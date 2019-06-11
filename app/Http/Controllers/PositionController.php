<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Department;
use App\Section;
use App\PositionRank;
use App\Position;

class PositionController extends Controller
{
	public function viewPositions(){
    	$positions = Position::get();
    	$positions = json_decode(json_encode($positions));
    	return view('admin.positions.view_positions')->with(compact('positions'));
    }

    public function addPosition(Request $request){
    	if($request->isMethod('post')){
    		$data = $request->all();
    		//echo "<pre>"; print_r($data); die;
    		$position = new Position;
    		$position->position_name = $data['position_name'];
            $position->position_name_eng = $data['position_name_eng'];
    		$position->status = 1;
    		$position->save();
    		return redirect('/admin/view-positions')->with('flash_message_success','Position added Successfully');
    	}
        $departments = Department::where(['status'=>1])->get();
        $sections = Section::where(['status'=>1])->get();
        $positionRanks = PositionRank::where(['status'=>1])->get();
    	//return view('admin.persons.add_person')->with(compact('levels'));
    	return view('admin.positions.add_position')->with(compact('departments','sections','positionRanks'));
    }
    public function editPosition(Request $request, $id = null){
    	if($request->isMethod('post')){
    		$data = $request->all();
    		//echo "<pre>"; print_r($data); die;
    		Position::where(['id'=>$id])->update(['position_name'=>$data['position_name'], 'position_name_eng'=>$data['position_name_eng']]);
    		return redirect('/admin/view-positions')->with('flash_message_success','Position updated Successfully');
    	}
    	$positionDetails = Position::where(['id'=>$id])->first();
    	return view('admin.positions.edit_position');
    }
    public function deletePosition(Request $request, $id = null){
    	if(!empty($id)){
    		Position::where(['id'=>$id])->delete();
    		return redirect('/admin/view-positions')->with('flash_message_success','Position deleted Successfully');
    	}
    	$categoryDetails = Position::where(['id'=>$id])->first();
    	return view('admin.categories.edit_position')->with(compact('positionDetails'));
    }
}
