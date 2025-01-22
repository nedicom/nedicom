<?php

namespace App\Helpers;


class OpenAI{
    public static function Answer($ask)
    {
            $ch = curl_init();

            $proxy = env('PROXY');
            $proxyauth = env('PROXY_AUTH');
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
            
            $generated_text = $response_data['choices'][0]['message']['content'];
            curl_close($ch);
            return $generated_text;
    }
}