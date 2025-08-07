<?php
/** @var SergiX44\Nutgram\Nutgram $bot */

use App\Http\Middleware\VerifyTelegramUser;
use App\Telegram\Conversations\StartConversation;

$bot->middleware(VerifyTelegramUser::class);

$bot->onCommand('start', StartConversation::class);
