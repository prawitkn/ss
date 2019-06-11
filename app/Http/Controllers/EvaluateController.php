<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

use App\Evaluate_header;
use App\Evaluate_details;
use App\TopicGroup;
use App\Topic;
use App\Employee;
use DB;

class EvaluateController extends Controller
{
    public function editEvaluate(Request $request, $id = null, $topic_group_id = null, $evaluator_id = null){
    	$topicGroups = TopicGroup::get();  
        $topicGroups = json_decode(json_encode($topicGroups));    
        
        // Evaluate
        $evaluateHeaders = Evaluate_header::where('id','=',$id)->get()->first();

        // Evaluator 
        $evaluatorId = $evaluator_id; //echo "<pre>"; print_r(Session::get('userRoles')); die;
        foreach (Session::get('userRoles') as $role){  
            switch($role->user_role_group_id){
                case 1 : case 3 : case 4 : 
                    $evaluatorId = $evaluator_id;
                    break 2;
                default : 
                    $evaluatorId = Session::get('employee_id');
                    break 1;
            }
        } //echo "<pre>"; print_r($evaluatorId); die;


        $evaluator = Employee::where('id','=',$evaluatorId)->get()->first();
        
        $evaluate_seq_no = NULL;
        if($evaluateHeaders->evaluator1_id == $evaluator->id) {
            $evaluate_seq_no = 1;
        }
        if($evaluateHeaders->evaluator2_id == $evaluator->id) {
            $evaluate_seq_no = 2;
        }
        $evaluate_seq_no = json_decode(json_encode($evaluate_seq_no)); 
        $evaluator = json_decode(json_encode($evaluator));  
        
        //echo "<pre>"; print_r($assessor); die;
    	$evaluators = Employee::where('position_rank_id','<',10)->get();

    	$topic_group_name = $topic_group_prev_id = $topic_group_next_id = null;
    	$data = $request->all();
        $topic_group_id = $topic_group_id;
        // if($request->isMethod('get')){
        //     if ($request->has('topic_group_id') && $request->section_id != NULL) {
        //         $topic_group_id = $data['topic_group_id'];
        //     }
        // }
        // Current Topic group  
    	$topic_group = TopicGroup::where('id','=',$topic_group_id)->first();
	    $topic_group = json_decode(json_encode($topic_group));  
        // Prev Topic group.
    	$topic_group_prev = TopicGroup::where('id','<',$topic_group_id)->orderByRaw('id')->first();
	    $topic_group_prev = json_decode(json_encode($topic_group_prev));
        // Next Topic Group
    	$topic_group_next = TopicGroup::where('id','>',$topic_group_id)->orderByRaw('id')->first();
	    $topic_group_next = json_decode(json_encode($topic_group_next));
    	    	
    	// Header
		$evaluate_headers = Evaluate_header::select('evaluate_headers.*'
            ,'terms.term_name','employees.person_full_name','employees.image')
        ->leftjoin('terms','terms.id','=','evaluate_headers.term_id')->leftjoin('employees','employees.id','=','evaluate_headers.employee_id')
		->where('evaluate_headers.id','=',$id)->first();
	        $evaluate_headers = json_decode(json_encode($evaluate_headers));

		// Details 
		$evaluateDetails = Evaluate_details::select('evaluate_details.id','evaluate_details.header_id','evaluate_details.topic_group_id','evaluate_details.topic_group_ratio','evaluate_details.topic_seq_no','evaluate_details.topic_name','evaluate_details.topic_desc','evaluate_details.score1','evaluate_details.ratio_score1','evaluate_details.score2','evaluate_details.ratio_score2'
	        )
		->leftjoin('topic_groups','topic_groups.id','=','evaluate_details.topic_group_id')
		->where('evaluate_details.header_id','=',$id)		
		->where('evaluate_details.topic_group_id','=',$topic_group_id);
		// ->where('evaluate_details.topic_group_id','=',$topicGroupId);  
	        
	        
        $evaluateDetails = $evaluateDetails->orderByRaw('evaluate_details.topic_seq_no, evaluate_details.id ASC')->get();        
        //echo "<pre>"; print_r($evaluateDetails); die;
        $evaluateDetails = json_decode(json_encode($evaluateDetails));          
        return view('evaluates.edit_evaluate')->with(compact('evaluate_headers','evaluateDetails','evaluator','evaluate_seq_no','topicGroups','topic_group','topic_group_prev','topic_group_next','evaluators','evaluator'));

    }
    public function saveEvaluate(Request $request){
    	if($request->isMethod('post')){
            $data = $request->all();
            $evaluate_id = $data['id'];
            $evaluator_id = $data['evaluator_id'];
            
            //Check Evaluator Seq.
            $evaluator = Evaluate_header::where('id','=',$evaluate_id)->first();

            $evaluator_count = 0;
            if(!is_null($evaluator->evaluator1_id) OR !empty($evaluator->evaluator1_id)){
                $evaluator_count+=1;
            }
            if(!is_null($evaluator->evaluator2_id) OR !empty($evaluator->evaluator2_id)){
                $evaluator_count+=1;
            }
            Evaluate_header::where('id','=',$evaluate_id)->update(['evaluator_count'=>$evaluator_count]);
           

            $evaluate_seq_no = NULL;
            if($evaluator->evaluator1_id == $evaluator_id) {
                $evaluate_seq_no = 1;
            }
            if($evaluator->evaluator2_id == $evaluator_id) {
                $evaluate_seq_no = 2;
            }
            if($evaluate_seq_no == NULL){
                return redirect('index')->with('flash_message_error','ผู้ประเมินไม่ถูกต้อง.'); 
            }
            foreach( $data['ids'] as $index => $id ) {
               $value = $_POST['score_'.$id];
               switch($evaluate_seq_no){
                case 1 : 
               $dtl = Evaluate_details::where(['id'=>$id])->first();
               $dtl->score1 = $value;
               $dtl->ratio_score1 = $value * $dtl->topic_group_ratio / 100;
               $dtl->update();
                    break;
                case 2 : 
               $dtl = Evaluate_details::where(['id'=>$id])->first();
               $dtl->score2 = $value;
               $dtl->ratio_score2 = $value * $dtl->topic_group_ratio / 100;
               $dtl->update();
                    break;
                default : 
               }// switch
            }// foreach

            
            

            // Summary to header row
            switch($evaluate_seq_no){
                case 1 : 
                    DB::statement(DB::raw("UPDATE evaluate_headers hd 
                        SET hd.evaluator1_score=(
                            SELECT SUM(score) 
                            FROM (
                                SELECT shd.id, SUM(sdt.ratio_score1)/COUNT(sdt.id) as score 
                                FROM evaluate_headers shd
                                INNER JOIN evaluate_details sdt ON sdt.header_id=shd.id AND sdt.status=1
                                WHERE shd.id=".$evaluate_id." 
                                GROUP BY shd.id, sdt.topic_group_id) as temp 
                            GROUP BY temp.id)
                        WHERE hd.id=".$evaluate_id)
                );
                    break;
                case 2 : 
               DB::statement(DB::raw("UPDATE evaluate_headers hd 
                        SET hd.evaluator2_score=(
                            SELECT SUM(score) 
                            FROM (
                                SELECT shd.id, SUM(sdt.ratio_score2)/COUNT(sdt.id) as score 
                                FROM evaluate_headers shd
                                INNER JOIN evaluate_details sdt ON sdt.header_id=shd.id AND sdt.status=1
                                WHERE shd.id=".$evaluate_id." 
                                GROUP BY shd.id, sdt.topic_group_id) as temp 
                            GROUP BY temp.id)
                        WHERE hd.id=".$evaluate_id)
                );
                    break;
                default : 
           }// switch

           DB::statement(DB::raw("UPDATE evaluate_headers hd 
                        SET hd.evaluator1_score=hd.evaluator1_score-hd.sick_leave_score-hd.personal_leave_score-hd.late_score-hd.absence_score-hd.warning_score -hd.warning_latter_score-hd.suspended_score
                        , hd.evaluator2_score=hd.evaluator2_score-hd.sick_leave_score-hd.personal_leave_score-hd.late_score-hd.absence_score-hd.warning_score -hd.warning_latter_score-hd.suspended_score 
                        WHERE hd.id=".$evaluate_id."
                            ")
                );


           DB::statement(DB::raw("UPDATE evaluate_headers hd 
                        SET hd.average_score=(evaluator1_score+evaluator2_score) / evaluator_count 
                        WHERE hd.id=".$evaluate_id."
                            ")
                );

            // go next page.
            $topic_group_id = $data['topic_group_id'];
            $topicGroupId = TopicGroup::where('topic_group_type_id','=',2)->where('id','>',$topic_group_id)->orderByRaw('id, topic_group_type_id')->get();
            if($topicGroupId->count()==0){
                //Go to comment
                return redirect('/evaluates/edit-evaluate-timeAttendance/'.$evaluate_id.'/'.($topic_group_id+1).'/'.$evaluator_id)->with('flash_message_success','บันทึกเรียบร้อย');
            }else{
                // Go next group id.
                $topicGroupId = $topicGroupId->first();
                $topic_group_id = $topicGroupId->id;                 
                return redirect('/evaluates/edit-evaluate/'.$evaluate_id.'/'.$topic_group_id.'/'.$evaluator_id)->with('flash_message_success','บันทึกเรียบร้อย'); 
            }
        }// isMethod('post')
    } // saveEvaluate
    public function viewEvaluate(Request $request, $id = null, $topic_group_id = null, $evaluator_id = null){
        $topicGroupsMenu = TopicGroup::where('topic_group_type_id','=',2)->orderByRaw('seq_no asc')->get();  
        $topicGroupsMenu = json_decode(json_encode($topicGroupsMenu));   

        $topicGroupsHeader = TopicGroup::where('topic_group_type_id','=',1)->orderByRaw('seq_no asc')->get();  
        $topicGroupsHeader = json_decode(json_encode($topicGroupsHeader));   

        $topicsHeader = Topic::where('topic_group_id','=',4)->orderByRaw('id ASC')->get();  
        $topicsHeader = json_decode(json_encode($topicsHeader));   
        
        // Evaluator 
        $evaluatorId = $evaluator_id; //echo "<pre>"; print_r(Session::get('userRoles')); die;
        foreach (Session::get('userRoles') as $role){  
            switch($role->user_role_group_id){
                case 1 : case 3 : case 4 : 
                    $evaluatorId = $evaluator_id;
                    break 2;
                default : 
                    $evaluatorId = Session::get('employee_id');
                    break 1;
            }
        } //echo "<pre>"; print_r($evaluatorId); die;

        // Evaluate
        $evaluateHeaders = Evaluate_header::where('id','=',$id)->get()->first();

        $evaluator = Employee::where('id','=',$evaluatorId)->get()->first();   
        $evaluate_seq_no = NULL;
        if($evaluateHeaders->evaluator1_id == $evaluator->id) {
            $evaluate_seq_no = 1;
        }
        if($evaluateHeaders->evaluator2_id == $evaluator->id) {
            $evaluate_seq_no = 2;
        }
        $evaluate_seq_no = json_decode(json_encode($evaluate_seq_no)); 
        $evaluator = json_decode(json_encode($evaluator));      


        //echo "<pre>"; print_r($assessor); die;
        $evaluators = Employee::where('position_rank_id','<',10)->get();

        $topic_group_name = $topic_group_prev_id = $topic_group_next_id = null;
        $data = $request->all();
        $topic_group_id = $topic_group_id;
        // if($request->isMethod('get')){
        //     if ($request->has('topic_group_id') && $request->section_id != NULL) {
        //         $topic_group_id = $data['topic_group_id'];
        //     }
        // }
        // Current Topic group  
        $topic_group = TopicGroup::where('id','=',$topic_group_id)->first();
        $topic_group = json_decode(json_encode($topic_group));  
        // Prev Topic group.
        $topic_group_prev = TopicGroup::where('id','<',$topic_group_id)->orderByRaw('id')->first();
        $topic_group_prev = json_decode(json_encode($topic_group_prev));
        // Next Topic Group
        $topic_group_next = TopicGroup::where('id','>',$topic_group_id)->orderByRaw('id')->first();
        $topic_group_next = json_decode(json_encode($topic_group_next));
                
        // Header
        $evaluate_headers = Evaluate_header::select('evaluate_headers.*'
            ,'terms.term_name','employees.person_full_name','employees.image'
            )->leftjoin('terms','terms.id','=','evaluate_headers.term_id')->leftjoin('employees','employees.id','=','evaluate_headers.employee_id')
        ->where('evaluate_headers.id','=',$id)->first();
            $evaluate_headers = json_decode(json_encode($evaluate_headers));

        // Details 
        $evaluateDetails = Evaluate_details::select('evaluate_details.id','evaluate_details.header_id','evaluate_details.topic_group_id','evaluate_details.topic_group_ratio','evaluate_details.topic_seq_no','evaluate_details.topic_name','evaluate_details.topic_desc','evaluate_details.score1','evaluate_details.ratio_score1','evaluate_details.score2','evaluate_details.ratio_score2'
            )
        ->leftjoin('topic_groups','topic_groups.id','=','evaluate_details.topic_group_id')
        ->where('evaluate_details.header_id','=',$id)       
        ->where('evaluate_details.topic_group_id','=',$topic_group_id);
        // ->where('evaluate_details.topic_group_id','=',$topicGroupId);  
                        
        //echo "<pre>"; print_r($employees->toSql()); die;
        $evaluateDetails = $evaluateDetails->orderByRaw('evaluate_details.topic_seq_no, evaluate_details.id ASC')->get();
        $evaluateDetails = json_decode(json_encode($evaluateDetails));          
        return view('evaluates.view_evaluate')->with(compact('evaluate_headers','evaluateDetails','evaluator','evaluate_seq_no','topicGroupsMenu','topicGroupsHeader','topicsHeader','topic_group','topic_group_prev','topic_group_next','evaluators','evaluator'));

    }// viewEvaluate
    public function confirmEvaluate(Request $request, $id = null){
        if($request->isMethod('post')){
            $data = $request->all();
            $evaluate_id = $data['id'];
            $evaluator_id = $data['evaluator_id'];
            
            //Check Evaluator Seq.
            $evaluator = Evaluate_header::where('id','=',$evaluate_id)->first();
            $evaluate_seq_no = NULL;
            if($evaluator->evaluator1_id == $evaluator_id) {
                $evaluate_seq_no = 1;
            }
            if($evaluator->evaluator2_id == $evaluator_id) {
                $evaluate_seq_no = 2;
            }
            if($evaluate_seq_no == NULL){
                return redirect('index')->with('flash_message_error','ผู้ประเมินไม่ถูกต้อง.'); 
            }

            switch($evaluate_seq_no){
                case 1 : 
               $hdr = Evaluate_header::where(['id'=>$id])->update(['evaluator1_status'=>2]);
                    break;
                case 2 : 
               $hdr = Evaluate_header::where(['id'=>$id])->update(['evaluator2_status'=>2]);
                    break;
                default : 
               }// switch

            
        }// isMethod('post')
        // go next page.
            return redirect('/evaluates/view-evaluate/'.$evaluate_id.'/1/'.$evaluator_id)->with('flash_message_success','บันทึกเรียบร้อย');
    } // confirmEvaluate
    public function rejectEvaluate(Request $request, $id = null, $evaluator_id = null){
        //Check Evaluator Seq.
        $evaluate = Evaluate_header::where('id','=',$id)->first();
        $evaluate_seq_no = NULL;
        if($evaluate->evaluator1_id == $evaluator_id) {
            $evaluate_seq_no = 1;
        }
        if($evaluate->evaluator2_id == $evaluator_id) {
            $evaluate_seq_no = 2;
        }
        if($evaluate_seq_no == NULL){
            return redirect('index')->with('flash_message_error','ผู้ประเมินไม่ถูกต้อง.'); 
        }
        
        switch($evaluate_seq_no){
            case 1 : 
           $hdr = Evaluate_header::where(['id'=>$id])->update(['evaluator1_status'=>1]);
                break;
            case 2 : 
           $hdr = Evaluate_header::where(['id'=>$id])->update(['evaluator2_status'=>1]);
                break;
            default : 
           }// switch
        return redirect('/evaluates/view-evaluate/'.$id.'/1/'.$evaluator_id)->with('flash_message_success','บันทึกเรียบร้อย');
    } // confirmEvaluate
    public function editEvaluateComments(Request $request, $id = null, $topic_group_id = null){
        $topicGroups = TopicGroup::get();  
        $topicGroups = json_decode(json_encode($topicGroups));    
        
        // Evaluate
        $evaluateHeaders = Evaluate_header::where('id','=',$id)->get()->first();

        // Evaluator 
        $evaluatorId = null;
        if (Session::get('admin') == 1){
            $evaluatorId = $evaluator_id;
        }else{                
            $evaluatorId = Session::get('employee_id');
        }
        $evaluator = Employee::where('id','=',$evaluatorId)->get()->first();
        
        $evaluate_seq_no = NULL;
        if($evaluateHeaders->evaluator1_id == $evaluator->id) {
            $evaluate_seq_no = 1;
        }
        if($evaluateHeaders->evaluator2_id == $evaluator->id) {
            $evaluate_seq_no = 2;
        }
        $evaluate_seq_no = json_decode(json_encode($evaluate_seq_no)); 
        $evaluator = json_decode(json_encode($evaluator));  
        
        //echo "<pre>"; print_r($assessor); die;
        $evaluators = Employee::where('position_rank_id','<',10)->get();

        $topic_group_name = $topic_group_prev_id = $topic_group_next_id = null;
        $data = $request->all();
        $topic_group_id = $topic_group_id;
        // if($request->isMethod('get')){
        //     if ($request->has('topic_group_id') && $request->section_id != NULL) {
        //         $topic_group_id = $data['topic_group_id'];
        //     }
        // }
        // Current Topic group  
        $topic_group = TopicGroup::where('id','=',$topic_group_id)->first();
        $topic_group = json_decode(json_encode($topic_group));  
        // Prev Topic group.
        $topic_group_prev = TopicGroup::where('id','<',$topic_group_id)->orderByRaw('id')->first();
        $topic_group_prev = json_decode(json_encode($topic_group_prev));
        // Next Topic Group
        $topic_group_next = TopicGroup::where('id','>',$topic_group_id)->orderByRaw('id')->first();
        $topic_group_next = json_decode(json_encode($topic_group_next));
                
        // Header
        $evaluate_headers = Evaluate_header::select('evaluate_headers.id','evaluate_headers.term_id','evaluate_headers.employee_id','evaluate_headers.status'
            ,'evaluate_headers.evaluator1_score'
            ,'evaluate_headers.evaluator1_comment1'
            ,'evaluate_headers.evaluator1_comment2'
            ,'evaluate_headers.evaluator1_comment3'
            ,'evaluate_headers.evaluator1_status'
            ,'evaluate_headers.evaluator2_score'
            ,'evaluate_headers.evaluator2_comment1'
            ,'evaluate_headers.evaluator2_comment2'
            ,'evaluate_headers.evaluator2_comment3'
            ,'evaluate_headers.evaluator2_status'
            ,'terms.term_name','employees.person_full_name','employees.image','evaluate_headers.employee_position','evaluate_headers.evaluator1_score','evaluate_headers.evaluator2_score'
            )->leftjoin('terms','terms.id','=','evaluate_headers.term_id')->leftjoin('employees','employees.id','=','evaluate_headers.employee_id')
        ->where('evaluate_headers.id','=',$id)->first();
            $evaluate_headers = json_decode(json_encode($evaluate_headers));

        // Details 
        $evaluateDetails = Evaluate_details::select('evaluate_details.id','evaluate_details.header_id','evaluate_details.topic_group_id','evaluate_details.topic_group_ratio','evaluate_details.topic_seq_no','evaluate_details.topic_name','evaluate_details.topic_desc','evaluate_details.score1','evaluate_details.ratio_score1','evaluate_details.score2','evaluate_details.ratio_score2'
            )
        ->leftjoin('topic_groups','topic_groups.id','=','evaluate_details.topic_group_id')
        ->where('evaluate_details.header_id','=',$id)       
        ->where('evaluate_details.topic_group_id','=',$topic_group_id);
        // ->where('evaluate_details.topic_group_id','=',$topicGroupId);  
            
            
        //echo "<pre>"; print_r($employees->toSql()); die;
        $evaluateDetails = $evaluateDetails->get();
        $evaluateDetails = json_decode(json_encode($evaluateDetails));          
        return view('evaluates.edit_evaluate_comments')->with(compact('evaluate_headers','evaluateDetails','evaluator','evaluate_seq_no','topicGroups','topic_group','topic_group_prev','topic_group_next','evaluators','evaluator'));

    }
    public function saveEvaluateComments(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            $evaluate_id = $data['id'];
            $evaluator_id = $data['evaluator_id'];
            
            //Check Evaluator Seq.
            $evaluator = Evaluate_header::where('id','=',$evaluate_id)->first();

            $evaluate_seq_no = NULL;
            if($evaluator->evaluator1_id == $evaluator_id) {
                $evaluate_seq_no = 1;
            }
            if($evaluator->evaluator2_id == $evaluator_id) {
                $evaluate_seq_no = 2;
            }
            if($evaluate_seq_no == NULL){
                return redirect('index')->with('flash_message_error','ผู้ประเมินไม่ถูกต้อง.'); 
            }
            switch($evaluate_seq_no){
                case 1 : 
           $hd = Evaluate_header::where(['id'=>$evaluate_id])->first();
           $hd->evaluator1_comment1 = $data['comment1'];
           $hd->evaluator1_comment2 = $data['comment2'];
           $hd->evaluator1_comment3 = $data['comment3'];
           $hd->update();
                break;
            case 2 : 
           $hd = Evaluate_header::where(['id'=>$evaluate_id])->first();
           $hd->evaluator2_comment1 = $data['comment1'];
           $hd->evaluator2_comment2 = $data['comment2'];
           $hd->evaluator2_comment3 = $data['comment3'];
           $hd->update();
                break;
            default : 
           }// switch

            return redirect('/evaluates/view-evaluate/'.$evaluate_id.'/1/'.$evaluator_id)->with('flash_message_success','บันทึกเรียบร้อย');
        }// isMethod('post')
    } // saveEvaluate
    public function editEvaluateTimeAttendance(Request $request, $id = null, $topic_group_id = null){
        $topicGroups = TopicGroup::get();  
        $topicGroups = json_decode(json_encode($topicGroups));    
        
        // Evaluate
        $evaluateHeaders = Evaluate_header::where('id','=',$id)->get()->first();

        // Evaluator 
        $evaluatorId = null;
        if (Session::get('admin') == 1){
            $evaluatorId = $evaluator_id;
        }else{                
            $evaluatorId = Session::get('employee_id');
        }
        $evaluator = Employee::where('id','=',$evaluatorId)->get()->first();
        
        $evaluate_seq_no = NULL;
        if($evaluateHeaders->evaluator1_id == $evaluator->id) {
            $evaluate_seq_no = 1;
        }
        if($evaluateHeaders->evaluator2_id == $evaluator->id) {
            $evaluate_seq_no = 2;
        }
        $evaluate_seq_no = json_decode(json_encode($evaluate_seq_no)); 
        $evaluator = json_decode(json_encode($evaluator));  
        
        //echo "<pre>"; print_r($assessor); die;
        $evaluators = Employee::where('position_rank_id','<',10)->get();

        $topic_group_name = $topic_group_prev_id = $topic_group_next_id = null;
        $data = $request->all();
        $topic_group_id = $topic_group_id;
        // if($request->isMethod('get')){
        //     if ($request->has('topic_group_id') && $request->section_id != NULL) {
        //         $topic_group_id = $data['topic_group_id'];
        //     }
        // }
        // Current Topic group  
        $topic_group = TopicGroup::where('id','=',$topic_group_id)->first();
        $topic_group = json_decode(json_encode($topic_group));  
        // Prev Topic group.
        $topic_group_prev = TopicGroup::where('id','<',$topic_group_id)->orderByRaw('id')->first();
        $topic_group_prev = json_decode(json_encode($topic_group_prev));
        // Next Topic Group
        $topic_group_next = TopicGroup::where('id','>',$topic_group_id)->orderByRaw('id')->first();
        $topic_group_next = json_decode(json_encode($topic_group_next));
                
        // Header
        $evaluate_headers = Evaluate_header::select('evaluate_headers.*' 
            ,'terms.term_name','employees.person_full_name','employees.image'
            )->leftjoin('terms','terms.id','=','evaluate_headers.term_id')->leftjoin('employees','employees.id','=','evaluate_headers.employee_id')
        ->where('evaluate_headers.id','=',$id)->first();
            $evaluate_headers = json_decode(json_encode($evaluate_headers));

        $topics = Topic::where('topic_group_id','=',4)->orderByRaw('id')->get();
        //echo "<pre>"; print_r($employees->toSql()); die;
        $topics = json_decode(json_encode($topics));          
        return view('evaluates.edit_evaluate_timeAttendance')->with(compact('evaluate_headers','topics','evaluator','evaluate_seq_no','topicGroups','topic_group','topic_group_prev','topic_group_next','evaluators','evaluator'));

    }
    public function saveEvaluateTimeAttendance(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            $evaluate_id = $data['id'];
            $evaluator_id = $data['evaluator_id'];
            
            //Check Evaluator Seq.
            $evaluator = Evaluate_header::where('id','=',$evaluate_id)->first();

            // go next page.
            $topic_group_id = $data['topic_group_id'];

            return redirect('/evaluates/edit-evaluate-comments/'.$evaluate_id.'/'.($topic_group_id+1).'/'.$evaluator_id)->with('flash_message_success','บันทึกเรียบร้อย');            
        }// isMethod('post')
    } // saveEvaluate
}
