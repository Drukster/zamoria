<?php

namespace App\Telegram\Conversations;

use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;

class StartConversation extends InlineMenu
{
    public function start(): void
    {
        $this->menuText("👋 Ассаляму алейкум!\n\nВыберите один из разделов:")
            ->addButtonRow(
                InlineKeyboardButton::make(
                    text: '⚙️ Настройки',
                    callback_data: 'settings'
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
