<?php

namespace App\Http\Controllers;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function save(Request $request)
    {
        $request->validate([
            'name' => "required",
            'mobile'=>'min:10|max:10',
            'designation_id' => "required"
        ]);

        DB::beginTransaction();
        try{

            if($request->id)
            $employee = Employee::find($request->id);
            else
            $employee = new Employee;


            $employee->name = strtoupper($request->name);
            $employee->mobile = $request->mobile;
            $employee->designation_id = $request->designation_id;
            $employee->save();
            DB::commit();

            return 'success';

        } catch(\Exception $e) {
            DB::rollBack();
            return 'failed';

        }
    }
    public function getEmployees()
    {

        $employees = Employee::with('designation')->orderBy('name','ASC');
        return  $employees->get();

    }

    public function getJobAdvisor()
    {

        $employees = Employee::where('designation_id','=',1)->orderBy('name','ASC');
        return  $employees->get();

    }

    public function getTechnician()
    {

        $employees = Employee::where('designation_id','=',2)->orderBy('name','ASC');
        return  $employees->get();

    }


    public function deleteEmployee(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        DB::beginTransaction();
        try{



        $wmployee = Employee::find($request->id);
        $wmployee->delete();

        return 'success';

    } catch(\Exception $e) {
        DB::rollBack();
        return 'failed';

    }
    }

}
