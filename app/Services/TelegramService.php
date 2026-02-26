<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected ?string $botToken;

    public function __construct()
    {
        $this->botToken = (string) config('services.telegram.bot_token', env('TELEGRAM_BOT_TOKEN', ''));
    }

    public function sendMessage(string $chatId, string $message): bool
    {
        // Bersihkan prefix 'bot', spasi, dan tanda kutip (" atau ') yang mungkin terbawa dari .env/server
        $cleanToken = preg_replace('/^bot/i', '', trim($this->botToken, '"\' '));

        if (empty($cleanToken)) {
            $errorMsg = 'Telegram API error: Bot token kosong. Pastikan TELEGRAM_BOT_TOKEN sudah diset, config cache sudah di-clear, atau server Octane/Roadrunner telah direstart.';
            Log::error($errorMsg);
            throw new \Exception($errorMsg);
        }

        try {
            // Gunakan withoutVerifying() untuk menghindari isu sertifikat SSL di production server
            $response = Http::withoutVerifying()->post("https://api.telegram.org/bot{$cleanToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
            ]);

            if (! $response->successful()) {
                $maskedToken = substr($cleanToken, 0, 5).str_repeat('*', max(0, strlen($cleanToken) - 10)).substr($cleanToken, -5);
                $logMsg = 'Telegram API error: '.$response->body().' | Token length: '.strlen($cleanToken).' | Masked: '.$maskedToken;
                Log::error($logMsg);
                throw new \Exception('Telegram API error: '.$response->body().' [Token check: length '.strlen($cleanToken).']');
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Telegram service exception: '.$e->getMessage());
            throw new \Exception('Telegram Error: '.$e->getMessage());
        }
    }
}
