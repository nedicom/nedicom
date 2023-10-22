<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Rules\ReCaptcha;


    class PostphoneController extends Controller
        {
            public function postphone(Request $request): RedirectResponse
                {
                    $validated = $request->validate([                
                            'phone' => 'required|max:12|min:10',
                            'token' => [new Recaptcha],
                        ],
                        [
                            'phone.max' => 'Телефон не должен быть более 12 цифр',
                            'phone.min' => 'Телефон не должен быть меньше 10 цифр',
                        ]
                    );       
                    
                    //if(!config('app.debug'))  ! - for development
                                  
                                    $phone = $request->phone;
                                    $conn = mysqli_connect("localhost", "crm", "904klfkFL:DlflrD4", "crm");
                                        if ($conn->connect_error) {
                                        die("Connection failed: " . $conn->connect_error);
                                        } 
                    
                                    $sql = "INSERT INTO leads (source, description, phone, lawyer, created_at, responsible, status, service)
                                    VALUES ('nedicom.ru', 'лид с ".$request->url."', $phone, 2, CURRENT_TIME(), 2, 'поступил', 5)"; //2 - Mark, 4 - Анастасия, 5 - иск, 67 - вера
                                    $conn->query($sql);
                                    return redirect()->back()->with('message', 'Ваш телефон отправлен. Скоро мы Вам перезвоним!');
                        
                    return redirect()->back()->with('message', 'Ваш телефон отправлен. Скоро мы Вам перезвоним!');
                }       
        }