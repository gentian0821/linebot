<?php

namespace App\Services;

use App\Task;

class AnalyzeMessageService
{
    /**
     * @param $events
     * @return array
     */
    public function reply_message($events)
    {
        $send_to = $events['source']['roomId'] ?? $events['source']['userId'];
        $result = $this->regist($events['message']["text"], $send_to);
        if ($result) {
            return $result;
        }

        $result = $this->fetch($events['message']["text"], $send_to);
        if ($result) {
            return $result;
        }

        return [
            'type' => 'text',
            'text' => $events['message']["text"]
        ];
    }

    /**
     * @param $push_text
     * @param $send_to
     * @return array
     */
    private function regist($push_text, $send_to)
    {
        if (strpos($push_text, '登録') === false) {
            return [];
        }

        if (!preg_match('/^(\d{1,2})\/(\d{1,2}) (\d{1,2})/u',$push_text, $date_matches)) {
            return [];
        }

        if (!preg_match('/「(.*)」/u',$push_text, $message_matches)) {
            return [];
        }

        $task = new Task;
        $task->send_to = $send_to;
        $task->send_message = $message_matches[1];
        $task->reserved_at = sprintf('2019-%02d-%02d %02d:00:00', $date_matches[1], $date_matches[2], $date_matches[3]);

        $task->save();

        return [
            'type' => 'text',
            'text' => '登録したよー！'
        ];
    }

    /**
     * @param $push_text
     * @param $send_to
     * @return array
     */
    private function fetch($push_text, $send_to) {
        if (strpos($push_text, 'リスト') === false) {
            return [];
        }

        $tasks = Task::where('reserved_at', '>', date('Y-m-d H:i:s'))
            ->where('send_to', $send_to)->orderBy('reserved_at')->get();

        $message = 'お知らせ予定だよー！';
        foreach ($tasks as $task) {
            $date = new \DateTime($task->reserved_at);
            $message .=  "\n" . $date->format('Y/m/d G:i') . ' ' . $task->send_message;
        }

        return [
            'type' => 'text',
            'text' => $message
        ];
    }
}
