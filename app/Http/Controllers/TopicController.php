<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

use App\Topic;
use App\TopicGroup;
use App\TopicPositionGroup;
use App\Employee;
use App\GradingGroup;
use App\positionRank;
use App\Department;
use App\Section;
use App\Term;
use DB;

class TopicController extends Controller
{
    public function viewTopics(Request $request){
    	$topicGroups = TopicGroup::where('topic_group_type_id','=',2)->get();
    	$topicPositionGroups = TopicPositionGroup::get();
        $sections = Section::get();


        $topic_group_id = "";
        $topic_position_group_id = "";
        $section_id = "";

        $topics = Topic::select('topics.id','topics.topic_group_id','topics.topic_position_group_id','topics.topic_name','topics.topic_desc','topics.status','topics.created_at','topics.updated_at'
        	,'topic_groups.topic_group_name','topic_position_groups.topic_position_group_name'
        )->leftjoin('topic_groups','topic_groups.id','=','topics.topic_group_id')
            ->leftjoin('topic_position_groups','topic_position_groups.id','=','topics.topic_position_group_id');
        if($request->has('isSubmit') && $request->isSubmit == 1){
            if ($request->has('topic_group_id') && $request->topic_group_id != NULL) {
                $topic_group_id = $request->topic_group_id;
                $topics->where('topics.topic_group_id','=',$topic_group_id);
            }
            if ($request->has('topic_position_group_id') && $request->topic_position_group_id != NULL) {
                $topic_position_group_id = $request->topic_position_group_id;
                $topics->where('topics.topic_position_group_id','=',$topic_position_group_id);
            }
            if ($request->has('section_id') && $request->section_id != NULL) {
                $section_id = $request->section_id;
                $topics->where('topics.section_id','=',$section_id);
            }
        }else{
            $topics->where('topics.id','=',-1);
        }      
                
        //echo "<pre>"; print_r($employees->toSql()); die;
        $topics = $topics->where('topics.topic_setting_type_id','=',1)->orderByRaw('topics.id ASC')->get();

    	$topics = json_decode(json_encode($topics));
    	return view('topics.view_topics')->with(compact('topics','topicGroups','topicPositionGroups','sections','topic_group_id','topic_position_group_id','section_id'));
    }

    public function addTopic(Request $request){
    	$topicGroups = TopicGroup::where('topic_group_type_id','=',2)->get();
    	$topicPositionGroups = TopicPositionGroup::get();
        $sections = Section::get();

    	if($request->isMethod('post')){
    		$data = $request->all();
    		//echo "<pre>"; print_r($data); die;
    		$topic = new Topic;
    		$topic->topic_group_id = $data['topic_group_id'];
    		$topic->topic_setting_type_id = 1;
    		$topic->topic_position_group_id = $data['topic_position_group_id'];
            $topic->section_id = $data['section_id'];
    		$topic->topic_name = $data['topic_name'];
    		$topic->topic_desc = $data['topic_desc'];
    		$topic->status = 1;
    		$topic->save();


            return redirect()->route('viewTopicsSearch', ['topic_group_id' => $data['topic_group_id'], 'topic_position_group_id' => $data['topic_position_group_id'], 'section_id' => $data['section_id']])->with('flash_message_success','บันทึกข้อมูลสำเร็จ');
    	}
        //$levels = Category::where(['parent_id'=>0])->get();
    	//return view('admin.persons.add_person')->with(compact('levels'));
    	return view('topics.add_topic')->with(compact('topicGroups','topicPositionGroups','sections'));
    }
    public function editTopic(Request $request, $id = null){
    	$topicGroups = TopicGroup::where('topic_group_type_id','=',2)->get();
    	$topicPositionGroups = TopicPositionGroup::get();
        $sections = Section::get();

    	if($request->isMethod('post')){
    		$data = $request->all();
            // $url_current = $data['url_current'];
            // $url_previous = $data['url_previous'];
    		//echo "<pre>"; print_r($data); die;
    		Topic::where(['id'=>$id])->update(['topic_group_id'=>$data['topic_group_id'], 'topic_position_group_id'=>$data['topic_position_group_id'], 'section_id'=>$data['section_id'], 'topic_name'=>$data['topic_name'], 'topic_desc'=>$data['topic_desc']]);
    	
            //return redirect($url_previous)->with('flash_message_success', 'ปรับปรุงข้อมูลสำเร็จ');

            return redirect()->route('viewTopicsSearch', ['topic_group_id' => $data['topic_group_id'], 'topic_position_group_id' => $data['topic_position_group_id'], 'section_id' => $data['section_id'], 'isSubmit' => 1])->with('flash_message_success','ปรับปรุงข้อมูลสำเร็จ');
    	}
    	$topicDetails = Topic::select('topics.*'
            ,'employees.person_full_name'
        )->leftjoin('employees','employees.id','=','topics.employee_id'
    )->where(['topics.id'=>$id])->first();
    	return view('topics.edit_topic')->with(compact('topicGroups','topicPositionGroups','sections','topicDetails'));
    }
    public function deleteTopic(Request $request, $id = null){
    	if(!empty($id)){
    		Topic::where(['id'=>$id])->delete();
    		return redirect('/topics/view-topics')->with('flash_message_success','Topic deleted Successfully');
    	}
    	$topicDetails = Topic::where(['id'=>$id])->first();
    	return view('topics.edit_topic')->with(compact('topicDetails'));
    }


    // Topic By One
    public function viewTopicsByOne(Request $request){
        $topicGroups = TopicGroup::where('id','=',1)->get();
        $employees = Employee::where('status','=',1)->get();


        $employee_id = "";

        //echo "<pre>"; print_r($topicPositionGroups); die;
        $topic_group_id = "";

        $topics = Topic::select('topics.id','topics.topic_group_id','topics.topic_position_group_id','topics.topic_name','topics.topic_desc','topics.status','topics.created_at','topics.updated_at'
            ,'topic_groups.topic_group_name','topic_position_groups.topic_position_group_name'
            ,'employees.person_full_name' 
        )->leftjoin('topic_groups','topic_groups.id','=','topics.topic_group_id')
        ->leftjoin('employees','employees.id','=','topics.employee_id')
            ->leftjoin('topic_position_groups','topic_position_groups.id','=','topics.topic_position_group_id');
        if($request->isMethod('get')){
            if($request->has('isSubmit') && $request->isSubmit == 1){
                if ($request->has('topic_group_id') && $request->topic_group_id != NULL) {
                    $topic_group_id = $request->topic_group_id;
                    $topics->where('topics.topic_group_id','=',$topic_group_id);
                }
                if ($request->has('employee_id') && $request->employee_id != NULL) {
                    $employee_id = $request->employee_id;
                    $topics->where('topics.employee_id','=',$employee_id);
                }
            }else{
                $topics->where('topics.id','=',-1);
            }    
        }        
                
        //echo "<pre>"; print_r($employees->toSql()); die;
        $topics = $topics->where('topics.topic_setting_type_id','=',2)->get();

        $employeeDetails = Employee::where('id','=',$employee_id)->first();

        $topics = json_decode(json_encode($topics));
        return view('topicsByOne.view_topics')->with(compact('topics','topicGroups','employees','topic_group_id','employeeDetails'));
    }

    public function addTopicByOne(Request $request){
        $topicGroups = TopicGroup::where('id','=',1)->get();
        $employees = Employee::where('status','=',1)->get();

        if($request->isMethod('post')){
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            $topic = new Topic;
            $topic->topic_group_id = $data['topic_group_id'];
            $topic->topic_setting_type_id = 2;  // By One 
            $topic->employee_id = $data['employee_id'];
            $topic->topic_name = $data['topic_name'];
            $topic->topic_desc = $data['topic_desc'];
            $topic->status = 1;
            $topic->save();
            // return redirect('/topics/view-topics-by-one')->with('flash_message_success','Topic added Successfully');
            return redirect()->route('viewTopicsByOneSearch', ['employee_id' => $data['employee_id']])->with('flash_message_success','Topic added Successfully');
        }
        //$levels = Category::where(['parent_id'=>0])->get();
        //return view('admin.persons.add_person')->with(compact('levels'));
        return view('topicsByOne.add_topic')->with(compact('topicGroups','employees'));
    }
    public function editTopicByOne(Request $request, $id = null){
        $topicGroups = TopicGroup::get();
        $employees = Employee::where('status','=',1)->get();

        if($request->isMethod('post')){
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            Topic::where(['id'=>$id])->update(['topic_group_id'=>$data['topic_group_id'], 'employee_id'=>$data['employee_id'], 'topic_name'=>$data['topic_name'], 'topic_desc'=>$data['topic_desc']]);
            return redirect()->route('viewTopicsByOneSearch', ['employee_id' => $data['employee_id']])->with('flash_message_success','Topic updated Successfully');
        }
        $topicDetails = Topic::select('topics.*'
            ,'employees.person_full_name'
        )->leftjoin('employees','employees.id','=','topics.employee_id'
    )->where(['topics.id'=>$id])->first();
        return view('topicsByOne.edit_topic')->with(compact('topicDetails','topicGroups','employees'));
    }
    public function deleteTopicByOne(Request $request, $id = null){
        if(!empty($id)){
            Topic::where(['id'=>$id])->delete();

            return redirect('/topics/view-topics-by-one')->with('flash_message_success','Topic deleted Successfully');
        }

        return redirect('/topics/view-topics-by-one');
    }
    public function applyTopicsToEmployees(Request $request){
        $user_id = Session::get('userId');

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
        $topics = null;

        if($request->isMethod('post')){
            $data = $request->all();

            $topicGroupDetail = TopicGroup::where('id','=',$data['topic_group_id'])->first();
            $topicGroupDetail = json_decode(json_encode($topicGroupDetail));   

            //initialize array
            $inserts = [];
            DB::table('topic_apply_to_employee_temp')->where('user_id','=',$user_id)->delete();
            $checkedTopicArr=$data['checkedTopicIds'];
            $checkedTopicIds=array();
            foreach( $data['checkedTopicIds'] as $index => $id ) {
                $checkedTopicIds[]=$id;
                $inserts[] = [ 'user_id' => $user_id ,
                       'topic_id' => $id , 
                       'seq_no' => $data['checkedSeqNos'][$index] ]; 
            }// foreach
            DB::table('topic_apply_to_employee_temp')->insert($inserts);

            $topics = DB::select(
                DB::raw("
                    SELECT tp.*, ae.seq_no 
                    FROM topics tp 
                    INNER JOIN topic_apply_to_employee_temp ae ON ae.topic_id=tp.id AND ae.user_id=".$user_id." 
                    ORDER BY ae.seq_no ASC 
                ")
            );
 
            return view('topics.apply_topics_to_employees')->with(compact('gradingGroups','positionRanks','departments','sections'
                ,'grading_group_id','position_rank_id','department_id','section_id'
                ,'topics','topicGroupDetail','checkedTopicArr'));
        } // $request->isMethod('post');

        $employees = $employees->get();
        $employees = json_decode(json_encode($employees));    
        return view('topics.apply_topics_to_employees')->with(compact('topics'));
    }
    public function saveTopicsToEmployees(Request $request){
        $user_id = Session::get('userId');

        if($request->isMethod('post')){
            $data = $request->all();

            $topic_group_id=$data['topic_group_id'];

            $checkedTopicIds=array();
            foreach( $data['checkedTopicIds'] as $index => $id ) {
                $checkedTopicIds[]=$id;
            }// foreach

            $checkedEmployeeIds=array();
            foreach( $data['checkedEmployeeIds'] as $index => $id ) {
                $checkedEmployeeIds[]=$id;
            }// foreach

            $term = Term::where('current','=',1)->first();

            if(!$term){
                return redirect('/terms/view-terms')->with('flash_message_error','คุณยังไม่ได้กำหนดห้วงเวลาการประเมินปัจจุบัน');
            } // if(!term)

           if($data['apply_type_name']=="reNew"){
                $q = "DELETE dt FROM evaluate_headers hd INNER JOIN evaluate_details dt ON dt.header_id=hd.id AND dt.topic_group_id=".$topic_group_id."
                WHERE hd.term_id=".$term->id."  
                AND hd.employee_id IN (".implode(", ", $checkedEmployeeIds).")            
                ";
                // "<pre>"; print_r($q); die;
                $deleteStatus = DB::delete($q);
                // "<pre>"; print_r($deleteStatus); die;
            }

            $insertStatus = DB::select(
                DB::raw("
                    INSERT INTO evaluate_details (`header_id`
                    , `topic_group_id`, `topic_group_seq_no`, `topic_group_name`, `topic_group_ratio`
                    , `topic_id`, `topic_seq_no`, `topic_name`, `topic_desc`, `status`)
                    SELECT *, 1 as status
                    FROM (SELECT ehd.id as header_id
                        , tg.id as topic_group_id, tg.seq_no as topic_group_seq_no, tg.topic_group_name as topic_group_name, tg.ratio as topic_group_ratio
                        , tp.id as topic_id, ae.seq_no as topic_seq_no, tp.topic_name, tp.topic_desc 
                        FROM evaluate_headers ehd 
                        INNER JOIN employees emp ON emp.id=ehd.employee_id 
                            AND emp.id IN (".implode(", ", $checkedEmployeeIds).") 
                        LEFT JOIN topic_groups tg ON tg.status=1 
                            AND tg.id=".$topic_group_id." 
                        INNER JOIN topics tp ON tp.status=1 
                            AND tp.topic_group_id = tg.id                  
                        INNER JOIN topic_apply_to_employee_temp ae ON ae.topic_id=tp.id AND ae.user_id=".$user_id." 
                        WHERE ehd.term_id=".$term->id." 
                        ORDER BY emp.id, tg.seq_no, ae.seq_no, tp.id ) as tmp
                    WHERE NOT EXISTS (SELECT * FROM evaluate_details ed 
                                    WHERE ed.header_id=tmp.header_id
                                    AND ed.topic_group_id=tmp.topic_group_id
                                    AND ed.topic_id=tmp.topic_id)
                ")
            );

            $topicGroupDetail = TopicGroup::where('id',$topic_group_id)->first();
            $topics = DB::select(
                DB::raw("
                    SELECT tp.*, ae.seq_no 
                    FROM topics tp 
                    INNER JOIN topic_apply_to_employee_temp ae ON ae.topic_id=tp.id AND ae.user_id=".$user_id." 
                    ORDER BY ae.seq_no ASC 
                ")
            );
            $employees = Employee::whereRaw("id IN (".implode(", ",$checkedEmployeeIds).")")->get();
            
            // return redirect()->route('viewTopicsToEmployees', ['topics', 'topicGroupDetail','employees'])->with('flash_message_success','กลุ่มหัวข้อที่เลือก นำมาใช้กับ กลุ่มพนักงานที่เลือก เรียบร้อยแล้ว');

            return view('topics.view_topics_to_employees')->with(compact('topics','topicGroupDetail','employees'))->with('flash_message_success','กลุ่มหัวข้อที่เลือก นำมาใช้กับ กลุ่มพนักงานที่เลือก เรียบร้อยแล้ว');
        } // $request->isMethod('post');

        $employees = $employees->get();
        $employees = json_decode(json_encode($employees));    
        return view('topics.apply_topics_to_employees')->with(compact('topics'));
    }
}
