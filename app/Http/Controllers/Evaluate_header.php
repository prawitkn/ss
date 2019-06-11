<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Evaluate_header;

class Evaluate_header extends Controller
{
    public function viewTopics(Request $request){
    	$topicGroups = TopicGroup::get();
    	$topicPositionGroups = TopicPositionGroup::get();


        $topic_group_id = "";
        $topic_position_group_id = "";

        $topics = Topic::select('topics.id','topics.topic_group_id','topics.topic_position_group_id','topics.topic_name','topics.topic_desc','topics.status','topics.created_at','topics.updated_at'
        	,'topic_groups.topic_group_name','topic_position_groups.topic_position_group_name'
        )->leftjoin('topic_groups','topic_groups.id','=','topics.topic_group_id')
            ->leftjoin('topic_position_groups','topic_position_groups.id','=','topics.topic_position_group_id');
        if($request->isMethod('get')){
            if ($request->has('topic_group_id') && $request->topic_group_id != NULL) {
                $topic_group_id = $request->topic_group_id;
                $topics->where('topics.topic_group_id','=',$topic_group_id);
            }
            if ($request->has('topic_position_group_id') && $request->topic_position_group_id != NULL) {
                $topic_position_group_id = $request->topic_position_group_id;
                $topics->where('topics.topic_position_group_id','=',$topic_position_group_id);
            }
        }        
                
        //echo "<pre>"; print_r($employees->toSql()); die;
        $topics = $topics->get();

    	$topics = json_decode(json_encode($topics));
    	return view('topics.view_topics')->with(compact('topics','topicGroups','topicPositionGroups','topic_group_id','topic_position_group_id'));
    }

    public function addTopic(Request $request){
    	$topicGroups = TopicGroup::get();
    	$topicPositionGroups = TopicPositionGroup::get();

    	if($request->isMethod('post')){
    		$data = $request->all();
    		//echo "<pre>"; print_r($data); die;
    		$topic = new Topic;
    		$topic->topic_group_id = $data['topic_group_id'];
    		$topic->topic_position_group_id = $data['topic_position_group_id'];
    		$topic->topic_name = $data['topic_name'];
    		$topic->topic_desc = $data['topic_desc'];
    		$topic->status = 1;
    		$topic->save();
    		return redirect('/topics/view-topics')->with('flash_message_success','Topic added Successfully');
    	}
        //$levels = Category::where(['parent_id'=>0])->get();
    	//return view('admin.persons.add_person')->with(compact('levels'));
    	return view('topics.add_topic')->with(compact('topicGroups','topicPositionGroups'));
    }
    public function editEvaluate(Request $request, $id = null){
    	$topicGroups = TopicGroup::get();
    	$topicPositionGroups = TopicPositionGroup::get();

    	if($request->isMethod('post')){
    		$data = $request->all();
    		//echo "<pre>"; print_r($data); die;
    		Topic::where(['id'=>$id])->update(['topic_group_id'=>$data['topic_group_id'], 'topic_position_group_id'=>$data['topic_position_group_id'], 'topic_name'=>$data['topic_name'], 'topic_desc'=>$data['topic_desc']]);
    		return redirect('/topics/view-topics')->with('flash_message_success','Topic updated Successfully');
    	}
    	$topicDetails = Topic::where(['id'=>$id])->first();
    	// Header
		$evaluate_headers = Evaluate_header::select('evaluate_headers.id','evaluate_headers.term_id','evaluate_headers.employee_id','evaluate_headers.status','employees.person_full_name','employees.image','evaluate_headers.employee_position'
	        )->leftjoin('employees','employees.id','=','evaluate_headers.employee_id')
		->where('id','=',$id)->first();
	        $evaluate_headers = json_decode(json_encode($evaluate_headers));

		// Details 
		$evaluate_details = Evaluate_detail::select('evaluate_details.id','evaluate_details.header_id','evaluate_details.topic_group_id','evaluate_details.topic_group_ratio','evaluate_details.seq_no','evaluate_details.topic_name','evaluate_details.topic_desc','evaluate_details.score','evaluate_details.ratio_score','evaluate_details.score2','evaluate_details.ratio_score2'
	        )
		->leftjoin('topic_groups','topic_groups.id','=','evaluate_details.topic_group_id')
		->where('evaluate_details.header_id','=',$id)
		->where('evaluate_details.topic_group_id','=',$topicGroupId);  
	        
	        
	        //echo "<pre>"; print_r($employees->toSql()); die;
	        $evaluate_details = $evaluate_details->get();
	        $evaluate_details = json_decode(json_encode($evaluate_details));          
	        return view('evaluate.edit_evaluate')->with(compact('evaluate_headers','evaluate_details'));

    }
    public function deleteTopic(Request $request, $id = null){
    	if(!empty($id)){
    		Topic::where(['id'=>$id])->delete();
    		return redirect('/topics/view-topics')->with('flash_message_success','Topic deleted Successfully');
    	}
    	$topicDetails = Topic::where(['id'=>$id])->first();
    	return view('topics.edit_topic')->with(compact('topicDetails'));
    }
}
