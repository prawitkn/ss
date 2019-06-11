<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Auth;
use Session;
use Image;

use App\GradingGroup;
use App\PositionRank;
use App\Department;
use App\Section;
use App\Employee;
use Response;
use DB;

class EmployeeController extends Controller
{
    public function viewEmployees(Request $request){
        $positionRanks = PositionRank::get();
        $positionRanks = json_decode(json_encode($positionRanks));
        $departments = Department::get();
        $departments = json_decode(json_encode($departments));
        $sections = Section::get();
        $sections = json_decode(json_encode($sections));

        $section_id = "";
        $department_id = "";


        $employees = Employee::select('employees.id','employees.person_code','employees.image','employees.person_full_name','employees.position_name','employees.position_rank_id','employees.section_id','employees.department_id','employees.status'
            ,'position_ranks.position_rank_name'
            ,'sections.section_name'
            ,'departments.department_name'
            ,'employees.evaluator1_id','employees.evaluator2_id'
            ,'evt1.person_full_name as evaluator1_full_name', 'evt2.person_full_name as evaluator_2_full_name'
        )->leftjoin('position_ranks','position_ranks.id','=','employees.position_rank_id')
            ->leftjoin('sections','sections.id','=','employees.section_id')
            ->leftjoin('departments','departments.id','=','employees.department_id')
            ->leftjoin('employees as evt1','evt1.id','=','employees.evaluator1_id')
            ->leftjoin('employees as evt2','evt2.id','=','employees.evaluator2_id');
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
        return view('employees.view_employees')->with(compact('positionRanks','departments','sections','employees','section_id','department_id'));
    }
    public function viewEvaluators(Request $request){
        $gradingGroups = GradingGroup::get();
        $gradingGroups = json_decode(json_encode($gradingGroups));
        $positionRanks = PositionRank::get();
        $positionRanks = json_decode(json_encode($positionRanks));
        $departments = Department::get();
        $departments = json_decode(json_encode($departments));
        $sections = Section::get();
        $sections = json_decode(json_encode($sections));

        $grading_group_id = null;
        $position_rank_id = null;
        $department_id = null;
        $section_id = null;


        $employees = Employee::select('employees.id','employees.person_code','employees.image','employees.person_full_name','employees.position_name','employees.grading_group_id','employees.position_rank_id','employees.section_id','employees.department_id'
            ,'position_ranks.position_rank_name'
            ,'employees.evaluator1_id','employees.evaluator2_id'
            ,'et.person_full_name as evaluator1_full_name'
            ,'et2.person_full_name as evaluator2_full_name' 
        )->leftjoin('position_ranks','position_ranks.id','=','employees.position_rank_id')
            ->leftjoin('employees as et','et.id','=','employees.evaluator1_id')
            ->leftjoin('employees as et2','et2.id','=','employees.evaluator2_id')
        ->where('employees.status','=',1);
        if($request->isMethod('get')){
            if ($request->has('grading_group_id') && $request->grading_group_id != NULL) {
                $grading_group_id = $request->grading_group_id;
                $employees->where('employees.grading_group_id','=',$request->grading_group_id);
            }
            if ($request->has('position_rank_id') && $request->position_rank_id != NULL) {
                $position_rank_id = $request->position_rank_id;
                $employees->where('employees.position_rank_id','=',$request->position_rank_id);
            }
            if ($request->has('department_id') && $request->department_id != NULL) {
                $department_id = $request->department_id;
                $employees->where('employees.department_id','=',$request->department_id);
            }
            if ($request->has('section_id') && $request->section_id != NULL) {
                $section_id = $request->section_id;
                $employees->where('employees.section_id','=',$request->section_id);
            }
        }
        
        //echo "<pre>"; print_r($employees->toSql()); die;
        $employees = $employees->get();
        $employees = json_decode(json_encode($employees));          
        return view('employees.view_evaluators')->with(compact('gradingGroups','positionRanks','departments','sections','employees','grading_group_id','position_rank_id','department_id','section_id'));
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

            $check = Employee::where('person_code','=',$employee->person_code)->first();
            if($check->count()>0){
                // return redirect('/employees/edit-employee/'.$employee->id)->with('flash_message_error','หมายเลขพนักงานซ้ำ');
                return redirect()->route('editEmployee', ['id' => $check->id])->with('flash_message_error','หมายเลขพนักงานซ้ำ');
            }

            if($request->hasFile('upload_images')){
            	$image_tmp = Input::file('upload_images');
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

        //echo "<pre>"; print_r(Storage::disk('local')); die;
        $positionRanks = PositionRank::get();
        $positionRanks = json_decode(json_encode($positionRanks));
        $departments = Department::get();
        $departments = json_decode(json_encode($departments));
        $sections = Section::get();
        $sections = json_decode(json_encode($sections));

        if($request->isMethod('post')){
            $data = $request->all();
            $url = $data['url'];
            //echo "<pre>"; print_r($data); die;
            $employee =  Employee::find($id);
            $employee->person_code = $data['person_code'];
            $employee->date_of_work = $data['date_of_work'];
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

            if($request->hasFile('upload_images')){
            	$image_tmp = Input::file('upload_images');
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
                    $oldImage = $employee->image;
            		$employee->image = $filename;

                    // Remove
                    if(file_exists('assets/images/employees/large/'.$oldImage) AND !empty($oldImage)){ 
                        unlink('assets/images/employees/large/'.$oldImage);
                     } 
                     if(file_exists('assets/images/employees/medium/'.$oldImage) AND !empty($oldImage)){ 
                        unlink('assets/images/employees/medium/'.$oldImage);
                     } 
                     if(file_exists('assets/images/employees/small/'.$oldImage) AND !empty($oldImage)){ 
                        unlink('assets/images/employees/small/'.$oldImage);
                     } 
            	}
            	//die;            	
            }
            $employee->save(); 

            return redirect($url)->with('flash_message_success', 'แก้ไขข้อมูลสำเร็จ');
            // return redirect('/employees/view-employees')->with('flash_message_success','Employee updated Successfully');
            // return redirect()->route('editEmployee', ['id' => $employee->id])->with('flash_message_success','Employee updated Successfully');
        }
        $employee = Employee::where(['id'=>$id])->get()->first();
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
    public function setActiveEmployee(Request $request, $id = null, $status = null){
        if(!empty($id) AND !empty($status)){
            if($status==1){
                Employee::where(['id'=>$id])->update(['status' => 2]);
            }else{
                Employee::where(['id'=>$id])->update(['status' => 1]);
            }

            return redirect('/employees/view-employees')->with('flash_message_success','Employee updated Successfully');
        }
        $personDetails = Employee::where(['id'=>$id])->first();
        return view('employees.view_employees')->with(compact('positionRankDetails'));
    }

    public function listEmployees(Request $request){
        $searchWord =  "";

        $employees = Employee::select('employees.id','employees.person_code','employees.image','employees.person_full_name','employees.position_name','employees.position_rank_id','employees.section_id','employees.department_id'
            ,'position_ranks.position_rank_name'
            ,'sections.section_name'
            ,'departments.department_name'
        )->leftjoin('position_ranks','position_ranks.id','=','employees.position_rank_id')
            ->leftjoin('sections','sections.id','=','employees.section_id')
            ->leftjoin('departments','departments.id','=','employees.department_id');

        $employees->where('employees.status','=',1);

        if($request->isMethod('get')){
            if ($request->has('searchWord') && $request->searchWord != NULL) {
                $searchWord = $request->searchWord;
                $employees->where('employees.person_full_name', 'like', '%' . $searchWord . '%');
            }
        }        
    
        $employees = $employees->get();

        $rowCount = $employees->count();
        $jsonData = array();
        $jsonData = json_decode(json_encode($employees), true);                    
        echo json_encode(array('success' => 
            'success', 'rowCount' => $rowCount, 'data' => json_encode($jsonData)));
    }
    public function listEvaluators(Request $request){
        $searchWord =  "";
        $position_rank_id = $request->position_rank_id;

        $employees = Employee::select('employees.id','employees.person_code','employees.image','employees.person_full_name','employees.position_name','employees.position_rank_id','employees.section_id','employees.department_id'
            ,'position_ranks.position_rank_name'
            ,'sections.section_name'
            ,'departments.department_name'
        )->leftjoin('position_ranks','position_ranks.id','=','employees.position_rank_id')
            ->leftjoin('sections','sections.id','=','employees.section_id')
            ->leftjoin('departments','departments.id','=','employees.department_id');

        $employees->where('employees.status','=',1);
//echo "<pre>"; print_r($employee); die;
        if(!empty($position_rank_id)){
            $employees->whereBetween('employees.position_rank_id', array($position_rank_id-2, $position_rank_id-1));
        }else{
            $employees->where('employees.position_rank_id','<=',10);
        }
      

        if($request->isMethod('get')){
            if ($request->has('searchWord') && $request->searchWord != NULL) {
                $searchWord = $request->searchWord;
                $employees->where('employees.person_full_name', 'like', '%' . $searchWord . '%');
            }
        }        
        //echo "<pre>"; print_r($employees->toSql()); die;
        $employees = $employees->get();
        //echo "<pre>"; print_r($employees->toSql()); die;
        $rowCount = $employees->count();
        $jsonData = array();
        $jsonData = json_decode(json_encode($employees), true);                    
        echo json_encode(array('success' => 
            'success', 'rowCount' => $rowCount, 'data' => json_encode($jsonData)));
    }
    public function editEvaluators(Request $request){
        $gradingGroups = GradingGroup::get();
        $gradingGroups = json_decode(json_encode($gradingGroups));
        $positionRanks = PositionRank::get();
        $positionRanks = json_decode(json_encode($positionRanks));
        $departments = Department::get();
        $departments = json_decode(json_encode($departments));
        $sections = Section::get();
        $sections = json_decode(json_encode($sections));

        $grading_group_id = null;
        $position_rank_id = null;
        $department_id = null;
        $section_id = null;

        if($request->isMethod('post')){
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            DB::beginTransaction();
            try{
                foreach( $data['ids'] as $index => $id ) {
                   Employee::where('id','=',$id)->update([
                    'grading_group_id' => $data['grading_group_id'][$index]
                    ,'evaluator1_id' => $data['evaluator1_id'][$index]
                    ,'evaluator2_id' => $data['evaluator2_id'][$index]
                    ]);
                }// foreach
                DB::commit();

                return redirect('/employees/view-evaluators')->with('flash_message_success','Employee updated Successfully');
            }catch(\Exception $e){
                DB::rollback();

                return redirect('/employees/view-evaluators')->with('flash_message_error','Error : '.$e->getMessage());
            }
        }

        $employees = $employees->get();
        $employees = json_decode(json_encode($employees));    
        return view('employees.view_evaluators')->with(compact('positionRanks','departments','sections','employees','grading_group_id','position_rank_id','department_id','section_id'));
    }

    public function listEmployeesByFilter(Request $request){
         $grading_group_id = null;
        $position_rank_id = null;
        $department_id = null;
        $section_id = null;

        $employees = Employee::select('employees.id','employees.person_code','employees.image','employees.person_full_name','employees.position_name','employees.position_rank_id','employees.section_id','employees.department_id'
            ,'position_ranks.position_rank_name'
            ,'sections.section_name'
            ,'departments.department_name'
        )->leftjoin('position_ranks','position_ranks.id','=','employees.position_rank_id')
            ->leftjoin('sections','sections.id','=','employees.section_id')
            ->leftjoin('departments','departments.id','=','employees.department_id');

        $employees->where('employees.status','=',1);

        if($request->isMethod('get')){
            if ($request->has('grading_group_id') && $request->grading_group_id != NULL) {
                $grading_group_id = $request->grading_group_id;
                $employees->where('employees.grading_group_id','=',$request->grading_group_id);
            }
            if ($request->has('position_rank_id') && $request->position_rank_id != NULL) {
                $position_rank_id = $request->position_rank_id;
                $employees->where('employees.position_rank_id','=',$request->position_rank_id);
            }
            if ($request->has('department_id') && $request->department_id != NULL) {
                $department_id = $request->department_id;
                $employees->where('employees.department_id','=',$request->department_id);
            }
            if ($request->has('section_id') && $request->section_id != NULL) {
                $section_id = $request->section_id;
                $employees->where('employees.section_id','=',$request->section_id);
            }
        }    
    
        $employees = $employees->get();

        $rowCount = $employees->count();
        $jsonData = array();
        $jsonData = json_decode(json_encode($employees), true);                    
        echo json_encode(array('success' => 
            'success', 'rowCount' => $rowCount, 'data' => json_encode($jsonData)));
    }

}
