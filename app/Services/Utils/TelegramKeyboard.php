<?php

namespace App\Services\Utils;

class TelegramKeyboard
{

    static ?string $action = '';

    static int $columns = 1;
    static array $list;

    static array $buttons = [];

    static function build()
    {
        if (self::$list) {
            $oneRow = [];

            foreach (self::$list as $key => $listKey) {
                $text = $listKey['text'] ?? $listKey;

                if (isset($listKey['callback'])) {
                    $callback = $listKey['callback'];
                } else {
                    $callback = [
                        'a'  => self::$action,
                        'id' => $key,
                    ];
                }

                $oneRow[] = [
                    'text'          => $text,
                    'callback_data' => json_encode($callback),
                ];

                if (count($oneRow) == self::$columns) {
                    self::$buttons[] = $oneRow;
                    $oneRow = [];
                }
            }

            if (count($oneRow) > 0) {
                self::$buttons[] = $oneRow;
            }
        }
    }

    /**
     * @param string $text
     * @param array $callback
     */
    static function addButton(
        string $text,
        array $callback,
    )
    {
        self::$buttons[] = [[
            'text'          => $text,
            'callback_data' => json_encode($callback),
        ]];
    }

    static function get(): array
    {
        return self::$buttons;
    }

}
