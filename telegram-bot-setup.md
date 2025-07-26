# Telegram 回声机器人设置指南

## 1. 创建 Telegram Bot

1. 在 Telegram 中找到 @BotFather
2. 发送 `/newbot` 命令
3. 按照提示设置机器人名称和用户名
4. 获取 Bot Token（类似：`123456789:ABCdefGHIjklMNOpqrsTUVwxyz`）

## 2. 配置环境变量

在你的 `.env` 文件中添加以下配置：

```env
# Telegram Bot 配置
TELEGRAM_BOT_TOKEN=你的_Bot_Token
TELEGRAM_BOT_USERNAME=你的机器人用户名
```

## 3. 设置 Webhook

有两种方式设置 Webhook：

### 方式一：使用 Artisan 命令（推荐）

```bash
php artisan telegram:bot set-webhook
```

### 方式二：通过浏览器访问

访问：`http://你的域名/telegram/set-webhook`

## 4. 验证设置

### 检查 Webhook 信息
```bash
php artisan telegram:bot get-webhook-info
```

### 检查 Bot 信息
```bash
php artisan telegram:bot get-me
```

### 通过浏览器检查
访问：`http://你的域名/telegram/webhook-info`

## 5. 测试机器人

1. 在 Telegram 中找到你的机器人
2. 发送 `/start` 命令
3. 发送任意消息，机器人会回复 "你说：[你的消息]"

## 6. 可用的管理命令

```bash
# 设置 Webhook
php artisan telegram:bot set-webhook

# 获取 Webhook 信息
php artisan telegram:bot get-webhook-info

# 删除 Webhook
php artisan telegram:bot delete-webhook

# 获取 Bot 信息
php artisan telegram:bot get-me
```

## 7. 可用的 Web 路由

- `POST /telegram/webhook` - Webhook 接收端点
- `GET /telegram/set-webhook` - 设置 Webhook
- `GET /telegram/webhook-info` - 获取 Webhook 信息
- `GET /telegram/delete-webhook` - 删除 Webhook

## 8. 功能说明

这个回声机器人具有以下功能：

1. **回声功能**：重复用户发送的任何文本消息
2. **欢迎消息**：当用户发送 `/start` 时显示欢迎信息
3. **错误处理**：包含完整的错误处理和日志记录
4. **管理工具**：提供命令行和 Web 接口管理 Webhook

## 9. 注意事项

1. 确保你的服务器支持 HTTPS（Telegram 要求 Webhook 使用 HTTPS）
2. 确保 Webhook URL 可以从外网访问
3. Bot Token 要保密，不要泄露给他人
4. 如果需要修改机器人逻辑，编辑 `app/Http/Controllers/TelegramBotController.php` 文件

## 10. 故障排除

如果机器人不工作，请检查：

1. Bot Token 是否正确设置
2. Webhook URL 是否可访问
3. 服务器是否支持 HTTPS
4. 查看 Laravel 日志文件：`storage/logs/laravel.log`
