<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Illuminate\Support\Facades\Log;

class TelegramBotController extends Controller
{
    protected $telegram;

    public function __construct()
    {
        $this->telegram = new Api(config('telegram.bot_token'));
    }

    /**
     * 处理 Telegram webhook
     */
    public function webhook(Request $request)
    {
        try {
            $update = $this->telegram->getWebhookUpdate();

            Log::info('Telegram Bot Webhook', [
                'update' => $update
            ]);
            
            // 检查是否有消息
            if ($update->getMessage()) {
                $message = $update->getMessage();
                $chatId = $message->getChat()->getId();
                $text = $message->getText();
                
                // 如果是 /start 命令
                if ($text === '/start') {
                    $this->telegram->sendMessage([
                        'chat_id' => $chatId,
                        'text' => '你好！我是一个回声机器人。发送任何消息给我，我会重复你说的话。'
                    ]);
                } else {
                    // 回声功能 - 重复用户发送的消息
                    $this->telegram->sendMessage([
                        'chat_id' => $chatId,
                        'text' => "你说：" . $text
                    ]);
                }
            }
            
            return response('OK', 200);
            
        } catch (TelegramSDKException $e) {
            \Log::error('Telegram Bot Error: ' . $e->getMessage());
            return response('Error', 500);
        }
    }

    /**
     * 设置 webhook
     */
    public function setWebhook()
    {
        try {
            $webhookUrl = config('app.url') . '/telegram/webhook';
            
            $response = $this->telegram->setWebhook([
                'url' => $webhookUrl
            ]);
            
            if ($response) {
                return response()->json([
                    'success' => true,
                    'response' => $response,
                    'message' => 'Webhook 设置成功',
                    'webhook_url' => $webhookUrl
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Webhook 设置失败'
            ]);
            
        } catch (TelegramSDKException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * 获取 webhook 信息
     */
    public function getWebhookInfo()
    {
        try {
            $webhookInfo = $this->telegram->getWebhookInfo();
            
            return response()->json([
                'success' => true,
                'webhook_info' => $webhookInfo
            ]);
            
        } catch (TelegramSDKException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * 删除 webhook
     */
    public function deleteWebhook()
    {
        try {
            $response = $this->telegram->deleteWebhook();
            
            return response()->json([
                'success' => true,
                'message' => 'Webhook 删除成功'
            ]);
            
        } catch (TelegramSDKException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}
