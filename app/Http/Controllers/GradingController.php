<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;

use App\Evaluate_header;
use App\UserRole;
use App\Employee;
use App\Term;
use App\GradingGroup;
use App\PositionRank;
use App\Department;
use App\Section;
use App\Grade;
use DB;

class GradingController extends Controller
{
    public function index(Request $request){
        // $password = 'ak2019';
        // $password = Hash::make($password);
        // echo "<pre>"; print_r($password); die;
        //echo "test"; die;
        if(Session::has('userId')){

            $evaluatorId = null;
            if (Session::get('admin') == 1){
                $evaluatorId = 1;
            }else{                
                $evaluatorId = Session::get('employee_id');
            }

            // $terms = Term::get();
            // $terms = json_decode(json_encode($terms));
            //$evaluator1 = Evaluate_header::where()

            

            $gradingGroups = GradingGroup::get();
            $gradingGroups = json_decode(json_encode($gradingGroups));

            $term_id = null;
            $grading_group_id = null;
            $term = Term::where('current','=',1)->first();
            $term_id = $term->id;
            $term = json_decode(json_encode($term));   

            $evaluates = Evaluate_header::select('evaluate_headers.id','evaluate_headers.term_id','evaluate_headers.employee_id','evaluate_headers.status','evaluate_headers.evaluator1_id','evaluate_headers.evaluator1_status','evaluate_headers.evaluator2_id','evaluate_headers.evaluator2_status','employees.person_code','employees.person_full_name','employees.image','evaluate_headers.employee_position','evaluate_headers.average_score','evaluate_headers.calculate_grading','evaluate_headers.final_grading',
                'evt1.person_full_name as evaluator1_person_full_name','evt2.person_full_name as evaluator2_person_full_name'
            )->leftjoin('employees','employees.id','=','evaluate_headers.employee_id'
            )->leftjoin('employees as evt1','evt1.id','=','evaluate_headers.evaluator1_id'
        )->leftjoin('employees as evt2','evt2.id','=','Evaluate_headers.evaluator2_id');
            $evaluates->where('evaluate_headers.term_id','=',$term_id);

            if($request->isMethod('get')){  
                if(!$request->has('isSubmit')){  
                    $evaluates->where('evaluate_headers.id','=',-1);
                }else{
                    if ($request->has('grading_group_id') && $request->grading_group_id != NULL) {
                        $grading_group_id = $request->grading_group_id;
                        $evaluates->where('evaluate_headers.grading_group_id','=',$grading_group_id);
                    } 
                }              
            }// end if
             //echo "<pre>"; print_r($employees->toSql()); die;
            $evaluates = $evaluates->orderByRaw('average_score DESC, calculate_grading ASC')->get();
            $evaluates = json_decode(json_encode($evaluates));          
            return view('grading')->with(compact('term','evaluates','gradingGroups','grading_group_id')); 
        }else{
            return redirect('/admin/login')->with('flash_message_error','Please login to access.'); 
        }
    }
    public function grading(Request $request, $grading_group_id = null){
        // $password = 'ak2019';
        // $password = Hash::make($password);
        // echo "<pre>"; print_r($password); die;
        //echo "test"; die;
        if(Session::has('userId')){

            $evaluatorId = null;
            if (Session::get('admin') == 1){
                $evaluatorId = 1;
            }else{                
                $evaluatorId = Session::get('employee_id');
            }

            // $terms = Term::get();
            // $terms = json_decode(json_encode($terms));

            $gradingGroups = GradingGroup::get();
            $gradingGroups = json_decode(json_encode($gradingGroups));

            $term_id = null;
            $term = Term::where('current','=',1)->first();
            $term_id = $term->id;
            $term = json_decode(json_encode($term));   


            $grades = Grade::where('status','=',1)->get();
            Evaluate_header::where([
                                ['term_id', '=', $term_id],
                                ['grading_group_id', '=', $grading_group_id]
                            ])->update(['calculate_grading'=>null]);
            $gradingCountTotal = Evaluate_header::where('term_id', '=', $term_id
                    )->where('grading_group_id', '=', $grading_group_id
                    )->whereNull('calculate_grading')->get()->count();
            foreach ($grades as $key => $value) {
                $ratio = ($value->ratio/100.0000);
                //echo "<pre>"; print_r($ratio); die;
                $limitPerson = $gradingCountTotal * $ratio;
                $limitPerson = round($limitPerson);
                //echo "<pre>"; print_r($limitPerson); die;

                $arrIds = Evaluate_header::select('id')->where('term_id', '=', $term_id
                    )->where('grading_group_id', '=', $grading_group_id
                    )->whereNull('calculate_grading')->take($limitPerson)->get();
                
                $inStr = ""; 
                if($arrIds->count()>0){
                    foreach ($arrIds as $k => $v) {
                        if($inStr==""){
                            $inStr.=" (".$v->id;
                        }else{
                            $inStr.=",".$v->id;
                        }
                    }
                    $inStr.=") ";
                }                 
                //echo "<pre>"; print_r($limitPerson); die;
                if($inStr==""){
                    $update = DB::statement(DB::raw("UPDATE evaluate_headers 
                        SET calculate_grading='".$value->grade_name."'  
                        WHERE term_id = ".$term_id."
                        AND grading_group_id = ".$grading_group_id." 
                        AND calculate_grading IS NULL 
                        ")
                    ); 
                }else{
                    $update = DB::statement(DB::raw("UPDATE evaluate_headers 
                        SET calculate_grading='".$value->grade_name."'  
                        WHERE id IN ".$inStr." 
                        ")
                    ); 
                }
            }

            $evaluates = Evaluate_header::select('evaluate_headers.id','evaluate_headers.term_id','evaluate_headers.employee_id','evaluate_headers.status','evaluate_headers.evaluator1_id','evaluate_headers.evaluator1_status','evaluate_headers.evaluator2_id','evaluate_headers.evaluator2_status','employees.person_code','employees.person_full_name','employees.image','evaluate_headers.employee_position','evaluate_headers.average_score','evaluate_headers.calculate_grading','evaluate_headers.final_grading',

                'evt1.person_full_name as evaluator1_person_full_name','evt2.person_full_name as evaluator2_person_full_name'
            )->leftjoin('employees','employees.id','=','evaluate_headers.employee_id'
            )->leftjoin('employees as evt1','evt1.id','=','evaluate_headers.evaluator1_id'
        )->leftjoin('employees as evt2','evt2.id','=','Evaluate_headers.evaluator2_id');
            $evaluates->where('evaluate_headers.term_id','=',$term_id);

            if($request->isMethod('get')){    
                if ($request->has('grading_group_id') && $request->grading_group_id != NULL) {
                    $grading_group_id = $request->grading_group_id;
                    $evaluates->where('evaluate_headers.grading_group_id','=',$grading_group_id);
                }               
            }// end if
             //echo "<pre>"; print_r($employees->toSql()); die;
            $evaluates = $evaluates->orderByRaw('average_score DESC')->get();
            $evaluates = json_decode(json_encode($evaluates));          
            return view('grading')->with(compact('term','evaluates','gradingGroups','grading_group_id')); 
        }else{
            return redirect('/admin/login')->with('flash_message_error','Please login to access.'); 
        }
    }
}
