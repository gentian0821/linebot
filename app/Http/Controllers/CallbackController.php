<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CallbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            [ 'name' => 'Yohei' ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $access_token = '83lp46KV9G7kxQwpadkxMI6+4X6d+ByUAY/pkAqZ+QTYbmknMS13VNiXDWPNp7WorKSGwA+aRKXxdHyipGRTeZ6nX5o4u5t1CGa0ciTuAykVTxJ4LS4gm9+APoQFqCfLltgJGtLqzwm0fOUuTWrCYQdB04t89/1O/w1cDnyilFU=';

        //APIから送信されてきたイベントオブジェクトを取得
        $json_string = file_get_contents('php://input');
        $json_obj = json_decode($json_string);

        //イベントオブジェクトから必要な情報を抽出
        $message = $json_obj->{"events"}[0]->{"message"};
        $reply_token = $json_obj->{"events"}[0]->{"replyToken"};
        //ユーザーからのメッセージに対し、オウム返しをする
        $post_data = [
            "replyToken" => $reply_token,
            "messages" => [
                [
                    "type" => "text",
                    "text" => $message->{"text"}
                ]
            ]
        ];

        //curlを使用してメッセージを返信する
        $ch = curl_init("https://api.line.me/v2/bot/message/reply");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charser=UTF-8',
            'Authorization: Bearer ' . $access_token
        ));
        $result = curl_exec($ch);
        curl_close($ch);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
