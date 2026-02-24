<x-filament-panels::page.simple>
    <x-filament-panels::form wire:submit="verify">
        {{ $this->form }}

        <div class="mt-4" wire:poll.1000ms="updateTimer">
            @if ($expiring = \Carbon\Carbon::parse($expiresAt)->isFuture())
                <p class="text-sm text-center text-gray-500 dark:text-gray-400">
                    Sisa waktu: <span
                        class="font-bold text-primary-600">{{ \Carbon\Carbon::parse($expiresAt)->diffInSeconds(now()) }}</span>
                    detik
                </p>
            @else
                <p class="text-sm text-center text-danger-500">
                    Target kedaluwarsa.
                </p>
            @endif
        </div>

        <x-filament::button type="submit" class="w-full mt-4">
            Akses Panel Admin
        </x-filament::button>
    </x-filament-panels::form>

    <div class="mt-4 flex justify-center">
        @if (!\Carbon\Carbon::parse($expiresAt)->isFuture())
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
