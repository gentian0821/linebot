<?php

namespace packages\Infrastructure\Notify;

use App\Services\MessageApiService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class NotifyRepository implements NotifyRepositoryInterface
{
    private $messageApiService;

    public function __construct(MessageApiService $messageApiService)
    {
        $this->messageApiService = $messageApiService;
    }

    public function sendMessage(array $params): void
    {
        if (!$params) {
            return;
        }

        $this->messageApiService->push(
            [['type' => 'text', 'text' => $this->makeMessage($params)]],
            Config::get('const.fayc4_send_to')
        );
    }

    private function makeMessage(array $params): string
    {
        if (Str::contains('登下校', $params['subject']))
        {
            $message = explode('-------', $params['message'])[0];
            if (Str::contains('登校', $message)) {
                return 'ゆいちゃんが無事登校したよー️' . $this->picture_letter("1F604") .
                    "\n\n" . $message;
            }

            return 'ゆいかが帰ってくるぞー❗️❗️' . "\n\n" . $message;
        }

        if (Str::contains('そろばん塾：入室通知', $params['subject']))
        {
            return 'ゆいちゃんが無事そろばんに行ったよー️' . $this->picture_letter("1F604") .
                    "\n\n" . $params['message'];
        }

        if (Str::contains('そろばん塾：退室通知', $params['subject']))
        {
            return 'ゆいちゃんがそろばんから帰ってくるぞー️' . $this->picture_letter("1F61C") .
                "\n\n" . $params['message'];
        }

        return $message = "From: " . $params['from'] . "\nSubject: " . $params['subject'] .
            "\n\n" . $params['message'];
    }


    private function picture_letter($code)
    {
        // 16進エンコードされたバイナリ文字列をデコード
        $bin = hex2bin(str_repeat('0', 8 - strlen($code)) . $code);
        // UTF8へエンコード
        return mb_convert_encoding($bin, 'UTF-8', 'UTF-32BE');
    }
}
