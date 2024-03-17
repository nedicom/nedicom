<?php

namespace App\Helpers;


class OpenAIDialogue{
    public static function Answer($ask, $array_conversation)
    {
            $ch = curl_init();

            $proxy = env('PROXY');
            $proxyauth = env('PROXY_AUTH');
            $url = 'https://api.openai.com/v1/chat/completions';
            $mesquantity = count($array_conversation);

            $data = array(
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a lawyer. 
                        '
                    ],
                    [
                        'role' => 'system',
                        'content' => 'Your task is to: Understand the language of the question.
                            Clarify the question if it is unclear. Invite the user to leave a phone number for contact under some pretext.'
                    ],
                    [
                        'role' => 'system',
                        'content' => 'Your message should be on russian and should not be more than 200 characters.'
                    ],
                    [
                        'role' => 'system',
                        'content' => "If the user's message contains a phone number, give a one-word response 'challenge'"
                    ],
                 ],
                'temperature' => 0.5,
                'max_tokens' => 500
            );

            if($mesquantity > 1){
                foreach ($array_conversation as $val) {
                    if(array_key_first($val) == 'user_message')
                    {
                        $data['messages'][] = 
                        [
                            'role' => 'user',
                            'content' => $val['user_message']
                        ];
                    }
                    if(array_key_first($val) == 'ai_message')
                    {
                        $data['messages'][] = 
                        [
                            'role' => 'assistant',
                            'content' => $val['ai_message']
                        ];
                    }
                }
            }

            $data['messages'][] = 
                [
                    'role' => 'user',
                    'content' => $ask,
                ];

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