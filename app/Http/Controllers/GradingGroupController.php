<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\GradingGroup;

class GradingGroupController extends Controller
{
    public function viewGradingGroups(){
    	$gradingGroups = GradingGroup::orderByRaw('seq_no, id ASC')->get();
    	$gradingGroups = json_decode(json_encode($gradingGroups));
    	return view('gradingGroups.view_gradingGroups')->with(compact('gradingGroups'));
    }

    public function addGradingGroup(Request $request){
    	if($request->isMethod('post')){
    		$data = $request->all();
    		//echo "<pre>"; print_r($data); die;
    		$gradingGroup = new GradingGroup;
    		$gradingGroup->seq_no = $data['seq_no'];
    		$gradingGroup->grading_group_name = $data['grading_group_name'];
            $gradingGroup->grading_group_desc = $data['grading_group_desc'];
    		$gradingGroup->status = 1;
    		$gradingGroup->save();
    		return redirect('/gradingGroups/view-gradingGroups')->with('flash_message_success','บันทึก กลุ่มการตัดเกรด สำเร็จ');
    	}
        //$levels = Category::where(['parent_id'=>0])->get();
    	//return view('admin.persons.add_person')->with(compact('levels'));
    	return view('gradingGroups.add_gradingGroup');
    }
    public function editGradingGroup(Request $request, $id = null){
    	if($request->isMethod('post')){
    		$data = $request->all();
    		//echo "<pre>"; print_r($data); die;
    		GradingGroup::where(['id'=>$id])->update(['seq_no'=>$data['seq_no'], 'grading_group_name'=>$data['grading_group_name'], 'grading_group_desc'=>$data['grading_group_desc']]);
    		return redirect('/gradingGroups/view-gradingGroups')->with('flash_message_success','ปรับปรุง กลุ่มการตัดเกรด สำเร็จ');
    	}
    	$gradingGroupDetails = GradingGroup::where(['id'=>$id])->first();
    	return view('gradingGroups.edit_gradingGroup')->with(compact('gradingGroupDetails'));
    }
    public function deleteGradingGroup(Request $request, $id = null){
    	if(!empty($id)){
    		GradingGroup::where(['id'=>$id])->delete();
    		return redirect('/gradingGroups/view-gradingGroups')->with('flash_message_success','ลบ กลุ่มการตัดเกรด สำเร็จ');
    	}
    	$gradingGroupDetails = GradingGroup::where(['id'=>$id])->first();
    	return view('gradingGroups.edit_gradingGroup')->with(compact('gradingGroupDetails'));
    }
    public function setActive(Request $request, $id = null, $status = null){
        if(!empty($id) AND !empty($status)){
            if($status==1){
                GradingGroup::where(['id'=>$id])->update(['status' => 2]);
            }else{
                GradingGroup::where(['id'=>$id])->update(['status' => 1]);
            }

            return redirect('/gradingGroups/view-gradingGroups')->with('flash_message_success','ปรับปรุงข้อมูลสำเร็จ');
        }
        return redirect('/gradingGroups/view-gradingGroups');
    }
    public function editGradingGroups(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            DB::beginTransaction();
            try{
                foreach( $data['ids'] as $index => $id ) {
                   GradingGroup::where('id','=',$id)->update([
                    'seq_no' => $data['seq_nos'][$index]
                    ]);
                }// foreach
                DB::commit();

                return redirect('/gradingGroups/view-gradingGroups')->with('flash_message_success','ปรับปรุงข้อมูลสำเร็จ');
            }catch(\Exception $e){
                DB::rollback();

                return redirect('/gradingGroups/view-gradingGroups')->with('flash_message_error','ผิดพลาด : '.$e->getMessage());
            }
        }

        return redirect('/gradingGroups/view-gradingGroups');
    }
}
