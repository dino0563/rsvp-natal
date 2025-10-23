<?php

namespace App\Http\Controllers;

use App\Enums\StatusTicket;
use App\Models\Registration;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Bus;
use App\Jobs\SendTicketWaJob;

class PublicRegistrationController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $data = $request->validate([
            'nama'           => ['required','string','max:255'],
            // terima 08xxx, 62xxx, atau +62xxx
            'telp'           => ['required','string','regex:/^(?:\+62|62|0)\d{9,14}$/'],
            'usia'           => ['required','integer','between:10,50'],
            'jenjang'        => ['required','string','max:255'],
            'sekolah'        => ['required','string','max:255'],
            'gereja'         => ['required','string','max:255'],
            'gereja_manual'  => ['nullable','string','max:255','required_if:gereja,lainnya'],
            'consent'        => ['accepted'],
            'informasi'      => ['nullable','string','max:255'],
        ], [], [
            'nama'          => 'Nama',
            'telp'          => 'Nomor Telepon',
            'usia'          => 'Usia',
            'jenjang'       => 'Jenjang Pendidikan',
            'sekolah'       => 'Sekolah/Kampus',
            'gereja'        => 'Gereja',
            'gereja_manual' => 'Nama Gereja (Lainnya)',
            'consent'       => 'Persetujuan',
            'informasi'     => 'Informasi',
        ]);

        // Normalisasi telepon jadi 62xxxxxxxxxx (tanpa +)
        $phone = $this->normalizePhone($data['telp']);
        if (!$phone) {
            return back()->withErrors(['telp' => 'Nomor telepon tidak valid.'])->withInput();
        }

        // Pakai gereja_manual jika pilih "lainnya"
        $church = $data['gereja'] === 'lainnya'
            ? ($data['gereja_manual'] ?? '')
            : $data['gereja'];

        // Sumber opsional
        $source = $request->input('source')
            ?? $request->query('utm_source')
            ?? $request->query('ref')
            ?? 'landing';

        // Tolak jika sudah ada nomor yang sama
        if (Registration::where('phone', $phone)->exists()) {
            return back()
                ->withErrors(['telp' => 'Nomor ini sudah terdaftar. Cek WhatsApp untuk tiketmu.'])
                ->withInput();
        }

        try {
            $r = Registration::create([
                'name'            => $data['nama'],
                'phone'           => $phone,
                'age'             => (int) $data['usia'],
                'education_level' => $data['jenjang'],
                'school'          => $data['sekolah'],
                'church'          => $church,
                'source'          => $data['informasi'],
                // biarkan default 'pending' dari DB kalau enum kamu belum jelas
                'status_ticket'   => StatusTicket::PENDING, // pastikan enum backed ke 'pending'
                // 'wa_last_status' biarkan default 'queued' dari DB
                'wa_opt_out'      => false,
            ]);

            // Kalau kamu punya Observer yang isi ticket_code, ticket_url, qr_path, panggil refresh
            $r->refresh();
            Bus::dispatchSync(new SendTicketWaJob($r->id));

            return redirect()->to($r->ticket_url ?? url('/'));
        } catch (QueryException $e) {
            // 23000 = pelanggaran constraint/unique
            if ($e->getCode() === '23000') {
                return back()
                    ->withErrors(['telp' => 'Nomor ini sudah terdaftar. Cek WhatsApp untuk tiketmu.'])
                    ->withInput();
            }
            throw $e;
        }
    }

    private function normalizePhone(string $raw): ?string
    {
        // buang non-digit, hasilkan 62xxxxxxxxxx
        $digits = preg_replace('/\D+/', '', $raw);
        if ($digits === null || $digits === '') return null;

        // +62xxxxxxxx -> 62xxxxxxxx
        if (str_starts_with($digits, '62')) {
            return $digits;
        }

        // 08xxxxxxxx -> 62xxxxxxxx
        if (str_starts_with($digits, '0')) {
            return '62' . substr($digits, 1);
        }

        // fallback: kalau kepalanya absurd, anggap invalid
        return null;
    }
}
