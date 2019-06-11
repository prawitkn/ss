<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\TopicGroup;

class TopicGroupController extends Controller
{
    public function viewTopicGroups(){
    	$topicGroups = TopicGroup::select('topic_groups.*','topic_group_types.topic_group_type_name')
        ->join('topic_group_types','topic_group_types.id','=','topic_groups.topic_group_type_id')->get();
    	$topicGroups = json_decode(json_encode($topicGroups));
    	return view('topicGroups.view_topicGroups')->with(compact('topicGroups'));
    }

    public function addTopicGroup(Request $request){
    	if($request->isMethod('post')){
    		$data = $request->all();
    		//echo "<pre>"; print_r($data); die;
    		$topicGroup = new TopicGroup;
    		$topicGroup->seq_no = $data['seq_no'];
    		$topicGroup->topic_group_name = $data['topic_group_name'];
    		$topicGroup->ratio = $data['ratio'];
    		$topicGroup->status = 1;
    		$topicGroup->save();
    		return redirect('/topicGroups/view-topicGroups')->with('flash_message_success','Topic Group added Successfully');
    	}
        //$levels = Category::where(['parent_id'=>0])->get();
    	//return view('admin.persons.add_person')->with(compact('levels'));
    	return view('topicGroups.add_topicGroup');
    }
    public function editTopicGroup(Request $request, $id = null){
    	if($request->isMethod('post')){
    		$data = $request->all();
    		//echo "<pre>"; print_r($data); die;
    		TopicGroup::where(['id'=>$id])->update(['seq_no'=>$data['seq_no'], 'topic_group_name'=>$data['topic_group_name'], 'ratio'=>$data['ratio']]);
    		return redirect('/topicGroups/view-topicGroups')->with('flash_message_success','Topic Group updated Successfully');
    	}
    	$topicGroupDetails = TopicGroup::where(['id'=>$id])->first();
    	return view('topicGroups.edit_topicGroup')->with(compact('topicGroupDetails'));
    }
    public function deleteTopicGroup(Request $request, $id = null){
    	if(!empty($id)){
    		TopicGroup::where(['id'=>$id])->delete();
    		return redirect('/topicGroups/view-topicGroups')->with('flash_message_success','Topic Group deleted Successfully');
    	}
    	$topicGroupDetails = TopicGroup::where(['id'=>$id])->first();
    	return view('topicGroups.edit_topicGroup')->with(compact('topicGroupDetails'));
    }
    public function setActive(Request $request, $id = null, $status = null){
        if(!empty($id) AND !empty($status)){
            if($status==1){
                TopicGroup::where(['id'=>$id])->update(['status' => 2]);
            }else{
                TopicGroup::where(['id'=>$id])->update(['status' => 1]);
            }

            return redirect('/topicGroups/view-topicGroups')->with('flash_message_success','ปรับปรุงข้อมูลสำเร็จ');
        }
        return redirect('/topicGroups/view-topicGroups');
    }
}
