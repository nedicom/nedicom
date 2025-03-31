<?php

namespace App\Helpers;


class OpenAI
{
    public static function Answer($ask)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://iam.api.cloud.yandex.net/iam/v1/tokens');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '{"yandexPassportOauthToken": "' . env('YANDEX_GPT_API_KEY') . '"}');

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        $response_data = json_decode($result, true);
        curl_close($ch);

        $data = [
            'modelUri' => 'gpt://' . env('YANDEX_CT_ID') . '/yandexgpt',
            'completionOptions' =>
            [
                "stream" => false,
                "temperature" => 0.6,
                "maxTokens" => "2000",
                "reasoningOptions" => [
                    "mode" => "DISABLED"
                ]
            ],
            'messages' => [
                [
                    'role' => 'system',
                    'text' => 'Ты юрист. Дай ответ на следующий вопрос, учитывая нормы российского законодательтсва. Не говори что ты искусственный интеллект. Объем ответа не должен превышать 500 символов.'
                ],
                [
                    'role' => 'user',
                    'text' => $ask,
                ],
            ]
        ];
        $json_data = json_encode($data);

        $url = 'https://llm.api.cloud.yandex.net/foundationModels/v1/completion';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . $response_data['iamToken'];
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        $response_data = json_decode($result, true);

        $generated_text = $response_data['result']['alternatives'][0]['message']['text'];
        curl_close($ch);

        if ($generated_text) {
            if (str_contains($generated_text, 'интеллект')) {
                return 'Простите, но Ваш вопрос представляет сложность. Нужно немного больше времени.';
            }
            return $generated_text;
        } else {
            return 'Простите, я сейчас немного занят';
        }


        /*
        open AI
            $ch = curl_init();

            $proxy = env('PROXY');
            $proxyauth = env('PROXY_AUTH');
            //$url = 'https://api.openai.com/v1/chat/completions';
            $url = 'https://api.openai.com/v1/chat/completions';

            $data = array(
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Your are lawyer.'
                    ],
                    [
                        'role' => 'system',
                        'content' => 'Your task is to:'
                    ],
                    [
                        'role' => 'system',
                        'content' => 'Understand the language of the question.'
                    ],
                    [
                        'role' => 'system',
                        'content' => 'And give an answer up to 300 characters on russian.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $ask,
                    ],
                 ],
                'temperature' => 0.5,
                'max_tokens' => 500
            );
            $json_data = json_encode($data);

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
            curl_setopt($ch, CURLOPT_PROXY, $proxy);
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);


            $headers = array();
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Authorization: Bearer '.env('OPENAI_API_KEY');
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_HEADER, 0);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            $response_data = json_decode($result, true);
            dd($result);
            $generated_text = $response_data['choices'][0]['message']['content'];
            curl_close($ch);
            return $generated_text;
            */
    }


    public static function Comment($ask, $comment_type)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://iam.api.cloud.yandex.net/iam/v1/tokens');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '{"yandexPassportOauthToken": "' . env('YANDEX_GPT_API_KEY') . '"}');

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        $response_data = json_decode($result, true);
        curl_close($ch);

        $data = [
            'modelUri' => 'gpt://' . env('YANDEX_CT_ID') . '/yandexgpt',
            'completionOptions' =>
            [
                "stream" => false,
                "temperature" => 0.6,
                "maxTokens" => "20000",
                "reasoningOptions" => [
                    "mode" => "DISABLED"
                ]
            ],
            'messages' => [
                [
                    'role' => 'system',
                    'text' => 'Ты - пользователь сайта. Твой пол не должен быть известен.'
                ],
                [
                    'role' => 'user',
                    'text' => 'Только что ты прочитал статью про ' . $ask . ' Дай ' . $comment_type .' комментарий к этой статье. Объем ответа не должен превышать 500 символов.',
                ],
            ]
        ];
        $json_data = json_encode($data);

        $url = 'https://llm.api.cloud.yandex.net/foundationModels/v1/completion';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . $response_data['iamToken'];
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        $response_data = json_decode($result, true);

        $generated_text = $response_data['result']['alternatives'][0]['message']['text'];
        curl_close($ch);

        if ($generated_text) {
            if (str_contains($generated_text, 'ya.ru')) {
                return 'Классная статья. Спасибо.';
            }
            return $generated_text;            
        } else {
            return 'Отличная статья, мне понравилась';
        }
    }
}
