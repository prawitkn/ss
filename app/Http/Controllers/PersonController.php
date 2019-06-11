<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\PositionRank;
use App\Department;
use App\Section;

use App\Employee;

class PersonController extends Controller
{
    
    public function viewPersons(){
        $positionRanks = PositionRank::get();
        $positionRanks = json_decode(json_encode($positionRanks));
        $departments = Department::get();
        $departments = json_decode(json_encode($departments));
        $sections = Section::get();
        $sections = json_decode(json_encode($sections));

        $persons = Employee::get();
        $persons = json_decode(json_encode($persons));        
        return view('persons.view_persons')->with(compact('persons'));
    }
    public function addPerson(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            $category = new Person;
            $category->name = $data['name'];
            $category->parent_id = $data['parent_id'];
            $category->description = $data['description'];
            $category->url = $data['url'];
            $category->save();
            return redirect('/admin/view-categories')->with('flash_message_success','Person added Successfully');
        }
        //$levels = Category::where(['parent_id'=>0])->get();
        //return view('admin.persons.add_person')->with(compact('levels'));
        return view('admin.persons.add_person');
    }
    public function editPerson(Request $request, $id = null){
        if($request->isMethod('post')){
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            Person::where(['id'=>$id])->update(['person_name'=>$data['person_name']]);
            return redirect('/admin/view-persons')->with('flash_message_success','Person updated Successfully');
        }
        $positionRankDetails = PositionRank::where(['id'=>$id])->first();
        return view('admin.persons.edit_person')->with(compact('positionRankDetails'));
    }
    public function deletePerson(Request $request, $id = null){
        if(!empty($id)){
            Person::where(['id'=>$id])->delete();
            return redirect('/admin/view-persons')->with('flash_message_success','Person deleted Successfully');
        }
        $personDetails = Person::where(['id'=>$id])->first();
        return view('admin.persons.edit_person')->with(compact('positionRankDetails'));
    }


}
