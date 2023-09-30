<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use App\Models\User;
use App\Traits\LoginTrait;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;

class ServiceController extends Controller
{
    CONST LINKEDIN_TYPE = 'linkedin' ;
    //social login 
    use LoginTrait;
    // social service
    public function redirect($service){
        if(isset($service)){
        return $this->returnData("services",$service);
        return Socialite::driver($service)->redirect();}
        else {return 'null';}
    }
    // google 
    public function callback(){
         $user =  Socialite::driver('google')->user();
         $users=$user;
         return $this->returnData("user",$users);
         $finduser = User::where('social_id',$users->id)->get()->value('social_id');
         if($finduser>0){
            $checkuser = $this->checkservice($users);
            if($checkuser==true){return Redirect::route('homepage');}
            else{return back()->with('error', 'Wrong Login Details');}
        }
         else{
            User::create([
                'name'=> $users->name,
                'email'=> $users->email,
                'gmail'=>$users->email,
                'profile_photo'=>$users->avatar,
                'phone',
                'password'=> $users->id,
                'role'=>Role::CUSTOMER,
                'remember_token' => Str::random(10),
                'social_id'=>$users->id,
                'social_type'=>'google',
             ]);        
             
             $checkuser = $this->checkservice($users);
             if($checkuser==true){return Redirect::route('homepage');}
             else{return back()->with('error', 'Wrong Login Details');}
             
        }   

    }

       // githup 
       public function githubcallback(){
        $user =  Socialite::driver('github')->user();
        $users=$user;
        $finduser = User::where('social_id',$users->id)->get()->value('social_id');
        if($finduser>0){
           $checkuser = $this->checkservice($users);
           if($checkuser==true){return Redirect::route('homepage');}
           else{return back()->with('error', 'Wrong Login Details');}
       }
        else{
           User::create([
               'name'=> $users->name,
               'email'=> $users->email,
               'gmail'=>$users->email,
               'profile_photo'=>$users->avatar,
               'phone',
               'password'=> $users->id,
               'role'=>Role::CUSTOMER,
               'remember_token' => $users->token,
               'social_id'=>$users->id,
               'social_type'=>'githup',
            ]);        
            
            $checkuser = $this->checkservice($users);
            if($checkuser==true){return Redirect::route('homepage');}
            else{return back()->with('error', 'Wrong Login Details');}
            
       }   

   }

   // linkedin
   public function linkedincallback(){
           
    $user =  Socialite::driver(static::LINKEDIN_TYPE)->user();
    $users=$user;
    dd($users);
    $finduser = User::where('social_id',$users->id)->get()->value('social_id');
    if($finduser>0){
       $checkuser = $this->checkservice($users);
       if($checkuser==true){return Redirect::route('homepage');}
       else{return back()->with('error', 'Wrong Login Details');}
   }
    else{
       User::create([
           'name'=> $users->name,
           'email'=> $users->email,
           'gmail'=>$users->email,
           'profile_photo'=>$users->avatar,
           'phone',
           'password'=> $users->id,
           'role'=>Role::CUSTOMER,
           'remember_token' => $users->token,
           'social_id'=>$users->id,
           'social_type'=>'linkedin',
        ]);        
        
        $checkuser = $this->checkservice($users);
        if($checkuser==true){return Redirect::route('homepage');}
        else{return back()->with('error', 'Wrong Login Details');}
        
   } 

   }


   //facebook
   public function facebookcallback(){

    $user =  Socialite::driver('facebook')->user();
        $users=$user;
        $finduser = User::where('social_id',$users->id)->get()->value('social_id');
        if($finduser>0){
           $checkuser = $this->checkservice($users);
           if($checkuser==true){return Redirect::route('homepage');}
           else{return back()->with('error', 'Wrong Login Details');}
       }
        else{
           User::create([
               'name'=> $users->name,
               'email'=> $users->email,
               'gmail'=>$users->email,
               'profile_photo'=>$users->avatar,
               'phone',
               'password'=> $users->id,
               'role'=>Role::CUSTOMER,
               'remember_token' => $users->token,
               'social_id'=>$users->id,
               'social_type'=>'facebook',
            ]);        
            
            $checkuser = $this->checkservice($users);
            if($checkuser==true){return Redirect::route('homepage');}
            else{return back()->with('error', 'Wrong Login Details');}
            
       }   
    }
}
