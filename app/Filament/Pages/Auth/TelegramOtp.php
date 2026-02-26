<?php

namespace App\Filament\Pages\Auth;

use App\Models\OtpLog;
use App\Services\TelegramService;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SimplePage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;
use Filament\Actions\Action;
use Carbon\Carbon;

class TelegramOtp extends SimplePage
{
    protected static bool $shouldRegisterNavigation = false;

    public static function getSlug(): string
    {
        return 'auth/telegram-otp';
    }

    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';

    protected static string $view = 'filament.pages.auth.telegram-otp';

    public ?string $otp = null;
    public string $expiresAt = '';
    public int $timeLeft = 0;

    public function mount()
    {
        $user = auth()->user();
        if (!$user || !$user->hasAnyRole(['admin', 'super-admin', 'admin-soal', 'admin-content', 'admin-finance', 'admin-full'])) {
            return redirect('/');
        }
        if (!$user->telegram_chat_id) {
            return redirect(config('filament.admin.path', '/admin'));
        }

        if (session()->has('otp_verified_at')) {
            return redirect(config('filament.admin.path', '/admin'));
        }

        $this->ensureOtpIsGenerated($user);
    }

    protected function ensureOtpIsGenerated($user)
    {
        $cacheKey = 'otp_' . $user->id;
        
        if (!Cache::has($cacheKey)) {
            $this->generateAndSendOtp($user);
        } else {
            $otpData = Cache::get($cacheKey);
            $this->expiresAt = $otpData['expires_at'];
            $diff = now()->diffInSeconds(\Carbon\Carbon::parse($this->expiresAt), false);
            $this->timeLeft = max(0, (int) $diff);
        }
    }

    public function generateAndSendOtp($user)
    {
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = now()->addMinutes(2);

        Cache::put('otp_' . $user->id, [
            'code' => $otp,
            'expires_at' => $expiresAt->toDateTimeString()
        ], $expiresAt);

        // Send via Telegram
        $telegramService = app(TelegramService::class);
        $message = "🔒 <b>Abdinara LMS Admin Login</b>\n\n";
        $message .= "Kode OTP Anda adalah: <code>{$otp}</code>\n";
        $message .= "<i>Kode ini akan kedaluwarsa dalam 2 menit. Jangan bagikan kode ini kepada siapa pun!</i>";
        
        try {
            $telegramService->sendMessage($user->telegram_chat_id, $message);

            $this->expiresAt = $expiresAt->toDateTimeString();
            $this->timeLeft = 120;
            
            \Filament\Notifications\Notification::make()
                ->title('OTP Terkirim')
                ->body('Kode OTP baru telah dikirimkan ke Telegram Anda.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            // Hapus cache karena pengiriman gagal
            Cache::forget('otp_' . $user->id);
            $this->timeLeft = 0;

            \Filament\Notifications\Notification::make()
                ->title('Gagal Mengirim OTP')
                ->body('Terjadi kesalahan Telegram (Mungkin API Blocked/Salah Chat ID/Bot Token). Error: ' . $e->getMessage())
                ->danger()
                ->persistent()
                ->send();
        }
    }

    public function updateTimer()
    {
        $diff = now()->diffInSeconds(\Carbon\Carbon::parse($this->expiresAt), false);
        $this->timeLeft = max(0, (int) $diff);
    }

    public function resendOtp()
    {
        $this->generateAndSendOtp(auth()->user());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('otp')
                    ->label('Kode OTP Telegram')
                    ->placeholder('Contoh: 123456')
                    ->required()
                    ->length(6)
                    ->numeric()
                    ->autocomplete('one-time-code')
                    ->autofocus(),
            ]);
    }

    public function verify()
    {
        $data = $this->form->getState();
        $user = auth()->user();
        $cacheKey = 'otp_' . $user->id;

        if (!Cache::has($cacheKey)) {
            OtpLog::create([
                'user_id' => $user->id,
                'token' => $data['otp'],
                'status' => 'expired',
                'expires_at' => now(),
            ]);

            throw ValidationException::withMessages([
                'otp' => 'Kode OTP Anda sudah kedaluwarsa. Silakan request ulang.',
            ]);
        }

        $otpData = Cache::get($cacheKey);

        if ($data['otp'] !== $otpData['code']) {
            throw ValidationException::withMessages([
                'otp' => 'Kode OTP salah.',
            ]);
        }

        // OTP is correct
        Cache::forget($cacheKey);
        session(['otp_verified_at' => now()]);

        OtpLog::create([
            'user_id' => $user->id,
            'token' => $data['otp'],
            'status' => 'success',
            'expires_at' => Carbon::parse($otpData['expires_at']),
        ]);

        return redirect()->intended('/admin');
    }

    public function getHeading(): string
    {
        return 'Verifikasi Keamanan Admin';
    }

    public function getSubheading(): ?string
    {
        return 'Masukkan 6 digit kode OTP yang telah dikirim ke Telegram Anda.';
    }

    public function hasLogo(): bool
    {
        return true;
    }
}
