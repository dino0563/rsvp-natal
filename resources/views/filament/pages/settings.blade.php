<x-filament-panels::page>

    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        <div class="mt-6">
            <x-filament::button type="submit">Simpan</x-filament::button>
        </div>
    </form>

    <x-filament-actions::modals />
</x-filament-panels::page>
