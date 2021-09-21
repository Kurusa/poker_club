<?php

namespace App\Commands\Admin\Mailing;

use App\Services\Status\{
    MailingStatusService,
    UserStatusService,
};
use TelegramBot\Api\{
    Exception,
    InvalidArgumentException,
    Types\ReplyKeyboardMarkup,
};
use App\Commands\BaseCommand;
use App\Models\Mailing;

class Text extends BaseCommand
{

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    function processCommand(array $params = [])
    {
        if ($this->user->status == UserStatusService::MAILING_TEXT) {
            $mailing = new Mailing;
            $mailing->user_id = $this->user->id;
            $mailing->status = MailingStatusService::CREATING;

            if ($this->update->getMessage()->getText() !== $this->text['skip']) {
                $mailing->text = $this->update->getMessage()->getText();
            }

            if (!$this->user->is_super_admin) {
                $mailing->club_id = $this->user->club()->first()->id;
            }

            $mailing->save();
            $this->triggerCommand(Image::class);
        } else {
            $this->user->update([
                'status' => UserStatusService::MAILING_TEXT,
            ]);

            $this->getBot()->sendMessageWithKeyboard(
                $this->user->chat_id,
                $this->text['writeMailingText'],
                new ReplyKeyboardMarkup([[
                    $this->text['cancel'],
                    $this->text['skip'],
                ]], false, true),
            );
        }
    }

}
