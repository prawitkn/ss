<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use Session;

use App\User;
use App\Evaluate_header;
use App\UserRole;
use App\Employee;
use App\Term;
use App\GradingGroup;
use App\PositionRank;
use App\Department;
use App\Section;
use App\Grade;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function login(Request $request){
    	if($request->isMethod('post')){
    		$data = $request->input();
    		if(Auth::attempt(['username'=>$data['uname'],'password'=>$data['password']])){
    			//echo "Success"; die;
                //Session::put('adminSession',$data['email']);
                $user = User::where(['username'=>$data['uname']])->first();
                Session::put('userId',$user->id);
                Session::put('userFullname',$user->name);
                Session::put('admin',$user->admin);
                Session::put('employee_id',$user->employee_id);

                $userRoles = UserRole::select('user_roles.*','user_role_groups.user_role_group_name')
                ->join('user_role_groups','user_role_groups.id','=','user_roles.user_role_group_id')
                ->where(['user_roles.user_id'=>$user->id])->get();
                //echo "<pre>"; print_r($userRoles); die;
                Session::put('userRoles',$userRoles);

                return redirect('/index');
    		}else{
    			//echo "Failed"; die;
                return redirect('/admin')->with('flash_message_error','Invalid Username or Password');
    		}
    	}
    	return view('admin.admin_login');
    }

    public function dashboard(){
        //echo "test"; die;
        if(Session::has('userId')){
            //Perform all dashboard tasks
            //return view('admin.dashboard');
            return view('admin.dashboard');
        }else{
            return redirect('/admin/login')->with('flash_message_error','Please login to access.'); 
        }
    }

    public function index(Request $request){
        // $password = 'ak2019';
        // $password = Hash::make($password);
         //echo "<pre>"; print_r(Session::get('userRoles')); die;
        //echo "test"; die;
        if(Session::has('userId')){
            $section_id = "";
            $department_id = "";

            $evaluatorId = null;
            // if (Session::get('admin') == 1){
            //     $evaluatorId = 1;
            // }else{                
            //     $evaluatorId = Session::get('employee_id');
            // } 
            $currentTermDetails = Term::where('current','=',1)->first();

            // Role
            $biggestRoleId = 2;
            foreach (Session::get('userRoles') as $role){  
                switch($role->user_role_group_id){
                    case 1 : case 3 : case 4 : 
                        $biggestRoleId = $role->user_role_group_id;
                        break 2;
                    default : 
                        $biggestRoleId = $role->user_role_group_id;
                        break 1;
                }
            } //echo "<pre>"; print_r($evaluatorId); die;

            switch ($biggestRoleId) {
                case 1 : case 3 : case 4 :
                    $evaluatorId = 1;

                    // $terms = Term::get();
                    // $terms = json_decode(json_encode($terms));
                    $gradingGroups = GradingGroup::get();
                    $gradingGroups = json_decode(json_encode($gradingGroups));
                    $positionRanks = PositionRank::get();
                    $positionRanks = json_decode(json_encode($positionRanks));
                    $departments = Department::get();
                    $departments = json_decode(json_encode($departments));
                    $sections = Section::get();
                    $sections = json_decode(json_encode($sections));

                    // $employeeDetails = Employee::where('id','=',$evaluatorEmployeeId)->get();
                    // $employeeDetails = json_decode(json_encode($employeeDetails));   
                    if($request->isMethod('get')){
            
        //echo "<pre>"; print_r($evaluatorEmployeeId); die;
                        $grading_group_id = null;
                        $position_rank_id = null;
                        $department_id = null;
                        $section_id = null;

                        $evaluates = Evaluate_header::select('evaluate_headers.id','evaluate_headers.term_id','evaluate_headers.employee_id','evaluate_headers.status','evaluate_headers.evaluator1_id','evaluate_headers.evaluator1_status','evaluate_headers.evaluator2_id','evaluate_headers.evaluator2_status','employees.person_code','employees.person_full_name','employees.image','evaluate_headers.employee_position',
                            'evt1.person_full_name as evaluator1_person_full_name','evt2.person_full_name as evaluator2_person_full_name'
                        )->leftjoin('employees','employees.id','=','evaluate_headers.employee_id'
                        )->leftjoin('employees as evt1','evt1.id','=','evaluate_headers.evaluator1_id'
                    )->leftjoin('employees as evt2','evt2.id','=','Evaluate_headers.evaluator2_id');
                        $evaluates->where('evaluate_headers.term_id','=',$currentTermDetails->id);

                        if ($request->has('grading_group_id') && $request->grading_group_id != NULL) {
                            $grading_group_id = $request->grading_group_id;
                            $evaluates->where('evaluate_headers.grading_group_id','=',$grading_group_id);
                        }
                        if ($request->has('position_rank_id') && $request->position_rank_id != NULL) {
                            $position_rank_id = $request->position_rank_id;
                            $evaluates->where('employees.position_rank_id','=',$position_rank_id);
                        }
                        if ($request->has('department_id') && $request->department_id != NULL) {
                            $department_id = $request->department_id;
                            $evaluates->where('employees.department_id','=',$department_id);
                        }
                        if ($request->has('section_id') && $request->section_id != NULL) {
                            $section_id = $request->section_id;
                            $evaluates->where('employees.section_id','=',$section_id);
                        }
                        //echo "<pre>"; print_r($employees->toSql()); die;
                        $evaluates = $evaluates->get();
                        $evaluates = json_decode(json_encode($evaluates));          
                        return view('admin')->with(compact('currentTermDetails','gradingGroups','positionRanks','departments','sections','evaluates','grading_group_id','position_rank_id','department_id','section_id')); 
                    }// end if
                    return view('admin')->with(compact('currentTermDetails','gradingGroups','departments','sections')); 
                    break;
                case 2 :
                    $evaluatorEmployeeId = Session::get('employee_id');
                    //echo "<pre>"; print_r($evaluatorEmployeeId); die; 
                    $employeeDetails = Employee::where('id','=',$evaluatorEmployeeId)->first();
                    $employeeDetails = json_decode(json_encode($employeeDetails));   
        //echo "<pre>"; print_r($evaluatorEmployeeId); die;
                    $evaluates = Evaluate_header::select('evaluate_headers.id','evaluate_headers.term_id','evaluate_headers.employee_id','evaluate_headers.evaluator1_id','evaluate_headers.evaluator1_status','evaluate_headers.evaluator2_id','evaluate_headers.evaluator2_status','evaluate_headers.status','employees.person_code', 'employees.person_full_name','employees.image','evaluate_headers.employee_position'
                    )->join('employees','employees.id','=','evaluate_headers.employee_id')
                    ->where('evaluate_headers.term_id','=',$currentTermDetails->id)
                    ->where(function($q) {
                          $q->where('evaluate_headers.evaluator1_id','=',Session::get('employee_id'))
                            ->orWhere('evaluate_headers.evaluator2_id','=',Session::get('employee_id'));
                    });

                    //echo "<pre>"; print_r($employees->toSql()); die;
                    $evaluates = $evaluates->get();
                    $evaluates = json_decode(json_encode($evaluates));          
                    return view('index')->with(compact('currentTermDetails','evaluates','employeeDetails','evaluatorId')); 
                    break;
                default :
            } // end switch

            //return view('index');
        }else{
            return redirect('/admin/login')->with('flash_message_error','Please login to access.'); 
        }
    }

    public function settings(){
        return view('admin.settings');
    }

    public function chkPassword(Request $request){
        $data = $request->all();
        $current_password = $data['current_pwd'];
        $check_password = User::where(['admin'=>'1'])->first();
        //$check_password = app(User::class)->where('admin', '1')->first();
        if(Hash::check($current_password,$check_password->password)){
            echo "true"; die;
        }else{
            echo "false"; die;
        }
    }

    public function updatePassword(Request $request){
        if($request->isMethod('post')){
            // $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            $data = $request->all();
            $current_password = $data['current_pwd'];
            $check_password = User::where(['admin'=>'1'])->first();     
            if(Hash::check($current_password,$check_password->password)){
                $password = bcrypt($data['new_pwd']);
                User::where('id','1')->update(['password'=>$password]);
                return redirect('/admin/settings')->with('flash_message_success','Password update successfully.');
            }else{
                return redirect('/admin/settings')->with('flash_message_error','Incorrect current password.');
            }
        }//endif post
    }

    public function logout(){  
        Session::flush();
        return redirect('/admin')->with('flash_message_success','Logged out Successfully.');
    }


}
