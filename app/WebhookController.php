<?php

namespace App;

use TelegramBot\Api\{
    Client,
    InvalidJsonException,
    Types\Update,
};
use App\Commands\MainMenu;
use App\Models\User;

class WebhookController
{

    protected string $handlerClassName = '';

    /**
     * @throws InvalidJsonException
     */
    public function handle()
    {
        $client = new Client(getenv('TELEGRAM_BOT_TOKEN'));

        $client->on(function (Update $update) {

            if ($update->getCallbackQuery()) {
                $this->processCallbackCommand($update);
            } elseif ($update->getMessage()) {
                $text = $update->getMessage()->getText();

                $this->processSlashCommand($text);
                $this->processKeyboardCommand($text);
                $this->processStatusCommand($update);
            }

            $this->checkHandlerClassName();
            (new $this->handlerClassName($update))->handle();
        }, function (Update $update) {
            return true;
        });

        $client->run();
    }

    protected function checkHandlerClassName()
    {
        if (!$this->handlerClassName) {
            $this->handlerClassName = MainMenu::class;
        }
    }

    protected function processCallbackCommand(Update $update)
    {
        $config = include_once(__DIR__ . '/config/callback_commands.php');
        $action = \json_decode($update->getCallbackQuery()->getData(), true)['a'];

        if (isset($config[$action])) {
            $this->handlerClassName = $config[$action];
        }
    }

    protected function processSlashCommand(string $text)
    {
        if (str_starts_with($text, '/')) {
            $handlers = include_once(__DIR__ . '/config/slash_commands.php');
            $this->handlerClassName = $handlers[$text];
        }
    }

    protected function processKeyboardCommand(string $text)
    {
        if (!$this->handlerClassName) {
            $config = include(__DIR__ . '/config/text.php');
            $translations = \array_flip($config);
            if (isset($translations[$text])) {
                $key = $translations[$text];
                $handlers = include_once(__DIR__ . '/config/keyboard_commands.php');
                $this->handlerClassName = $handlers[$key];
            }
        }
    }

    protected function processStatusCommand(Update $update)
    {
        if (!$this->handlerClassName) {
            $handlers = include_once(__DIR__ . '/config/status_commands.php');
            $user = User::where('chat_id', $update->getMessage()->getFrom()->getId())->first();
            if (isset($handlers[$user->status])) {
                $this->handlerClassName = $handlers[$user->status];
            }
        }
    }

}
