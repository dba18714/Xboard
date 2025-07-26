<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramBotController extends Controller
{

    /**
     * 处理 Telegram webhook
     */
    public function webhook(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            // 获取更新
            $update = Telegram::getWebhookUpdates();
            
            // 记录接收到的更新
            Log::info('Telegram update received', ['update' => $update]);
            
            // 获取消息内容
            $message = $update->getMessage();
            if ($message !== null) {
                $chat_id = $message->getChat()->getId();
                $text = $message->getText();

                // 记录消息内容
                Log::info('Processing message', ['chat_id' => $chat_id, 'text' => $text]);

                // 处理文本消息
                if ($text) {
                    $processingTime = round((microtime(true) - $startTime) * 1000, 2);
                    $response = Telegram::sendMessage([
                        'chat_id' => $chat_id,
                        'text' => "Laravel 你说: {$text}\n处理用时: {$processingTime}ms",
                    ]);
                    
                    // 记录发送响应
                    $processingTime = round((microtime(true) - $startTime) * 1000, 2);
                    Log::info('Message processed', [
                        'text' => $text,
                        'processing_time_ms' => $processingTime
                    ]);
                }
            }

            return response()->json(['status' => 'success']);
            
        } catch (\Exception $e) {
            // 记录错误
            Log::error('Telegram webhook error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * 设置 webhook
     */
    public function setWebhook()
    {
        try {
            $webhookUrl = config('app.url') . '/telegram/webhook';
            
            $response = Telegram::setWebhook([
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
            $webhookInfo = Telegram::getWebhookInfo();
            
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
            $response = Telegram::deleteWebhook();
            
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
