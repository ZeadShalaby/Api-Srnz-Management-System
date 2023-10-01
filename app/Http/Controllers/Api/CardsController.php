<?php

namespace App\Http\Controllers\Api;

use Validator;
use Auth;
use Exception;


use App\Models\Cards;
use App\Models\Orders;
use App\Traits\CountTrait;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;

class CardsController extends Controller
{
    use CountTrait,ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // todo return my cards //
        $card = Cards::where('user_id',auth()->user()->id)->get();
        foreach ($card as $belong) {
            $department = $belong->department;
            $user =$belong->user; 
            $orders =$belong->order; 
        }
        return $this->returnData("cards",$card);
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
    // todo add to my cards //
    public function store(Request $request,Cards $card)
    {
      //
    $order = Orders::find($request->orders_id);
    $rule = Cards::where('orders_id',$order->id)->where('user_id',auth()->user()->id)->value('id');
    $rules = [
        "orders_id" => "required",
    ];
    // ! valditaion
    $validator = Validator::make($request->all(),$rules);

    if($validator->fails()){
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code,$validator);
     }
     if($rule > 0){return $this->returnError("O002","Alredy Aded In Your Card .");}
     else{
       $card = Cards::create([
        'orders_id' => $order->id,
        'user_id' => auth()->user()->id,
        'price' => $order->price,
        'department_id' => $order->department_id
    ]);
    return $this->returnSuccessMessage("Aded order Successfully .");
}}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    // ! Delete order im my cards //
    public function destroy(Request $request,Favourite $card)
        {
        $user_card = Cards::find($request->id);
        if($user_card->user_id == auth()->user()->id){
        $card->delete();
        return $this->returnSuccessMessage("Deleted Successfully .");}
        else{return $this->returnError("D001","Some Thing Wrong .");}                        
        }

    // ! Delete All Orders im my cards //
    public function deleteall(Request $request , Cards $card){
        $user_card = Cards::where('user_id',auth()->user()->id)->get();
        foreach($user_card as $MyCard){
          $MyCard->delete();}
        $msg='Remove All Orders in Your Crds Successfully .';
        return $this->returnSuccessMessage($msg);
    }
}

