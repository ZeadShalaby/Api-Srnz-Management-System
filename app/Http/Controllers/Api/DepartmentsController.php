<?php

namespace App\Http\Controllers\Api;

use Auth;
use Exception;
use Validator;

use App\Models\Departments;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;


class DepartmentsController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $departments=Departments::get();
        return $this->returnData('departments',$departments);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * todo Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $rules = [
            "name" => "required|unique:departments,name|string|min:4",
            "code" => "required|unique:departments,code|string|min:4",
            "img" => "required"
        ];
        // ! valditaion
        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code,$validator);
            }
        
        else {
            $department = Departments::create([
                'name' => $request->name,
                'code' => $request->code,
                'img'  => $request->img ,
                 ]);
        }    

        return $this->returnSuccessMessage("Create Departments Successfully .");

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,Departments $department)
    {
        // ! this
        $department = Departments::find($department);
        //! or this
        $departments = Departments::find($request->id);
       
        return  $this->returnData('Departments',$department);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * todo Update the specified resource in storage.
     */
    public function update(Request $request,Departments $department)
    {
        //
        $rules = [
            "name" => "required",
            "code" => "required",
            "img" => "required"
        ];
        // ! valditaion
        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code,$validator);
            }
        else{
            $department->update([
                'name' => $request->name,
                'code' => $request->code,
                'img'  => $request->img ,
                 ]);
        }    
        $msg = "Departments : ".$department->name." , Update successfully .";
        return $this->returnSuccessMessage($msg);      }

    /**
     * ! Remove the specified resource from storage.
     */
    public function destroy(Request $request,Departments $department)
    {
        // ! this
        $department = Departments::find($department->id);
        if(!isset($department)){return  $this ->returnError('', 'Some Thing Wrong');}
        else{$department->delete();}
        // ! or this
        $departments = Departments::find($request->id);
        $department->delete();

        $msg = "Departments : ".$department->name." Delete successfully .";
        return $this->returnSuccessMessage($msg);       
     
    }

    // todo view restore
    public function restore_view()
    {
        $dep_restore = Departments::onlyTrashed()->get();
        return $this->returnData('Dep_Restore',$dep_restore);    
    }
    
    // todo restore ->restore();
    public function restore(Request $request)
    {
       $dep_restore = Departments::withTrashed()->find($request->id);
       $dep = Departments::onlyTrashed()->where('id',$request->id)->value('id');
       if($dep < 0){return  $this ->returnError('', 'Some Thing Wrong');}
       else{$dep_restore->restore();
       $msg = "Departments".$dep_restore->name."Restore successfully .";
       return $this->returnSuccessMessage($dep_restore);}
    }

     // todo autocompleteSearch
     public function autocompleteSearch(Request $request)
     {
           $query = $request->get('query');
           $filterResult = Departments::where('name', 'LIKE', '%'. $query. '%')
           ->orwhere('code', 'LIKE', '%'. $query. '%')
           ->get();
            return $this->returnData('search',$filterResult);
        } 
     
     // todo search_departments
     public function search_departments (Request $request)
      {
        return $this->returnData;
         $output = $this->DepSearch($request);
         
         return response($output);
      }

}
