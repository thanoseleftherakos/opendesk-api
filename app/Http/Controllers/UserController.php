<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use \Auth;
use App\Hotel;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function index(){
        $user =  Auth::user();
        return $this->createResponse($user,'',200);
    }

    public function update(Request $request){ 
        $user =  Auth::user();
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'old_password' => ['required_if:change_password,1'],
            'new_password' => ['required_if:change_password,1'],
            'new_password_again' => ['required_if:change_password,1|same:new_password']
        ]);

        $user->name = $request['name'];
        $user->email = $request['email'];
        $hotel = Hotel::find(Auth::user()->hotel->id);
        $response = array(
            'hotel' => $hotel,
            'user' => $user
        );
        if($request['old_password']){
            if(app('hash')->check($request['old_password'], $user->password)) {
                if($request['change_password']){
                    $user->password = app('hash')->make($request['new_password']);
                }
                $user->update();
                return $this->createResponse($response,'User updated successfully',200);
            }
            return $this->createResponse($user,'Wrong password! Please try again',501);
        }
        $user->update();


        return $this->createResponse($response,'User updated successfully',200);

    }
    

}
