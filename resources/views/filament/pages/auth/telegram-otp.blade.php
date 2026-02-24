<x-filament-panels::page.simple>
    <x-filament-panels::form wire:submit="verify">
        {{ $this->form }}

        <div class="mt-4" wire:poll.1000ms="updateTimer">
            @if ($timeLeft > 0)
                <p class="text-sm text-center text-gray-500 dark:text-gray-400">
                    Sisa waktu: <span class="font-bold text-primary-600">{{ $timeLeft }}</span>
                    detik
                </p>
            @else
                <p class="text-sm text-center text-danger-500">
                    Waktu OTP Habis.
                </p>
            @endif
        </div>

        <x-filament::button type="submit" class="w-full mt-4">
            Akses Panel Admin
        </x-filament::button>
    </x-filament-panels::form>

    <div class="mt-4 flex justify-center">
        @if ($timeLeft <= 0)
            <x-filament::button color="gray" wire:click="resendOtp">
                Request Ulang OTP Telegram
            </x-filament::button>
        @else
            <x-filament::button color="gray" disabled>
                Request Ulang OTP Telegram
            </x-filament::button>
        @endif
    </div>
</x-filament-panels::page.simple>
