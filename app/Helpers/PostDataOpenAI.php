<?php

namespace App\Helpers;

class PostDataOpenAI{
    public static function PostData($conversation, $phone)
    {
        $conn = mysqli_connect(env('DB_MYSQLIHOST'), env('DB_MYSQLINAME'), env('DB_MYSQLIPASS'), env('DB_MYSQLINAME')); 
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } 
    
            $sql = "INSERT INTO leads (source, description, phone, lawyer, created_at, responsible, status, service)
            VALUES ('OpenAI', '$conversation', '$phone', 80, CURRENT_TIME(), 80, 'поступил', 11)"; //82 - данил, 11 - консультация
            $conn->query($sql);
        }
    }