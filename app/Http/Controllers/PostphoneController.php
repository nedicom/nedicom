<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
//use App\Rules\ReCaptcha;


    class PostphoneController extends Controller
        {
            public function postphone(Request $request): RedirectResponse
                {
                    $phone = '8'.$request->one . $request->two . $request->three . $request->four . $request->five .
                    $request->six . $request->seven . $request->eight . $request->nine . $request->ten;                                

                    $conn = mysqli_connect(env('DB_MYSQLIHOST'), env('DB_MYSQLINAME'), env('DB_MYSQLIPASS'), env('DB_MYSQLINAME')); 
                        if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                        } 
    
                    $sql = "INSERT INTO leads (source, description, phone, lawyer, created_at, responsible, status, service)
                    VALUES ('nedicom.ru форма', 'На nedicom оставил номер со страницы - ".$request->url."', $phone, 2, CURRENT_TIME(), 2, 'поступил', 5)"; //2 - Mark, 4 - Анастасия, 5 - иск, 67 - вера
                    $conn->query($sql);
                    return redirect()->route('Welcome')->with('message', 'Ваш телефон успешно отправлен! Скоро мы свяжемся с Вами.');                        
                }       
        }