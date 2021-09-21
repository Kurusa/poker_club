<?php

namespace App\Commands\Admin\Mailing;

use App\Services\Status\MailingStatusService;
use TelegramBot\Api\{
    Exception,
    InvalidArgumentException,
    Types\ReplyKeyboardMarkup,
};
use App\Commands\BaseCommand;
use App\Services\Status\UserStatusService;

class Image extends BaseCommand
{

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    function processCommand(array $params = [])
    {
        if ($this->user->status == UserStatusService::MAILING_IMAGE) {
            if ($this->update->getMessage()->getPhoto()) {
                $this->user->mailings()->where('status', MailingStatusService::CREATING)->update([
                    'image' => $this->update->getMessage()->getPhoto()[0]->getFileId(),
                ]);
            }
            if (!$this->user->is_super_admin) {
                $this->triggerCommand(Start::class);
            } else {
                $this->triggerCommand(Club::class);
            }
        } else {
            $this->user->update([
                'status' => UserStatusService::MAILING_IMAGE,
            ]);

            $this->getBot()->sendMessageWithKeyboard(
                $this->user->chat_id,
                $this->text['addMailingImage'],
                new ReplyKeyboardMarkup([[
                    $this->text['cancel'],
                    $this->text['skip'],
                ]], false, true),
            );
        }
    }

}
