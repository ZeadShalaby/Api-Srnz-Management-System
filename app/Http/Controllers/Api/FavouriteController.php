<?php

namespace App\Http\Controllers\Api;
use Validator;

use App\Models\Orders;
use App\Models\Favourite;
use App\Traits\CountTrait;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class FavouriteController extends Controller
{
    use CountTrait,ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $favourite = Favourite::where('user_id',auth()->user()->id)->get();
        return $this->returnData("Favourite",$favourite);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $order = Orders::find($request->orders_id);
        $rule = Favourite::where('orders_id',$order->id)->where('user_id',auth()->user()->id)->value('id');
        $rules = [
            "orders_id" => "required",
        ];
        // ! valditaion
        $validator = Validator::make($request->all(),$rules);
    
        if($validator->fails()){
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code,$validator);
         }
         if($rule > 0){return $this->returnError("O002","Alredy Aded In Your Favourites .");}
         else{
          $formFields = Favourite::create([
            'user_id' => auth()->user()->id,
            'orders_id' => $order->id
        ]);    
        return $this->returnSuccessMessage("Aded in Your Favourite Successfully .");
    }}

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
   
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
     // ! Delete order im my cards //
     public function destroy(Request $request , Favourite $favourite)
     {
         $user_card = Favourite::find($favourite->id);
         if($user_card->user_id == auth()->user()->id){
            $user_card->delete();
            $msg = "Favourites : ".$favourite->orders_id." , Delete successfully .";
            return $this->returnSuccessMessage($msg);}
         else{return $this->returnError("F001","Some Thing Wrong .");}                
     }
     
     // ! Delete All Orders im my cards //
     public function deleteall(Request $request){
         $user_fav = Favourite::where('user_id',Auth::user()->id)->get();
         foreach($user_fav as $MyFav){
           $MyFav->delete();}
         $msg='Remove All Favourites Successfully .';
         return $this->returnSuccessMessage($msg);
        }
}
