<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Section;

class SectionController extends Controller
{
    public function viewSections(){
    	$sections = Section::get();
    	$sections = json_decode(json_encode($sections));
    	return view('admin.sections.view_sections')->with(compact('sections'));
    }

    public function addSection(Request $request){
    	if($request->isMethod('post')){
    		$data = $request->all();
    		//echo "<pre>"; print_r($data); die;
    		$section = new Section;
    		$section->section_name = $data['section_name'];
    		$section->status = 1;
    		$section->save();
    		return redirect('/admin/view-sections')->with('flash_message_success','Section added Successfully');
    	}
        //$levels = Category::where(['parent_id'=>0])->get();
    	//return view('admin.persons.add_person')->with(compact('levels'));
    	return view('admin.sections.add_section');
    }
    public function editSection(Request $request, $id = null){
    	if($request->isMethod('post')){
    		$data = $request->all();
    		//echo "<pre>"; print_r($data); die;
    		Section::where(['id'=>$id])->update(['section_name'=>$data['section_name']]);
    		return redirect('/admin/view-sections')->with('flash_message_success','Section updated Successfully');
    	}
    	$sectionDetails = Section::where(['id'=>$id])->first();
    	return view('admin.sections.edit_section')->with(compact('sectionDetails'));
    }
    public function deleteSection(Request $request, $id = null){
    	if(!empty($id)){
    		Section::where(['id'=>$id])->delete();
    		return redirect('/admin/view-sections')->with('flash_message_success','Section deleted Successfully');
    	}
    	$sectionDetails = Section::where(['id'=>$id])->first();
    	return view('admin.sections.edit_section')->with(compact('sectionDetails'));
    }
    public function setActive(Request $request, $id = null, $status = null){
        if(!empty($id) AND !empty($status)){
            if($status==1){
                Section::where(['id'=>$id])->update(['status' => 2]);
            }else{
                Section::where(['id'=>$id])->update(['status' => 1]);
            }

            return redirect('/admin/view-sections')->with('flash_message_success','ปรับปรุงข้อมูลสำเร็จ');
        }
        return redirect('/admin/view-sections');
    }
}
