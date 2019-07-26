<?php

namespace App\Services;

use App\Task;
use App\Translate;
use Google\Cloud\Translate\TranslateClient;
use Illuminate\Support\Facades\Config;
use Google\Cloud\Vision\VisionClient;
use Illuminate\Support\Facades\Log;

class AnalyzeMessageService
{
    /**
     * @param $events
     * @return array
     */
    public function reply_message($events)
    {
        if (isset($events['message']['type']) && $events['message']['type'] === 'image') {
            return $this->image($events['message']);
        }

        if (isset($events['postback'])) {
            return $this->regist_postback($events['postback']);
        }

        $send_to = $events['source']['groupId'] ?? $events['source']['roomId'] ?? $events['source']['userId'];

        $result = $this->regist_datetimepicker($events['message']["text"], $send_to);
        if ($result) {
            return $result;
        }

        $result = $this->regist($events['message']["text"], $send_to);
        if ($result) {
            return $result;
        }

        $result = $this->delete($events['message']["text"]);
        if ($result) {
            return $result;
        }

        $result = $this->fetch($events['message']["text"], $send_to);
        if ($result) {
            return $result;
        }

        $result = $this->translate($events['message']["text"]);
        if ($result) {
            return $result;
        }

        $key = rand(1,4);

        $default_messages = [
            1 => [
                'type' => 'text',
                'text' => 'ふぁいしーふぉーだよー！'
            ],
            2 => [
                'type' => 'text',
                'text' => $events['message']["text"]
            ],
            3 => [
                'type' => 'image',
                'originalContentUrl' => 'https://linebot-fayc4.herokuapp.com/img/kakkun2.jpg',
                'previewImageUrl'    => 'https://linebot-fayc4.herokuapp.com/img/kakkun2_thum.jpg'
            ],
            4 => [
                'type' => 'image',
                'originalContentUrl' => 'https://linebot-fayc4.herokuapp.com/img/yuika2.jpg',
                'previewImageUrl'    => 'https://linebot-fayc4.herokuapp.com/img/yuika2_thum.jpg'
            ]
        ];

        return $default_messages[$key];
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

    private function regist_datetimepicker($push_text, $send_to)
    {
        if (!preg_match('/^登録 (.*)/u',$push_text, $matches)) {
            return [];
        }

        $template = [
            'type'    => 'buttons',
            'text'    => 'いつにするー？',
            'actions' => [
                [
                    'type' => 'datetimepicker',
                    'label' => '日時を選ぶ',
                    'mode' => 'datetime',
                    'data' => 'send_message=' . $matches[1] . '&send_to=' . $send_to,
                    'initial' => date('Y-m-d\TH:00'),
                ],
                [
                    'type' => 'postback',
                    'label' => 'やめる',
                    'data' => 'send_message=' . $matches[1] . '&send_to=' . $send_to,
                ]
            ]
        ];

        return [
            'type'     => 'template',
            'altText'  => '代替テキスト',
            'template' => $template
        ];
    }

    private function translate($push_text)
    {
        if (!preg_match('/^翻訳 (.*)/u',$push_text, $matches)) {
            return [];
        }

        $source_text = $matches[1];
        $ascii_count = 0;
        $multibyte_count = 0;
        $length = mb_strlen($source_text, 'UTF-8');
        for ($i = 0; $i < $length; $i += 1) {
            $char = mb_substr($source_text, $i, 1, 'UTF-8');
            if (mb_check_encoding($char, 'ASCII')) {
                $ascii_count++;
                continue;
            }
            $multibyte_count++;
        }

        $translate_lang = 'en';
        if ($ascii_count > $multibyte_count) {
            $translate_lang = 'ja';
        }

        $translate_client = new TranslateClient(['key' => Config::get('const.translation_api_key')]);
        $result = $translate_client->translate(
            $matches[1],
            ['target' => $translate_lang]
        );

        $translate = new Translate;
        $translate->text_size = mb_strlen($matches[1]);
        $translate->source_text = $matches[1];
        $translate->translate_text = $result['text'];
        $translate->save();

        return [
            'type' => 'text',
            'text' => $result['text'],
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

        $message = "お知らせ予定だよー！\n------------------";
        foreach ($tasks as $task) {
            $date = new \DateTime($task->reserved_at);
            $message .=  "\n" . $task->id . ': ' . $date->format('Y/m/d G:i') . ' ' . $task->send_message;
        }

        return [
            'type' => 'text',
            'text' => $message
        ];
    }

    private function delete($push_text)
    {
        if (strpos($push_text, '消して') === false && strpos($push_text, '削除') === false) {
            return [];
        }

        if (!preg_match('/^([0-9]{1,}).*/u',$push_text, $id_matches)) {
            return [];
        }

        $task = Task::find($id_matches[1]);

        $task->delete();

        return [
            'type' => 'text',
            'text' => '削除したよー！'
        ];
    }


    /**
     * @param $postback
     * @return array
     */
    private function regist_postback($postback)
    {
        if (!isset($postback['params'])) {
            return [
                'type' => 'text',
                'text' => 'やめたよー！！'
            ];
        }

        $data = explode('&', $postback['data']);

        $date_time = new \DateTime($postback['params']['datetime']);
        $task = new Task;
        $task->send_to = explode('=', $data[1])[1];
        $task->send_message = explode('=', $data[0])[1];
        $task->reserved_at = $date_time->format('Y-m-d H:00:00');

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
    private function image($message)
    {
        $message_api = new MessageApiService();
        $response = $message_api->contents($message['id']);

        $json = json_encode([
            "requests" => [
                [
                    "image" => [
                        "content" => base64_encode($response->getBody())
                    ],
                   "features" => [
                        [
                            "type" => "TEXT_DETECTION" ,
                            "maxResults" => 3 ,
                        ] ,
                    ],
                ],
            ],
        ]);

        $curl = curl_init() ;
        curl_setopt( $curl, CURLOPT_URL, "https://vision.googleapis.com/v1/images:annotate?key=" . Config::get('const.cloud_vision_api_key') ) ;
        curl_setopt( $curl, CURLOPT_HEADER, true ) ;
        curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, "POST" ) ;
        curl_setopt( $curl, CURLOPT_HTTPHEADER, array( "Content-Type: application/json" ) ) ;
        curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false ) ;
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true ) ;
        curl_setopt( $curl, CURLOPT_TIMEOUT, 15 ) ;
        curl_setopt( $curl, CURLOPT_POSTFIELDS, $json ) ;
        $res1 = curl_exec( $curl ) ;
        $res2 = curl_getinfo( $curl ) ;
        curl_close( $curl ) ;

        // 取得したデータ
        $res = substr( $res1, $res2["header_size"] ) ;				// 取得したJSON
        $header = substr( $res1, 0, $res2["header_size"] ) ;		// レスポンスヘッダー

        Log::info($res);

        return [
            'type' => 'text',
            'text' => $res['responses']['fullTextAnnotation']['text']
        ];
    }
}
