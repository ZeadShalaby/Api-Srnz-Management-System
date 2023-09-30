<?php

namespace App\Http\Controllers\Api\users;

use Exception;
use Validator;
use App\Models\Role;
use App\Models\User;
use App\Models\Orders;
use App\Traits\ImgTrait;
use App\Models\Favourite;
use App\Traits\CountTrait;
use App\Traits\LoginTrait;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;



class UserController extends Controller
{
    use CountTrait,LoginTrait,ResponseTrait,ImgTrait;
    /**
     * Display a listing of the resource.
     */
    //// todo Return All Customer ////
    public function index()
    {
        //
        $user = auth()->user();
        $usersrole = Role::CUSTOMER;
        $users = User::where('role',Role::CUSTOMER)->get();
        return $this->returnData('users',$users);

    }
    //// todo Return All Admins ////
    public function admin(){
        $user = auth()->user();
        $usersrole = Role::CUSTOMER;
        $users = User::where('role',Role::ADMIN)->where('name','!=','Admin')->get();
        return $this->returnData('Admins',$users);
    }

    //// todo Return one Users by id  ////
    public function show(Request $request , User $user){
        $user = User::where('name','!=','Admin')->find($request->id);
        if(!isset($user)){return $this->returnError('404'," Not Found Users .");}
        return $this->returnData('user',$user);

    }
    
    //// todo Create New Admins ////
    public function store(Request $request){
        $rules = [
            "name" => "required|unique:users,name",
            "email" => "required|unique:users,email",
            "gmail" => "required|unique:users,gmail",
            "phone" => "required|unique:users,phone",
            "password" => "required",
        ];
        // ! valditaion
        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code,$validator);
            }
        else{    
        $user = User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'gmail'=>$request->gmail,
            'profile_photo'=>'https://via.placeholder.com/400x400.png/004444?text=itaque',
            'phone'=>$request->phone,
            'password'=>$request->password,
            'role'=>Role::ADMIN,
            'remember_token' => Str::random(10),
         ]);  }   
    }

    //// todo update users ////
    public function update(Request $request){
        $user=User::find($request->id);
        $rules = [
            "name" => "required",
            "email" => "required",
            "gmail" => "required",
            "phone" => "required",
            'profile_photo'=>"required",
            "password" => "required",
        ];
        // ! valditaion
        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code,$validator);
            }
        else{
            $edit = $user->update([
                'name_en'=> $request->name_en,
                'name_ar'=> $request->name_ar,
                'email'=> $request->email,
                'gmail'=>$request->gmail,
                'profile_photo'=>$request->profile_photo,
                'phone'=>$request->phone,
                'password'=> $request->password,
             ]); 
             $msg = "USers : ".$user->name." , Update successfully .";
             return $this->returnSuccessMessage($msg);}    
    }

 // ! Destroy Users
 public function destroy(Request $request,User $user)
 {
     // ! Delete my Account (Customers) //
     $user = User::find($user->id);
     if(Auth::user()->role == Role::CUSTOMER && Auth::user()->id ==$user){
         $user->delete();
         return $this->returnData("users".$user->name,"DeleteSuccessfuly");
    }        
     //! Delete Any Account (Admin) //
     if(Auth::user()->role == Role::ADMIN && Auth::user()->id == 1){
        return $this->returnData('Admin',"done");
        try{
            $user->delete();
            return $this->returnData("users".$user->name,"DeleteSuccessfuly");}
         catch(Exception $e){
            return $this->returnError('D001',"Some Thing Wrong");
       }}
     else{return $this->rreturnError('403',"Unauthenticated to do that.");} 
 }

     //// todo autocompleteSearch ////
     public function autocompleteSearch(Request $request)
     {
           $query = $request->get('query');
           $filterResult = User::where('name', 'LIKE', '%'. $query. '%')->get();
           return $this->returnData("search",$filterResult);
     } 
     
    
    //// todo Settings ////
    public function setting(Request $request){
     $orders = Orders::where('user_id',auth()->user()->id)->get();
     $favourite = Favourite::where('user_id',auth()->user()->id)->get();
     $numorders = $this->countorders($orders);
     $numfav = $this->countfavourite($favourite);
    return $this->returnData("users",["user"=>auth()->user(),"CountOrders"=>$numorders,"CountFav"=>$numfav]);
    }
 
}
