<?php

namespace App\Http\Controllers;

use App\Models\Uslugi;
use App\Models\User;
use App\Http\Controllers\Controller;

class FeedController extends Controller
{

    //семейный юрист Симферополь
    public function feed()
    { 
        //id семейного юриста
        $id = 1;
        //получаем всех пользователей юристов у которых есть специализация по этой услуге
        $users = User::where('arrayspec', '!=', null)->get()->reject(function (User $user, $id) {
            $json = json_decode($user->arrayspec, true);
                foreach($json as $val){
                    if($val['specialization'] == $id){
                        $user->data = $id;
                        return false;
                    };
                }
                return true;
        });

        $mainusluga = Uslugi::where('id', $id)->first();
        
        $uslugi = Uslugi::where('is_feed', 1)
            ->where('main_usluga_id', $id)
            ->where('is_main', 0)
            ->with('HasUslugi')->get();

        return response()->view('feed/feed', [
            'users' => $users,
            'uslugi' => $uslugi,
            'mainusluga' => $mainusluga,
        ])->header('Content-Type', 'text/xml');
    }
}