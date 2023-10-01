<?php

namespace App\Http\Controllers\Api;

use Exception;
use Validator;

use App\Models\Role;
use App\Models\User;
use App\Models\Orders;
use App\Traits\ImgTrait;
use App\Models\Favourite;
use App\Events\OrderVieweer;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Api\FavouriteController;

class OrdersController extends Controller
{
    use ResponseTrait;
    use ImgTrait;
     // should br login first 
   /* public function __construct()
    {
       
            $this->middleware('auth.guard:api');
              
            
    }*/
    
   
    // todo get all orders //
    public function index(Request $request,Orders $order){
        $orders = Orders::select('id','name_'.app()->getLocale().' as name ','view','department_id','user_id')->get();    //selectlange()->get();
        // todo belongs to with model orders //
        foreach ($orders as $belong) {
        $department = $belong->department;
        $user =$belong->user; 
        }
        return $this->returnData('orders',$orders);
    }
     

    // todo add orders //
    public function store(Request $request)
    {       
        $rules = [
            'path'=> 'required|image|mimes:jpg,png,gif|max:2048',
            'price'=> 'required',
            'description'=> 'required',
            'department_id'=> 'required',
            'name_en'=> 'required',
            'name_ar'=> 'required'

        ];       
        // ! valditaion
        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code,$validator);
            }
        if (!isset(auth()->user()->phone)) {
            return  $this -> returnError('', 'Some Thing Wrong check your phone number');
        }

        else{
        $sename = DB::table('orders')->where('name_en', $request->name)
       ->orwhere('name_ar', $request->name)
        ->value('id');  

        if($sename > 0){
            $msg= 'Name Oredy Eists .';
            return $this->returnError('E0011',$msg);
        }
       else{
            $order = Orders::create([
                'name_en'=> $request->name_en,
                'name_ar'=>$request->name_ar,
                'department_id'=> $request->department_id,
                'user_id'=>Auth::user()->id,
                'gmail'=>Auth::user()->gmail,
                'phone'=>Auth::user()->phone,
                'description'=> $request->description,
                'price'=> $request->price,
                'path'=> $request->path,
    
             ]);
                $msg= 'Create successfuly .';
                return $this->returnSuccessMessage($msg);

            }}
        
    }
    public function update(Request $request,User $user)
    {       
        $order = Orders::find($request->id);
       
        if( (auth()->user()->role == Role::ADMIN)||($order->user_id == auth()->user()->id)){
        $rules = [
            'path'=> 'required',
            'price'=> 'required',
            'description'=> 'required',
            'department_id'=> 'required',
            'name_en'=> 'required',
            'name_ar'=> 'required'

        ];       
        // ! valditaion
        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code,$validator);
            }
        if (!isset(auth()->user()->phone)) {
            return  $this -> returnError('', 'Some Thing Wrong check your phone number');
        }
        else{
        $sename = DB::table('orders')->where('name_en', $request->name)
       ->orwhere('name_ar', $request->name)
        ->value('id');  

        if($sename > 0){
            $msg= 'Name Oredy Eists .';
            return $this->returnError('E0011',$msg);
        }
       else{
         $order->update([
            'name_en'=> $request->name_en,
            'name_ar'=>$request->name_ar,
            'department_id'=> $request->department_id,
            'user_id'=>$order->user_id,
            'gmail'=>$order->gmail,
            'phone'=>$order->phone,
            'description'=> $request->description,
            'price'=> $request->price,
            'path'=> $request->path,
         ]);
         $msg = 'Update : '.$order->name_en.' successfuly .';
         return $this->returnSuccessMessage($msg);
        }}}
        else{return $this->returnError("403","UnAuthorization .");}}

    // todo get only one orders by id //
    public function getorders(Request $request,Orders $order){
        $order = Orders::find($request->id);
        $orders = Orders::select('id','name_'.app()->getLocale().' as name ','description','user_id',"department_id",'path','view')->find($request->id);    //selectlange()->get();
        event(new OrderVieweer($order));   
        if(!$orders)
        return $this->returnError('404','Not Found Orders ');
        // todo belongs to with model orders //
        $department = $orders->department;
        $user =$orders->user; 
        return $this->returnData('orders',$orders);
    } 

    // ! Customer Delete My Orders //
    public function destroymyorders(Request $request){
        $order = Orders::find($request->id);
        $orders = Orders::where('id',$request->id)->where('user_id',auth()->user()->id)->value('id');
        $dep_interesteds = Favourite::where('orders_id', $request->id)->where('user_id',auth()->user()->id)->get();
        if(!isset($orders)){
           return  $this -> returnError('', 'Some Thing Wrong');}
        else{$order->delete();}   
        if(isset($dep_interesteds)){
        foreach ($dep_interesteds as $interested) {
            $interested->delete();
       }}
       return $this->returnData("orders",$request->id,"Delete Successfully .");

    }


    // ! Admin Delete All Orders //
    public function destroy(Request $request){
        $orders = Orders::find($request->id);
    //    $dep_orders = Favourite::where('order_id', $request->id)->get()->value('order_id');
        if(!isset($orders)){
            return  $this -> returnError('', 'Some Thing Wrong');
        }  
        $orders->delete();  
        return $this->returnData("orders",$request->id,"Delete Successfully .");
    }

    // todo view restore //
    public function restore_view(Request $request)
    {
        if(auth()->user()->role == Role::ADMIN){
            $orders_restore = Orders::onlyTrashed()->get();
            foreach ($orders_restore as $belong) {
                $department = $belong->department;
                $user =$belong->user; 
                }
            return $this->returnData("all_orders_restore",$orders_restore,"All Orders Restore .");     
        }
        $orders_restore = Orders::where('user_id',auth()->user()->id)->onlyTrashed()->get();
        foreach ($orders_restore as $belong) {
            $department = $belong->department;
            $user =$belong->user; 
            }
        return $this->returnData("your_orders_restore",$orders_restore,"your Orders Restore .");      
    }

    // ! restore orders //
    public function restore(Request $request)
    {
       if(auth()->user()->role != Role::ADMIN){ 
       $order_id = Orders::withTrashed()->where('id',$request->id)->where('user_id',auth()->user()->id)->value('id');
       if(!isset($order_id)){return  $this -> returnError('', 'Some Thing Wrong');}
       else{Orders::withTrashed()->find($request->id)->restore();
    return $this->returnData("orders",$request->id,"Orders Restore successfully .");}
    }
      else{
       $order_id = $request->id;
       Orders::withTrashed()->find($order_id)->restore();
       return $this->returnData("orders",$request->id,"Orders Restore successfully .");  }    
    }

    // todo autocompleteSearch
    public function autocompleteSearch(Request $request)
    {
          $query = $request->get('query');
          $filterResult = Orders::where('name_en', 'LIKE', '%'. $query. '%')
          ->orwhere('name_ar', 'LIKE', '%'. $query. '%')
          ->get();
          foreach ($filterResult as $belong) {
            $department = $belong->department;
            $user =$belong->user; 
            }
          return $this->returnData('search',$filterResult);
    } 
}
