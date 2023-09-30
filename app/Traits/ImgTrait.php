<?php

namespace App\Traits;

use App\Models\Role;
use App\Models\User;
use App\Models\Orders;
use App\Models\Departments;
use App\Models\Interesteds;

trait ImgTrait

{   // save image 
    protected function saveImage($img,$folder){
    
        //save photo in folder
        $file_extension = $img->getClientOriginalExtension();
        $file_name = time().'.'.$file_extension;
        $path = $folder;
        $img->move($path,$file_name);
      
        return $file_name;
    
     }


     //orders search    
     protected function OrSearch($request){
        $output='';
        $img='';
        $change_user = '';
        $order_search = Orders::where('name', 'LIKE', '%'. $request->info. '%')
        ->get();
        $interesteds = Interesteds::get();
        $ordersuser = Orders::where('user_id',auth()->user()->id)->get();

       
                                    
        if(auth()->user()->role==Role::ADMIN){

            /**   <img class="card-img-top" src="'.$product->path.'" alt="Card image cap"> */
                            foreach($order_search as $product) {
            
                               if(isset($product->user->name)){
                                $product->user->name;}
                                else{"users deleted";}
                               if(isset($product->view)){
                                $img=' <img src="'.url('image\all\view.png').'" alt="vieweer" style="width: 30px"> '.$product->view.'';
                               }else{$img=' <img src="'.url('image\all\nview.png').'" alt="vieweer" style="width: 30px"> ';}
                               $start=1;
                               $orderid=$product->id;
                                  while ($start<=5){
                                     if($orderid>5){ $orderid %=5 ; }                                  
                                     if($orderid<$start){
                                       $stars =' <span class="fa fa-star" style="color: rgb(242, 255, 0)"></span>';}
                                     else{
                                     $stars =' <span class="fa fa-star" style="color: rgb(188, 188, 187)"></span>';}
                                 $start++;}
                                $output .=
                                '<div class="OrderRow'.$product->id.'" >
                                <div class="container" >
                                <div class="box" >
                                <a href="'.route('ordersite.show',$product->id).'" class="inside-page__btn inside-page__btn--beach" >
                                <div class="icon-images" style="margin-top: -30px">

                                <div class="icons" >
                                    <img src="'.asset('image/all/img1.jpg').'" alt="users" />                
                                    <div  class="spann" style="color: aquamarine;margin-left:90px;" >'.$product->user->name.'</div>
                                </div>
                                </div>                                <br>
                   
                   
                                <article class="card" style="text-decoration: none">
                                <img
                                class="card__background"
                                src="'.asset('image/orders/try.png').'"
                                alt="'.$product->name.'"
                                width="1920"
                                height="2193"
                                />
                                <div class="card__content | flow">
                                <div class="card__content--container | flow">
                                    <h2 class="card__title">'.$product->name.'</h2>
                                    <p class="card__description">
                                        '.$product->description.'
                                    </p>
                                    <p>COST :'.$product->price.' <span style="color: rgb(59, 212, 105)">$</span></p>
                                    <span class="fa fa-star" style="color: rgb(188, 188, 187)"></span>
                                    <span class="fa fa-star" style="color: rgb(188, 188, 187)"></span>
                                    <span class="fa fa-star" style="color: rgb(242, 255, 0)"></span>
                                    <span class="fa fa-star" style="color: rgb(242, 255, 0)"></span>

                                     '.$stars.'
                                    
                                </div>
                                    
                                </div>
                            </article>
                   
                   </a>
                     
                               
                           <div class="under_imgs">
                           '.$img.'
                           
                           <div style="margin-top: -18px">
                           <button order_id='.$product->id.' id="delete" class="delete_btn"  style="margin-left: 150px;"><i class="fa fa-trash"></i></button>        
                               </div>
                           <br><br>
                           </div>
                           </div>
                   
                           </div>
                           </div>
           
                               
                                ';
               
   
        
                 
                            }
            
                            return $output;
                        }
                        
    /* ordersite search  */
    if(auth()->user()->role==Role::CUSTOMER){

        /**<img class="card-img-top" src="'.$product->path.'" alt="Card image cap"> */
        foreach($order_search as $product) {
            
            if(isset($product->user->name)){
                $product->user->name;}
                else{"users deleted";}
               if(isset($product->view)){
                $img=' <img src="'.url('image\all\view.png').'" alt="vieweer" > '.$product->view.'';
               }else{$img=' <img src="'.url('image\all\nview.png').'" alt="vieweer" > ';}
              
               if(isset($sefav)){ }
               else{
               $favgold = '<div class="AddFav"><button  orders_id = '.$product->id.' name="favourite" class="AddFav btn btn-lg" ><i class="fa fa-heart" id ="fav'.$product->id.'" style="color: gold; width:50px;" ></i></button></div>'; 
               
               if(isset($interesteds)){
               foreach ($interesteds as $interested){
               if(($interested->user_id==auth()->user()->id)&($interested->order_id==$product->id)){
               $favgold = '<div id="favred">
               <button orders_id = '.$product->id.'  name="favourite" class="AddFav btn btn-lg" ><i class="fa fa-heart" style="color: red;width:50px;" ></i></button> 
               </div>';
               }
               }}
               $btnedit = '';
               $btndelete = '';
               if(isset($ordersuser)){
                foreach($ordersuser as $oruser){
                 if($oruser->id==$product->id){
                $btnedit =  '<div class="btnedits"><a href="'.route('ordersite.edit', $product->id).'"class="btn btn-info" style="margin-left: 200px;margin-top: -150px;"><i class="fa fa-edit"></i> </a></div';
                $btndelete = '<div class="btndeletes"><button order_id='.$product->id.' class="delete_btn btn btn-danger"  style="margin-top: -200px;margin-left: 400px;"><i class="fa fa-trash"></i></button>';
               }}}}
               $start=1;
               $orderid=$product->id;
                 while ($start<=5){
                     if($orderid>5){ $orderid %=5 ; }                                  
                     if($orderid<$start){
                       $stars =' <span class="fa fa-star" style="color: rgb(242, 255, 0)"></span>';}
                     else{
                     $stars =' <span class="fa fa-star" style="color: rgb(188, 188, 187)"></span>';}
                 $start++;}
                $output .=
                '<div class="OrderRow'.$product->id.'">
                <div class="container">
                <div class="box">
                <a href="'.route('ordersite.show',$product->id).'" class="inside-page__btn inside-page__btn--beach">
                <span style="color: aquamarine" >'.$product->user->name.'</span>
                <br><br>
   
   
                <article class="card">
                <img
                class="card__background"
                src="'.asset('image/orders/try.png').'"
                alt="'.$product->name.'"
                width="1920"
                height="2193"
                />
                <div class="card__content | flow">
                <div class="card__content--container | flow">
                    <h2 class="card__title">'.$product->name.'</h2>
                    <p class="card__description">
                        '.$product->description.'
                    </p>
                    <p>COST :'.$product->price.' <span style="color: rgb(59, 212, 105)">$</span></p>
                    <span class="fa fa-star" style="color: rgb(188, 188, 187)"></span>
                    <span class="fa fa-star" style="color: rgb(188, 188, 187)"></span>
                    <span class="fa fa-star" style="color: rgb(242, 255, 0)"></span>
                    <span class="fa fa-star" style="color: rgb(242, 255, 0)"></span>

                     '.$stars.'
                </div>
                    
                </div>
            </article>
   
   </a>
     

   
            <div class="view_img">
            '.$img.'
            <div class="under_img">
            '.$favgold.'
            </div>
            '.$btnedit.'
            '.$btndelete.'
            </div>
            </div>
            </div>
            </div>
            </div>
   
   
                ';

  


        
         }

         return $output;   
         
    }

    }


   // search users get response
   protected function UserSearch($request){


        $output='';
        
        $user_search = User::where('name', 'LIKE', '%'. $request->info. '%')->where('name','!=','Admin')
        ->orwhere('email', 'LIKE', '%'. $request->info. '%')->where('name','!=','Admin')
        ->orwhere('phone', 'LIKE', '%'. $request->info. '%')->where('name','!=','Admin')
        ->get();
 
        foreach ($user_search as $product) {
                 
            if($product->role == Role::CUSTOMER) 
            $gender ='Customer';
            else
            $gender = 'Admin';

           
           $output .=
           '<div class="UserRow'.$product->id.'">
            <div class="container">
            <div class="box">
            <div class="card">
            <div class="content">
            <div class="back">
            <div class="back-content">
            <svg stroke="#ffffff"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" height="50px" width="50px"
                            fill="#ffffff">

                                <g stroke-width="0" id="SVGRepo_bgCarrier"></g>

                                <g stroke-linejoin="round" stroke-linecap="round" id="SVGRepo_tracerCarrier"></g>


                                <g id="SVGRepo_iconCarrier">
                                    
                            <path d="M20.84375 0.03125C20.191406 0.0703125 19.652344 0.425781 19.21875 1.53125C18.988281 2.117188 18.5 3.558594 18.03125 4.9375C17.792969 5.636719 17.570313 6.273438 17.40625 6.75C17.390625 6.796875 17.414063 6.855469 17.40625 6.90625C17.398438 6.925781 17.351563 6.949219 17.34375 6.96875L17.25 7.25C18.566406 7.65625 19.539063 8.058594 19.625 8.09375C22.597656 9.21875 28.351563 11.847656 33.28125 16.78125C38.5 22 41.183594 28.265625 42.09375 30.71875C42.113281 30.761719 42.375 31.535156 42.75 32.84375C42.757813 32.839844 42.777344 32.847656 42.78125 32.84375C43.34375 32.664063 44.953125 32.09375 46.3125 31.625C47.109375 31.351563 47.808594 31.117188 48.15625 31C49.003906 30.714844 49.542969 30.292969 49.8125 29.6875C50.074219 29.109375 50.066406 28.429688 49.75 27.6875C49.605469 27.347656 49.441406 26.917969 49.25 26.4375C47.878906 23.007813 45.007813 15.882813 39.59375 10.46875C33.613281 4.484375 25.792969 1.210938 22.125 0.21875C21.648438 0.0898438 21.234375 0.0078125 20.84375 0.03125 Z M 16.46875 9.09375L0.0625 48.625C-0.09375 48.996094 -0.00390625 49.433594 0.28125 49.71875C0.472656 49.910156 0.738281 50 1 50C1.128906 50 1.253906 49.988281 1.375 49.9375L40.90625 33.59375C40.523438 32.242188 40.222656 31.449219 40.21875 31.4375C39.351563 29.089844 36.816406 23.128906 31.875 18.1875C27.035156 13.34375 21.167969 10.804688 18.875 9.9375C18.84375 9.925781 17.8125 9.5 16.46875 9.09375 Z M 17 16C19.761719 16 22 18.238281 22 21C22 23.761719 19.761719 26 17 26C15.140625 26 13.550781 24.972656 12.6875 23.46875L15.6875 16.1875C16.101563 16.074219 16.550781 16 17 16 Z M 31 22C32.65625 22 34 23.34375 34 25C34 25.917969 33.585938 26.730469 32.9375 27.28125L32.90625 27.28125C33.570313 27.996094 34 28.949219 34 30C34 32.210938 32.210938 34 30 34C27.789063 34 26 32.210938 26 30C26 28.359375 26.996094 26.960938 28.40625 26.34375L28.3125 26.3125C28.117188 25.917969 28 25.472656 28 25C28 23.34375 29.34375 22 31 22 Z M 21 32C23.210938 32 25 33.789063 25 36C25 36.855469 24.710938 37.660156 24.25 38.3125L20.3125 39.9375C18.429688 39.609375 17 37.976563 17 36C17 33.789063 18.789063 32 21 32 Z M 9 34C10.65625 34 12 35.34375 12 37C12 38.65625 10.65625 40 9 40C7.902344 40 6.960938 39.414063 6.4375 38.53125L8.25 34.09375C8.488281 34.03125 8.742188 34 9 34Z"></path>

                                    
                                </g>

            </svg>
            <strong>'.$product->name.'</strong>
            </div>
            </div>
            <div class="front">  
            <div class="img">
              <div class="circle">
              </div>
              <div class="circle" id="right">
              </div>
              <div class="circle" id="bottom">
              </div>
            </div>
          
            <div class="front-content">
                <small class="badge"> 
                '.$gender.'
                </small>
                <small class="edit"> <a href="'.route('users.edit',$product->id).'"> <i class="fa fa-edit"></i> </a></small>
                <small class="delete"><button user_id='.$product->id.' class="delete_btn" id="delete_users"><i class="fa fa-trash"></i></button></small>
                <a href="'.route('users.show', $product->id).'">

                <div class="description">
                <div class="title">
                  <p class="title">
                <img class="user_profile" src="'.asset('image/all/try.png').'" alt="User_photo"/>
                </p>
                <svg fill-rule="nonzero" height="15px" width="15px" viewBox="0,0,256,256" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg"><g style="mix-blend-mode: normal" text-anchor="none" font-size="none" font-weight="none" font-family="none" stroke-dashoffset="0" stroke-dasharray="" stroke-miterlimit="10" stroke-linejoin="miter" stroke-linecap="butt" stroke-width="1" stroke="none" fill-rule="nonzero" fill="#20c997"><g transform="scale(8,8)"><path d="M25,27l-9,-6.75l-9,6.75v-23h18z"></path></g></g></svg>
              </div>

              <p class="card-footer">
                                    
              '.$product->name.'
                  <br>
                 '.$product->phone.'
                 </p>
                 </div>
               </a>
            </div>
        </div>
    </div>
</div>
<div>                   
</div>
</div>
</div>
 

       
        ';

        
 
        }
        

        return $output;           

   }
   
   

   // search department get response
   protected function DepSearch($request){
    $output = '';
   
    $dep_search = Departments::where('name', 'LIKE', '%'. $request->info. '%')
        ->orwhere('code', 'LIKE', '%'. $request->info. '%')
        ->get();
      
        foreach ($dep_search as $product) {
            if(isset($product->img)){
                $dep_img = '<img src="'.asset('image/departments/'.$product->img).'" alt="departments">';
               }else{$dep_img=' <img src="'.url('image\all\course.svg').'" alt="departments" > ';}
              
        
            if($product->id % 2 == 0 ){
            $icon_dep = ' <img src="'.asset('image/all/dep.png').'" alt="departments">';}
                else{             
                $icon_dep = ' <img src="'.asset('image/all/logo.png').'" alt="departments">';}
        
             $output.='
             
             <div class="DepartmentRow'.$product->id.'">
             <div class="container">
             <div class="box">
             <div class="item">
             <div class="item__image">
             <div class="image-switch__outer">
             <div class="image-switch__inner">
             '.$dep_img.'
             </div>
             </div>
             </div>
             <div class="item__description">
             <div class="description-switch__outer">
             <div class="description-switch__inner">
              
             <button department_id='.$product->id.' class="delete_btn btn btn-danger" ><i class="fa fa-trash" id="delete"></i></button>
             <a href="'.route('departments.edit', $product->id).'" class="edit_btn -info"> <i class="fa fa-edit" id="edit"></i> </a>
             

                <p>'.$product->name.'</p>
               
               <a href="'.route('departments.show',$product->id).'" ><!--target="_blank"   open link in new page    -->  
                 <i class="fas fa-location-arrow" id="show"></i>
               <div class="item__action-arrow">
               </a>

                    </div>
                </div>
                </div>
            </div>

  
            <div class="flap level0">
                
    
            <div class="flap level1 flip-right">
                
              <div class="flap level2 flip-down">
                <div class="flap level3 flip-left"></div>
                <div class="flap level3 flip-right">
                  <div class="flap level4 flip-up">
                    <div class="flap level5 flip-right">
                      <div class="flap level6 flip-left">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="flap level2 flip-up">
                <div class="flap level3 flip-left">
                  <div class="flap level4 flip-up"></div>
                  <div class="flap level5 flip-down">
                    <div class="flap level6 flip-left">
                      <div class="flap level7 flip-up">
                        <div class="flap level8 flip-left"></div>
                        <div class="flap level8 flip-right"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="flap level1 flip-left">
              <div class="flap level2 flip-up">
                <div class="flap level3 flip-left">
                  <div class="flap level4 flip-down">
                    <div class="flap level5 flip-left">
                      <div class="flap level6 flip-right">
                        <div class="flap level7 flip-up">
                          <div class="flap level8--alt flip-right"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="flap level2 flip-down">
                <div class="flap level3 flip-right">
                  <div class="flap level4 flip-down">
                    <div class="flap level5 flip-up"></div>
                  </div>
                  <div class="flap level5 flip-up">                                
                    <div class="flap level6 flip-right"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="item__hover-icon">
            <div class="icon-switch__outer">
              <div class="icon-switch__inner">
               '.$icon_dep.'
              <div class="code">
              <span >'.$product->code.'</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
 </div>
</div>
             
             ';
        
        }
   
   return $output;
   }


                  
                  
                  
               
                    
             
                   

}

