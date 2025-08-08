<?php

namespace App\Telegram\Conversations;

use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\WebApp\WebAppInfo;

class StartConversation extends InlineMenu
{
    public function start(): void
    {
        $this->menuText("👋 Ассаляму алейкум!\n\nВыберите один из разделов:")
            ->addButtonRow(
                InlineKeyboardButton::make(
                    text: 'Работаем братья, работаем!',
                    web_app: new WebAppInfo('https://revolution-paste-hood-motherboard.trycloudflare.com')
                )
            )

            ->addButtonRow(
                InlineKeyboardButton::make(
                    text: '🆘 Поддержка',
                    callback_data: 'support')
            );

        $this->showMenu();
    }
}
