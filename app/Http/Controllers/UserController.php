<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\PositionRank;
use App\Department;
use App\Section;

use App\User;
//$2y$10$tfS2qHGGz//btX7Bj99dDOMgAF1BN/wireydxSL2pO2LwOqK4wPKe
// p@ssw0rd = $2y$10$Z16BxHrkyoyAFQO3sXPrXOLKH2D5Fc8rcjB93yTOdN7KvbetynlP2
// ak2019 = $2y$10$I1XkJNYWBzTd9tmxQEmq..mGQU/JY4caGjUZVWHfGX48Jffa7kB6y
class UserController extends Controller
{
    public function viewUsers(Request $request){
        
        
        
        //echo "<pre>"; print_r($employees->toSql()); die;
        $users = User::get();
        $users = json_decode(json_encode($users));          
        return view('admin.users.view_users')->with(compact('users'));
    }
    public function viewEvaluators(Request $request){
        $positionRanks = PositionRank::get();
        $positionRanks = json_decode(json_encode($positionRanks));
        $departments = Department::get();
        $departments = json_decode(json_encode($departments));
        $sections = Section::get();
        $sections = json_decode(json_encode($sections));

        $section_id = "";
        $department_id = "";


        $employees = Employee::select('employees.id','employees.person_code','employees.image','employees.person_full_name','employees.position_name','employees.position_rank_id','employees.section_id','employees.department_id'
            ,'position_ranks.position_rank_name'
            ,'et.person_full_name as evaluator_full_name'
            ,'et2.person_full_name as evaluator_2_full_name' 
        )->leftjoin('position_ranks','position_ranks.id','=','employees.position_rank_id')
            ->leftjoin('employees as et','et.id','=','employees.evaluator_id')
            ->leftjoin('employees as et2','et2.id','=','employees.evaluator_2_id');
        if($request->isMethod('get')){
            if ($request->has('section_id') && $request->section_id != NULL) {
                $section_id = $request->section_id;
                $employees->where('employees.section_id','=',$request->section_id);
            }
            if ($request->has('department_id') && $request->department_id != NULL) {
                $department_id = $request->department_id;
                $employees->where('employees.department_id','=',$request->department_id);
            }
        }
        
        
        
        //echo "<pre>"; print_r($employees->toSql()); die;
        $employees = $employees->get();
        $employees = json_decode(json_encode($employees));          
        return view('employees.view_evaluators')->with(compact('positionRanks','departments','sections','employees','section_id','department_id'));
    }
    public function addEmployee(Request $request){
    	$positionRanks = PositionRank::get();
        $positionRanks = json_decode(json_encode($positionRanks));
        $departments = Department::get();
        $departments = json_decode(json_encode($departments));
        $sections = Section::get();
        $sections = json_decode(json_encode($sections));

        if($request->isMethod('post')){
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            $employee = new Employee;
            $employee->person_code = $data['person_code'];
            $employee->date_of_work = now(); //$data['date_of_work'];
            $employee->person_title = $data['person_title'];
            $employee->person_name = $data['person_name'];
            $employee->person_surname = $data['person_surname'];
            $employee->person_full_name = $data['person_title'].' '.$data['person_name'].'  '.$data['person_surname'];
            $employee->person_title_en = $data['person_title_en'];
            $employee->person_name_en = $data['person_name_en'];
            $employee->person_surname_en = $data['person_surname_en'];
            $employee->person_full_name_en = $data['person_title_en'].' '.$data['person_name_en'].'  '.$data['person_surname_en'];
            $employee->position_name = $data['position_name'];
            $employee->position_rank_id = $data['position_rank_id'];
            $employee->section_id = $data['section_id'];
            $employee->department_id = $data['department_id'];
            $employee->status = 1;
            //$category->description = $data['description'];
            //$category->url = $data['url'];

            if($request->hasFile('personImage')){
            	$image_tmp = Input::file('personImage');
            	if($image_tmp->isValid()){
            		$extension = $image_tmp->getClientoriginalExtension();
            		$filename = $employee->person_code.'_'.rand(11111,99999).'.'.$extension;
            		$large_image_path = 'assets/images/employees/large/'.$filename;
            		$medium_image_path = 'assets/images/employees/medium/'.$filename;
            		$small_image_path = 'assets/images/employees/small/'.$filename;
            		//Resize Images
            		Image::make($image_tmp)->save($large_image_path);
            		Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
            		Image::make($image_tmp)->resize(300,300)->save($small_image_path);

            		// Strore image name in employees table
            		$employee->image = $filename;
            	}
            	//die;            	
            }
            $employee->save();

            return redirect('/employees/view-employees')->with('flash_message_success','Employee added Successfully');
        }
        //$levels = Category::where(['parent_id'=>0])->get();
        //return view('admin.persons.add_person')->with(compact('levels'));
        return view('employees.add_employee')->with(compact('positionRanks','departments','sections'));
    }
    public function editEmployee(Request $request, $id = null){
        if($request->isMethod('post')){
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            Employee::where(['id'=>$id])->update(['person_name'=>$data['person_name']]);
            return redirect('/employees/view-employees')->with('flash_message_success','Employee updated Successfully');
        }
        $positionRankDetails = PositionRank::where(['id'=>$id])->first();
        return view('employees.edit_employee')->with(compact('positionRankDetails'));


        $positionRanks = PositionRank::get();
        $positionRanks = json_decode(json_encode($positionRanks));
        $departments = Department::get();
        $departments = json_decode(json_encode($departments));
        $sections = Section::get();
        $sections = json_decode(json_encode($sections));

        if($request->isMethod('post')){
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            $employee = new Employee;
            $employee->person_code = $data['person_code'];
            $employee->date_of_work = now(); //$data['date_of_work'];
            $employee->person_title = $data['person_title'];
            $employee->person_name = $data['person_name'];
            $employee->person_surname = $data['person_surname'];
            $employee->person_full_name = $data['person_title'].' '.$data['person_name'].'  '.$data['person_surname'];
            $employee->person_title_en = $data['person_title_en'];
            $employee->person_name_en = $data['person_name_en'];
            $employee->person_surname_en = $data['person_surname_en'];
            $employee->person_full_name_en = $data['person_title_en'].' '.$data['person_name_en'].'  '.$data['person_surname_en'];
            $employee->position_name = $data['position_name'];
            $employee->position_rank_id = $data['position_rank_id'];
            $employee->section_id = $data['section_id'];
            $employee->department_id = $data['department_id'];
            $employee->status = 1;
            //$category->description = $data['description'];
            //$category->url = $data['url'];

            if($request->hasFile('personImage')){
            	$image_tmp = Input::file('personImage');
            	if($image_tmp->isValid()){
            		$extension = $image_tmp->getClientoriginalExtension();
            		$filename = $employee->person_code.'_'.rand(11111,99999).'.'.$extension;
            		$large_image_path = 'assets/images/employees/large/'.$filename;
            		$medium_image_path = 'assets/images/employees/medium/'.$filename;
            		$small_image_path = 'assets/images/employees/small/'.$filename;
            		//Resize Images
            		Image::make($image_tmp)->save($large_image_path);
            		Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
            		Image::make($image_tmp)->resize(300,300)->save($small_image_path);

            		// Strore image name in employees table
            		$employee->image = $filename;
            	}
            	//die;            	
            }
            $employee->save();

            return redirect('/employees/view-employees')->with('flash_message_success','Employee added Successfully');
        }
        $employee = Employee::where(['id'=>$id])->get();
        $employee = json_decode(json_encode($employee));
        return view('employees.edit_employee')->with(compact('positionRanks','departments','sections','employee'));
    }
    public function deleteEmployee(Request $request, $id = null){
        if(!empty($id)){
            Employee::where(['id'=>$id])->delete();
            return redirect('/employees/view-employees')->with('flash_message_success','Employee deleted Successfully');
        }
        $personDetails = Employee::where(['id'=>$id])->first();
        return view('employees.view_employees')->with(compact('positionRankDetails'));
    }
}
