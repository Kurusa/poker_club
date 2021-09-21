<?php

namespace App\Commands;

use App\Services\Status\UserStatusService;
use App\Models\User;
use App\Services\Utils\Api;
use TelegramBot\Api\Types\Update;
use Exception;

/**
 * Class BaseCommand
 * @package App\Commands
 */
abstract class BaseCommand
{
    protected User $user;

    protected $botUser;

    protected array $text;

    protected Update $update;

    protected ?string $userStatus;

    protected ?array $callbackData;

    private $bot;

    /**
     * @throws Exception
     */
    public function __construct(Update $update)
    {
        $this->update = $update;
        if ($update->getCallbackQuery()) {
            $this->botUser = $update->getCallbackQuery()->getFrom();
        } elseif ($update->getMessage()) {
            $this->botUser = $update->getMessage()->getFrom();
        } else {
            throw new Exception('Cant get telegram user data.');
        }
    }

    public function handle(array $params = [])
    {
        $this->user = User::firstOrCreate([
            'chat_id' => $this->botUser->getId()
        ], [
            'user_name'     => $this->botUser->getUsername(),
            'first_name'    => $this->botUser->getFirstName(),
            'status'        => UserStatusService::NEW,
        ]);

        $this->text = include(__DIR__ . '/../config/text.php');

        $this->setUserStatus();
        $this->processCommand($params);
    }

    /**
     * @return Api
     */
    protected function getBot(): Api
    {
        if (!$this->bot) {
            $this->bot = new Api(env('TELEGRAM_BOT_TOKEN'));
        }

        return $this->bot;
    }

    /**
     * @param $class
     * @param array $params
     */
    protected function triggerCommand($class, array $params = [])
    {
        (new $class($this->update))->handle($params);
    }

    private function setUserStatus()
    {
        if (isset($this->userStatus)) {
            $this->user->update([
                'status' => $this->userStatus
            ]);
        }
    }

    abstract function processCommand(array $params = []);

    protected function getCallbackData()
    {
        if (!isset($this->callbackData)) {
            $this->callbackData = json_decode($this->update->getCallbackQuery()->getData(), true);
        }

        return $this->callbackData;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function getCallbackDataByKey(string $key): string
    {
        if (!isset($this->callbackData)) {
            $this->getCallbackData();
        }

        return $this->callbackData[$key] ?? '';
    }

    protected function getMessageId(): int
    {
        if ($this->update->getCallbackQuery()) {
            $update = $this->update->getCallbackQuery();
        } else {
            $update = $this->update;
        }

        return $update->getMessage()->getMessageId();
    }

}
