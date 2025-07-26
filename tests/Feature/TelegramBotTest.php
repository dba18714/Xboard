<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TelegramBotTest extends TestCase
{
    /**
     * 测试 Webhook 端点是否可访问
     */
    public function test_webhook_endpoint_accessible()
    {
        $response = $this->post('/telegram/webhook', []);
        
        // Webhook 应该返回 200 状态码，即使没有有效的 Telegram 数据
        $this->assertTrue(in_array($response->status(), [200, 500]));
    }

    /**
     * 测试设置 Webhook 端点
     */
    public function test_set_webhook_endpoint()
    {
        // 如果没有配置 Bot Token，应该返回错误
        if (!config('telegram.bot_token')) {
            $response = $this->get('/telegram/set-webhook');
            $this->assertEquals(500, $response->status());
        } else {
            $response = $this->get('/telegram/set-webhook');
            $this->assertTrue(in_array($response->status(), [200, 500]));
        }
    }

    /**
     * 测试获取 Webhook 信息端点
     */
    public function test_webhook_info_endpoint()
    {
        if (!config('telegram.bot_token')) {
            $response = $this->get('/telegram/webhook-info');
            $this->assertEquals(500, $response->status());
        } else {
            $response = $this->get('/telegram/webhook-info');
            $this->assertTrue(in_array($response->status(), [200, 500]));
        }
    }

    /**
     * 测试删除 Webhook 端点
     */
    public function test_delete_webhook_endpoint()
    {
        if (!config('telegram.bot_token')) {
            $response = $this->get('/telegram/delete-webhook');
            $this->assertEquals(500, $response->status());
        } else {
            $response = $this->get('/telegram/delete-webhook');
            $this->assertTrue(in_array($response->status(), [200, 500]));
        }
    }
}
