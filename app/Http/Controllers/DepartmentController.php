<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Department;


class DepartmentController extends Controller
{
    public function viewDepartments(){
    	$departments = Department::get();
    	$departments = json_decode(json_encode($departments));
    	return view('admin.departments.view_departments')->with(compact('departments'));
    }

    public function addDepartment(Request $request){
    	if($request->isMethod('post')){
    		$data = $request->all();
    		//echo "<pre>"; print_r($data); die;
    		$department = new Department;
    		$department->department_name = $data['department_name'];
    		$department->status = 1;
    		$department->save();
    		return redirect('/admin/view-departments')->with('flash_message_success','Department added Successfully');
    	}
        //$levels = Category::where(['parent_id'=>0])->get();
    	//return view('admin.persons.add_person')->with(compact('levels'));
    	return view('admin.departments.add_department');
    }
    public function editDepartment(Request $request, $id = null){
    	if($request->isMethod('post')){
    		$data = $request->all();
    		//echo "<pre>"; print_r($data); die;
    		Department::where(['id'=>$id])->update(['department_name'=>$data['department_name']]);
    		return redirect('/admin/view-departments')->with('flash_message_success','Department updated Successfully');
    	}
    	$departmentDetails = Department::where(['id'=>$id])->first();
    	return view('admin.departments.edit_department')->with(compact('departmentDetails'));
    }
    public function deleteDepartment(Request $request, $id = null){
    	if(!empty($id)){
    		Department::where(['id'=>$id])->delete();
    		return redirect('/admin/view-departments')->with('flash_message_success','Department deleted Successfully');
    	}
    	$departmentDetails = Department::where(['id'=>$id])->first();
    	return view('admin.departments.edit_department')->with(compact('departmentDetails'));
    }
    public function setActive(Request $request, $id = null, $status = null){
        if(!empty($id) AND !empty($status)){
            if($status==1){
                Department::where(['id'=>$id])->update(['status' => 2]);
            }else{
                Department::where(['id'=>$id])->update(['status' => 1]);
            }

            return redirect('/admin/view-departments')->with('flash_message_success','ปรับปรุงข้อมูลสำเร็จ');
        }
        return redirect('/admin/view-departments');
    }
}
