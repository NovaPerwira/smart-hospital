<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Telegram Bot API wrapper.
 * Uses TELEGRAM_BOT_TOKEN and TELEGRAM_OWNER_CHAT_ID from .env
 */
class TelegramService
{
    private string $token;
    private string $chatId;
    private string $apiBase;

    public function __construct()
    {
        $this->token = config('services.telegram.bot_token', '');
        $this->chatId = config('services.telegram.owner_chat_id', '');
        $this->apiBase = "https://api.telegram.org/bot{$this->token}";
    }

    /**
     * Send a message to the owner's Telegram chat.
     *
     * @throws \RuntimeException on failure
     */
    public function sendMessage(string $text): array
    {
        if (empty($this->token) || empty($this->chatId)) {
            Log::warning('[telegram] Bot token or chat_id not configured — logging message instead.');
            Log::info('[telegram] Message:', ['text' => $text]);
            // Return a mock success so the job doesn't fail in dev
            return ['ok' => true, 'mock' => true];
        }

        $response = Http::timeout(15)->post("{$this->apiBase}/sendMessage", [
            'chat_id' => $this->chatId,
            'text' => $text,
            'parse_mode' => 'Markdown',
        ]);

        if (!$response->successful() || !($response->json('ok'))) {
            $error = $response->json('description') ?? $response->body();
            Log::error('[telegram] sendMessage failed', ['error' => $error]);
            throw new \RuntimeException("Telegram API error: {$error}");
        }

        Log::info('[telegram] Message sent successfully', [
            'chat_id' => $this->chatId,
            'message_id' => $response->json('result.message_id'),
        ]);

        return $response->json();
    }
}
