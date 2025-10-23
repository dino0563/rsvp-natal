<?php

use App\Enums\StatusTicket;
use App\Enums\WaStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('registrations', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('phone_raw');                  // 08...
            $t->string('phone_e164')->unique();       // +62...
            $t->unsignedTinyInteger('age')->nullable();
            $t->string('education_level')->nullable();
            $t->string('school')->nullable();
            $t->string('church')->nullable();
            $t->string('source')->nullable();

            $t->string('ticket_code')->nullable()->index();
            $t->string('ticket_url')->nullable();
            $t->string('qr_path')->nullable();

            $t->string('status_ticket')->default(StatusTicket::PENDING->value)->index();
            $t->string('wa_last_status')->default(WaStatus::QUEUED->value)->index();
            $t->text('wa_last_error')->nullable();
            $t->timestamp('wa_last_attempt_at')->nullable();

            $t->boolean('wa_opt_out')->default(false);

            $t->timestamps();
        });

        Schema::create('tickets', function (Blueprint $t) {
            $t->id();
            $t->foreignId('registration_id')->constrained()->cascadeOnDelete();
            $t->string('code')->unique();     // public token (short)
            $t->string('qr_hash')->unique();  // hashed payload for single-use verify
            $t->timestamp('used_at')->nullable()->index();
            $t->foreignId('used_by_staff_id')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
        });

        Schema::create('comms_logs', function (Blueprint $t) {
            $t->id();
            $t->foreignId('registration_id')->constrained()->cascadeOnDelete();
            $t->string('channel')->default('wa');
            $t->string('template_key'); // ticket|T-1|H-3
            $t->string('provider_message_id')->nullable()->index();
            $t->string('status')->nullable();
            $t->text('error')->nullable();
            $t->json('meta')->nullable();
            $t->timestamps();
        });

        Schema::create('settings', function (Blueprint $t) {
            $t->id();
            $t->string('key')->unique();
            $t->text('value')->nullable();
            $t->timestamps();
        });

        Schema::create('campaigns', function (Blueprint $t) {
            $t->id();
            $t->string('key');                         // ticket|T-1|H-3
            $t->timestamp('scheduled_at')->nullable(); // jadwal kirim
            $t->unsignedInteger('throttle_per_second')->default(5);
            $t->string('status')->default('idle');     // idle|running|done
            $t->timestamps();

            $t->index(['key','status']);
            $t->index('scheduled_at');
        });
    }

    public function down(): void {
        Schema::dropIfExists('campaigns');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('comms_logs');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('registrations');
    }
};
