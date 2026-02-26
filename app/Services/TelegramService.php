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
        // Bersihkan prefix 'bot' jika user terlanjur menempelkannya di .env
        $cleanToken = str_replace('bot', '', $this->botToken);

        try {
            // Gunakan withoutVerifying() untuk menghindari isu sertifikat SSL di production server
            $response = Http::withoutVerifying()->post("https://api.telegram.org/bot{$cleanToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
            ]);

            if (! $response->successful()) {
                Log::error('Telegram API error: '.$response->body());
                throw new \Exception('Telegram API error: '.$response->body());
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Telegram service exception: '.$e->getMessage());
            throw new \Exception('Telegram Error: '.$e->getMessage());
        }
    }
}
