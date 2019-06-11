<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\PositionRank;
use App\PositionRankGroup;
use DB;

class PositionRankController extends Controller
{
    public function viewPositionRanks(){
    	$positionRanks = PositionRank::select('position_ranks.*'
            ,'position_rank_groups.position_rank_group_name')
            ->leftjoin('position_rank_groups','position_rank_groups.id','=','position_ranks.position_rank_group_id')->get();
    	$positionRanks = json_decode(json_encode($positionRanks));
    	return view('admin.position_ranks.view_position_ranks')->with(compact('positionRanks'));
    }

    public function addPositionRank(Request $request){
        $positionRankGroups = PositionRankGroup::where('id','>',1)->get();
        $positionRankGroups = json_decode(json_encode($positionRankGroups));

    	if($request->isMethod('post')){
    		$data = $request->all();
    		//echo "<pre>"; print_r($data); die;
    		$positionRank = new PositionRank;
    		$positionRank->position_rank_name = $data['position_rank_name'];
            $positionRank->position_rank_group_id = $data['position_rank_group_id'];
    		$positionRank->status = 1;
    		$positionRank->save();
    		return redirect('/admin/view-position_ranks')->with('flash_message_success','Position Rank added Successfully');
    	}
        //$levels = Category::where(['parent_id'=>0])->get();
    	//return view('admin.persons.add_person')->with(compact('levels'));
    	return view('admin.position_ranks.add_position_rank')->with(compact('positionRankGroups'));
    }
    public function editPositionRank(Request $request, $id = null){
        $positionRankGroups = PositionRankGroup::where('id','>',1)->get();
        $positionRankGroups = json_decode(json_encode($positionRankGroups));

    	if($request->isMethod('post')){
    		$data = $request->all();
            $url = $data['url'];
    		//echo "<pre>"; print_r($data); die;
    		PositionRank::where(['id'=>$id])->update(['position_rank_name'=>$data['position_rank_name'],'position_rank_group_id'=>$data['position_rank_group_id']]);
            
    		return redirect($url)->with('flash_message_success', 'แก้ไขข้อมูลสำเร็จ');
    	}
    	$positionRankDetails = PositionRank::where(['id'=>$id])->first();
    	return view('admin.position_ranks.edit_position_rank')->with(compact('positionRankDetails','positionRankGroups'));
    }
    public function deletePositionRank(Request $request, $id = null){
    	if(!empty($id)){
    		PositionRank::where(['id'=>$id])->delete();
    		return redirect('/admin/view-position_ranks')->with('flash_message_success','Position Rank deleted Successfully');
    	}
    	$positionRankDetails = PositionRank::where(['id'=>$id])->first();
    	return view('admin.position_ranks.edit_position_rank')->with(compact('positionRankDetails'));
    }
    public function setActive(Request $request, $id = null, $status = null){
        if(!empty($id) AND !empty($status)){
            if($status==1){
                PositionRank::where(['id'=>$id])->update(['status' => 2]);
            }else{
                PositionRank::where(['id'=>$id])->update(['status' => 1]);
            }

            return redirect('/admin/view-position_ranks')->with('flash_message_success','ปรับปรุงข้อมูลสำเร็จ');
        }
        return redirect('/admin/view-position_ranks');
    }
    public function editPositionRanks(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            $url_current = $data['url_current'];
            //echo "<pre>"; print_r($data); die;
            DB::beginTransaction();
            try{
                foreach( $data['ids'] as $index => $id ) {
                   PositionRank::where('id','=',$id)->update([
                    'position_rank_seq_no' => $data['seq_nos'][$index]
                    ]);
                }// foreach
                DB::commit();

                // return redirect('/positionRanks/view-positionRanks')->with('flash_message_success','ปรับปรุงข้อมูลสำเร็จ');
                return redirect($url_current)->with('flash_message_success', 'แก้ไขข้อมูลสำเร็จ');
            }catch(\Exception $e){
                DB::rollback();

                return redirect($url_current)->with('flash_message_error','ผิดพลาด : '.$e->getMessage());
            }
        }

        return redirect('/positionRanks/view-positionRanks');
    }
}
