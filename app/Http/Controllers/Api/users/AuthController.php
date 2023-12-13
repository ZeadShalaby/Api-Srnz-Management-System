<?php

namespace App\Http\Controllers\Api\users;

use Auth;
use Exception;
use Validator;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;


class AuthController extends Controller
{
    use ResponseTrait;
  /*  public function __construct()
    {
       $this->middleware('auth.guard:api', ['except' => ['login']]);
    }*/

    // todo Login USers
    public function login(Request $request){

        try{
        $rules = [
            "email" => "required|exists:users,email",
            "password" => "required"

        ];
        // !valditaion
        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code,$validator);
            }

        //! login
        $credentials = $request->only(['email','password']);
        $tocken = Auth::guard('api')->attempt($credentials);
        $users =  Auth::guard('api')->user();
        if(!$tocken)
        return $this->returnError('E001','information not valid.');
        
        $users -> api_tocken = $tocken;

        
        // !return tocken
        return $this->returnData('Users',$users);
        }
        catch(Exception $ex){
            return $this->returnError($ex->getcode(),$ex->getMessage());
        }

    }


    // todo Logout Users
    public function logout(Request $request){
        
        //return $request->header('auth_token'); if i request tocken in header in postman
        // if i sen request in body 
        $token = $request->auth_token; 
        if(isset($token)){
            try{
            //logout
            JWTAuth::setToken($token)->invalidate();
            }catch(TokenInvalidException $e){
                return $this->returnError("T002","Some Thing Went Wrongs");
            }
            catch(TokenExpiredException $e){
                return $this->returnError("T002","Some Thing Went Wrongs");
            }
            return $this->returnSuccessMessage('Logged Out Successfully');
        }
        else{
            return $this->returnError("T001","Some Thing Went Wrongs .");
        }
    }

    // todo Return Profile Information 
    public function profile(){
        return auth()->user();
    }

   


    // todo Register New Customer
    public function register(Request $request,User $user){
        $user = User::find($user->id);
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
          if(Auth::user()->role == Role::CUSTOMER && Auth::user()->id ==$user){    

        User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'gmail'=>$request->gmail,
            'profile_photo'=>'https://via.placeholder.com/400x400.png/004444?text=itaque',
            'phone'=>$request->phone,
            'password'=> $request->password,
            'role'=>Role::CUSTOMER,
            'remember_token' => Str::random(10),
         ]);} 
        else{return $this->returnError("403","Unauthenticated to do that");}
        }
    }


     // todo change image of departments //
     public function changeimg(Request $request)
     {
         //
         $users = Users::find($request->id);
         $rules = [
             "profile_photo" => "required|image|mimes:jpg,png,gif|max:2048"
         ];
         // ! valditaion
         $validator = Validator::make($request->all(),$rules);
 
         if($validator->fails()){
                 $code = $this->returnCodeAccordingToInput($validator);
                 return $this->returnValidationError($code,$validator);
             }
         else{
             $folder = 'images/users';
             $image_name = time().'.'.$request->file->extension();
             $images = $request->file->move(public_path($folder),$image_name) ;
             $path = env("APP_URL").env("Path_user").$image_name;
             $users->update([
                 'profile_photo'  => $path ,
             ]);
         }    
         $msg = "users : ".$users->name." , Update successfully .";
         return $this->returnSuccessMessage($msg);    
     
     }
 
     
     //// todo update users ////
     public function update(Request $request){
        $user=User::find($request->id);
        $rules = [
            "name" => "required",
            "email" => "required",
            "gmail" => "required",
            "phone" => "required",
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
                'name'=> $request->name,
                'email'=> $request->email,
                'gmail'=>$request->gmail,
                'phone'=>$request->phone,
                'password'=> $request->password,
             ]); 
             $msg = "USers : ".$user->name." , Update successfully .";
             return $this->returnSuccessMessage($msg);} 
    }



    
    // todo return users image
    public function imagesuser(Request $request,$user){
        if(isset($user)){
          return $this->returnimageusers($user,$user);}
        else {return 'null';}

    }

    // todo return users image
    public function imagesord(Request $request,$ord){
        if(isset($ord)){
          return $this->returnimageord($ord,$ord);}
        else {return 'null';}

    }

    // todo return users image
    public function imagesdep(Request $request,$dep){
        if(isset($dep)){
          return $this->returnimagedep($dep,$dep);}
        else {return 'null';}
    }
   
}
