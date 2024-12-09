<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\Uslugi;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request, ): Response 
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),  
            'uslugi'=>  Uslugi::orderBy('usl_name','desc')
            ->select('id', 'usl_name')
            ->where('is_main', 1)
            ->get(),
            'auth' => Auth::user(),          
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());
        if($request->lawyer == true){
            $request->user()->lawyer = 1;
        }
        else{
            $request->user()->lawyer = 0;
        }

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Update the user's profile information.
     */
    public function updatespec(Request $request)
    {   
        //check is admin

        //get array
        $user = User::find(Auth::user()->id);
        $userspec = $user -> arrayspec;              
        $usluhaid = $request -> arrayspec;
        $uslugavalue = $request -> arrayvalue; 
            if($userspec){
                $array = json_decode($userspec, JSON_FORCE_OBJECT);
                $array[] = ['specialization' => $usluhaid, 'value' => $uslugavalue];
                $user->arrayspec = json_encode($array);
            }
            else{
                $array = array(array('specialization' => $usluhaid, 'value' => $uslugavalue));
                $user->arrayspec = json_encode($array);
            }
        $user->save();
        return Redirect::route('profile.edit');
    }

    public function deletespec(Request $request): RedirectResponse
    {   
        $user = User::find(Auth::user()->id);
        $userspec = $user -> arrayspec;
        $usluhaid = $request -> id;
        $array = json_decode($userspec, JSON_FORCE_OBJECT);

        $k=0;
        $newarray=[];
            for($i = 0; $i < count($array); $i++){            
                        if ((array_search($usluhaid,  $array[$i])) == false) {
                            $newarray[$k] = $array[$i];  
                            $k++; 
                        }                      
            }

        $user->arrayspec = json_encode($newarray);

        $user->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
