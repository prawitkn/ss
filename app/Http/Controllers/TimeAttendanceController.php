<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\TimeAttendance;
use App\Term;
use App\Evaluate_header;
use App\GradingGroup;
use App\PositionRank;
use App\Department;
use App\Section;
use DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TimeAttendanceController extends Controller
{
    public function viewTimeAttendances(){
    	$terms = Term::orderByRaw('id','desc')->get();
    	$terms = json_decode(json_encode($terms));
    	return view('timeAttendances.view_timeAttendances')->with(compact('terms'));
    }

    public function addLeave(Request $request){
    	$user_id = Session::get('userId');

    	$cycle_no = null;

    	$term = Term::where('current','=',1)->first();
    	$term = json_decode(json_encode($term));

		$sumQty=$sumTotal=0;
		$rowCount=0;
    	if($request->isMethod('post')){
    		$term_id = $request->term_id;
    		$cycle_no = $request->cycle_no;

    		$this->validate($request, ['upload_files'=>'required|mimes:xls,xlsx']);

    		$path = $request->file('upload_files')->getRealPath();

    		$spreadsheet = new Spreadsheet($path);
			$sheet = $spreadsheet->getActiveSheet();
			/**  Identify the type of $inputFileName  **/
			$inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($path);
			/**  Create a new Reader of the type that has been identified  **/
			$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
			/**  Load $inputFileName to a Spreadsheet Object  **/
			$spreadsheet = $reader->load($path);
			$worksheet = $spreadsheet->getActiveSheet();
			$rows = $worksheet->toArray();
			//var_dump($rows); die;

			// // Begin : Loop for check index cell value.
			// echo "<table border=1>";
			// echo "<tr>"; for($i=0; $i<23; $i++){
			// 	echo "<td>".$i."</td>";
			// } echo "</tr>";
			// foreach($rows as $key => $value) {
			//     // key is the row count(starts from 0)
			//     // array of values
			//     echo "<tr>"; for($c=0; $c<23; $c++){
			// 		echo "<td>".$value[$c]."</td>";
			// 	} echo "</tr>";

			    
			//     // foreach($value as $iter => $column_value) {
			//     //     //$column_value the value of row
			        
			//     // };
			//     // $i+=1;
			//     echo "</tr>";
			// };
			// echo "</table>"; die;
			// // End : Loop for check index cell value.

			DB::table('time_attendance_upload_temp')->where('user_id','=',$user_id)->delete();

			$file_name=null;
			$file_name2=null;
			$employee_code=$employee_full_name=null;
    		$i=0; foreach($rows as $key => $value) {
		    // key is the row count(starts from 0)
		    // array of values
    			if($i==2) $file_name = $value[0];
    			if($i==4) $file_name2 = $value[0];
    			if(TRIM($value[0])!=""){
    				$employee_code=$value[0]; $employee_full_name=$value[5];
    			}
				 $insertTempTables = DB::statement(DB::raw("
	                INSERT time_attendance_upload_temp (`user_id`,`term_id`,`cycle_no`,`file_name`,`file_name2`,`employee_code`, `employee_full_name`, `leave_code`, `leave_name`, `qty`, `total`, `remark`) 
	                VALUES (".$user_id.",".$term_id.",".$cycle_no.",'".$file_name."','".$file_name2."','".$employee_code."','".$employee_full_name."','".$value[12]."','".$value[14]."','".$value[17]."','".$value[19]."','".$value[22]."') 
	            "));
			$i+=1; } // foreach 
			$rowCount=$i;			

			DB::select(DB::raw("UPDATE time_attendance_upload_temp
				JOIN employees ON employees.person_code=time_attendance_upload_temp.employee_code
				SET time_attendance_upload_temp.employee_id=employees.id
				WHERE time_attendance_upload_temp.user_id=".$user_id)
			);

			$uploadHeaderDetails = DB::table('time_attendance_upload_temp')->where('user_id','=',$user_id)->first();
			//echo "<pre>".dd($data); die;
    		$uploadHeaderDetails = json_decode(json_encode($uploadHeaderDetails));

			$data = DB::table('time_attendance_upload_temp')->where('user_id','=',$user_id)
			->whereRaw("leave_code IN ('2130','2140','2150')")->orderByRaw('employee_id asc')->get();
			//echo "<pre>".dd($data); die;
    		$data = json_decode(json_encode($data));
			//echo $data->count(); end;

    		return redirect('/timeAttendances/add-leave')->with(compact('term','uploadHeaderDetails','data','rowCount','sumQty','sumTotal'))->with('flash_message_success','อัพโหลดสำเร็จ');
    	}

    	$data = DB::table('time_attendance_upload_temp')->get();
    	$rowCount = $data->count();
    	foreach($data as $itm){
    		if(is_numeric($itm->qty)){
    			$sumQty+=$itm->qty; $sumTotal+=$itm->total;
    		}
    	}

    	DB::table('time_attendance_upload_temp')->whereRaw('length(employee_code) <> 5')->delete();

		// 2130		ป่วยไม่มีใบแพทย์
		// 2140		ป่วยมีใบแพทย์
		// 2150		หักลากิจ
		DB::table('time_attendance_upload_temp')->whereRaw("leave_code NOT IN ('2130','2140','2150')")->delete();
    	
    	$uploadHeaderDetails = DB::table('time_attendance_upload_temp')->where('user_id','=',$user_id)
			->whereRaw("leave_code IN ('2130','2140','2150')")->first();
		//echo "<pre>".dd($data); die;
		$uploadHeaderDetails = json_decode(json_encode($uploadHeaderDetails));
    	$data = DB::table('time_attendance_upload_temp')->where('user_id','=',$user_id)
			->whereRaw("leave_code IN ('2130','2140','2150')")->orderByRaw('employee_id asc')->get();
		//echo "<pre>".dd($data); die;
		$data = json_decode(json_encode($data));

    	return view('timeAttendances.add_leave')->with(compact('term','uploadHeaderDetails','data','rowCount','sumQty','sumTotal'));
    }

    public function useLeave(Request $request){
    	$user_id = Session::get('userId');

    	$term = Term::where('current','=',1)->first();
    	$term = json_decode(json_encode($term));

    	if($request->isMethod('post')){
    		$term_id = $request->term_id;
    		$cycle_no = $request->cycle_no;
    		//time_attendane_leave_details
			DB::table('time_attendance_leave_details')->where('user_id','=',$user_id)
			->where('term_id','=',$term_id)
			->where('cycle_no','=',$cycle_no)
			->whereRaw("leave_code IN ('2130','2140','2150')")
			->delete();

			//initialize array
			$inserts = [];
			$bids = DB::table('time_attendance_upload_temp')->where('user_id','=',$user_id)
			->where('term_id','=',$term_id)
			->where('cycle_no','=',$cycle_no)
			->whereRaw("leave_code IN ('2130','2140','2150')")
			->get();
			foreach($bids as $bid) {
			    $inserts[] = [ 'user_id' => $user_id ,
			           'term_id' => $term_id , 
			           'cycle_no' => $cycle_no , 
			           'file_name' => $bid->file_name , 
			           'file_name2' => $bid->file_name2 , 
			           'employee_id' => $bid->employee_id , 
			           'employee_code' => $bid->employee_code , 
			           'employee_full_name' => $bid->employee_full_name , 
			           'leave_code' => $bid->leave_code , 
			           'leave_name' => $bid->leave_name , 
			           'qty' => $bid->qty , 
			           'total' => $bid->total , 
			           'remark' => $bid->remark ]; 
			}
			DB::table('time_attendance_leave_details')->insert($inserts);

			// Use uploaded data.
    		switch($cycle_no){
    			case 1 : 
    				$updateStatus = DB::select(
		                DB::raw("
		                	UPDATE evaluate_headers hd 
		                	SET hd.sick_leave1_count = 0  
		                	WHERE hd.term_id=".$term_id." 
		                ")
		            );

    				// Sick leave 1
    				$updateStatus = DB::select(
		                DB::raw("
		                	UPDATE evaluate_headers hd 
		                	SET hd.sick_leave1_count = IFNULL((SELECT SUM(ta.total) 
		                						FROM time_attendance_leave_details ta 
		                						WHERE ta.leave_code IN ('2130','2140')
		                						AND ta.employee_id=hd.employee_id 
		                						AND ta.term_id=hd.term_id 
		                						AND ta.cycle_no=1 
		                							),0)
		                	WHERE hd.term_id=".$term_id." 
		                ")
		            );

		            // Personal leave 1
    				$updateStatus = DB::select(
		                DB::raw("
		                	UPDATE evaluate_headers hd 
		                	SET hd.personal_leave1_count = IFNULL((SELECT SUM(ta.total) 
		                						FROM time_attendance_leave_details ta 
		                						WHERE ta.leave_code IN ('2150')
		                						AND ta.employee_id=hd.employee_id 
		                						AND ta.term_id=hd.term_id 
		                						AND ta.cycle_no=1 
		                							),0)
		                	WHERE hd.term_id=".$term_id." 
		                ")
		            );
    				break;
    			case 2 : 
    				$updateStatus = DB::select(
		                DB::raw("
		                	UPDATE evaluate_headers hd 
		                	SET hd.sick_leave2_count = 0 
		                	WHERE hd.term_id=".$term_id." 
		                ")
		            );

    				// Sick leave 1
    				$updateStatus = DB::select(
		                DB::raw("
		                	UPDATE evaluate_headers hd 
		                	SET hd.sick_leave2_count = IFNULL((SELECT SUM(ta.total) 
		                						FROM time_attendance_leave_details ta 
		                						WHERE ta.leave_code IN ('2130','2140')
		                						AND ta.employee_id=hd.employee_id 
		                						AND ta.term_id=hd.term_id 
		                						AND tad.cycle_no=2 
		                							),0)
		                	WHERE hd.term_id=".$term_id." 
		                ")
		            );

		            // Personal leave 1
    				$updateStatus = DB::select(
		                DB::raw("
		                	UPDATE evaluate_headers hd 
		                	SET hd.personal_leave2_count = IFNULL((SELECT SUM(ta.total) 
		                						FROM time_attendance_leave_details ta 
		                						WHERE ta.leave_code IN ('2150')
		                						AND ta.employee_id=hd.employee_id 
		                						AND ta.term_id=hd.term_id 
		                						AND ta.cycle_no=2 
		                							),0)
		                	WHERE hd.term_id=".$term_id." 
		                ")
		            );
    				break;
    			default :
    		} // switch.


    		$updateStatus = DB::select(
                DB::raw("
                	UPDATE evaluate_headers hd 
                	INNER JOIN employees ep ON ep.id=hd.employee_id 
                	SET hd.sick_leave_score = IF ( YEAR(ep.date_of_work) = YEAR(NOW()) 
                	, (hd.sick_leave1_count+hd.sick_leave2_count) / 1.5
                	, (hd.sick_leave1_count+hd.sick_leave2_count) / 3 
                	) 
                	,hd.personal_leave_score = IF ( YEAR(ep.date_of_work) = YEAR(NOW()) 
                	, (hd.personal_leave1_count+hd.personal_leave2_count) / 1.5
                	, (hd.personal_leave1_count+hd.personal_leave2_count) / 3 
                	) 
                	WHERE hd.term_id=".$term_id." 
                ")
            );

			$uploadHeaderDetails = DB::table('time_attendance_leave_details')->where('user_id','=',$user_id)
			->where('term_id','=',$term_id)
			->where('cycle_no','=',$cycle_no)
			->whereRaw("leave_code IN ('2130','2140','2150')")
			->first();
			//echo "<pre>".dd($data); die;
    		$uploadHeaderDetails = json_decode(json_encode($uploadHeaderDetails));

			$data = DB::table('time_attendance_leave_details')->where('user_id','=',$user_id)
			->where('term_id','=',$term_id)
			->where('cycle_no','=',$cycle_no)
			->whereRaw("leave_code IN ('2130','2140','2150')")
			->get();
			//echo "<pre>".dd($data); die;
    		$data = json_decode(json_encode($data));
			//echo $data->count(); end;

    		return redirect('/timeAttendances/add-leave')->with(compact('term','uploadHeaderDetails','data'))->with('flash_message_success','ปรับปรุงข้อมูลสำเร็จ');
    	}
    	
    	$uploadHeaderDetails = DB::table('time_attendance_leave_details')->where('user_id','=',$user_id)
		->where('term_id','=',$term_id)
		->where('cycle_no','=',$cycle_no)
			->whereRaw("leave_code IN ('2130','2140','2150')")
		->first();
		//echo "<pre>".dd($data); die;
		$uploadHeaderDetails = json_decode(json_encode($uploadHeaderDetails));

		$data = DB::table('time_attendane_leave_details')->where('user_id','=',$user_id)
		->where('term_id','=',$term_id)
		->where('cycle_no','=',$cycle_no)
		->whereRaw("leave_code IN ('2130','2140','2150')")
		->get();
		//echo "<pre>".dd($data); die;
		$data = json_decode(json_encode($data));
		//echo $data->count(); end;

    	return view('timeAttendances.add_leave')->with(compact('term','uploadHeaderDetails','data'));
    }


    public function addAbsence(Request $request){
		$user_id = Session::get('userId');

    	$term = Term::where('current','=',1)->first();
    	$term = json_decode(json_encode($term));

    	$sumQty=$sumTotal=0;
		$rowCount=0;
    	if($request->isMethod('post')){
    		$term_id = $request->term_id;
    		$cycle_no = $request->cycle_no;

    		$this->validate($request, ['upload_files'=>'required|mimes:xls,xlsx']);

    		$path = $request->file('upload_files')->getRealPath();

    		$spreadsheet = new Spreadsheet($path);
			$sheet = $spreadsheet->getActiveSheet();
			/**  Identify the type of $inputFileName  **/
			$inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($path);
			/**  Create a new Reader of the type that has been identified  **/
			$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
			/**  Load $inputFileName to a Spreadsheet Object  **/
			$spreadsheet = $reader->load($path);
			$worksheet = $spreadsheet->getActiveSheet();
			$rows = $worksheet->toArray();
			//var_dump($rows); die;

			// //// Begin : Loop for check index cell value.
			// echo "<table border=1>";
			// echo "<tr>"; for($i=0; $i<21; $i++){
			// 	echo "<td>".$i."</td>";
			// } echo "</tr>";
			// foreach($rows as $key => $value) {
			//     // key is the row count(starts from 0)
			//     // array of values
			//     echo "<tr>"; for($c=0; $c<21; $c++){
			// 		echo "<td>".$value[$c]."</td>";
			// 	} echo "</tr>";

			    
			//     // foreach($value as $iter => $column_value) {
			//     //     //$column_value the value of row
			        
			//     // };
			//     // $i+=1;
			//     echo "</tr>";
			// };
			// echo "</table>"; die;
			// //// End : Loop for check index cell value.

			DB::table('time_attendance_upload_temp')->where('user_id','=',$user_id)->delete();

			$file_name=null;
			$file_name2=null;
			$employee_code=$employee_full_name=null;
    		$i=0; foreach($rows as $key => $value) {
		    // key is the row count(starts from 0)
		    // array of values
    			if($i==3) $file_name = $value[0];
    			if($i==5) $file_name2 = $value[0];
    			if(TRIM($value[0])!=""){
    				$employee_code=$value[0]; $employee_full_name=$value[3];
    			}
				 $insertTempTables = DB::statement(DB::raw("
	                INSERT time_attendance_upload_temp (`user_id`,`term_id`,`cycle_no`,`file_name`,`file_name2`,`employee_code`, `employee_full_name`, `leave_code`, `leave_name`, `qty`, `total`, `remark`) 
	                VALUES (".$user_id.",".$term_id.",".$cycle_no.",'".$file_name."','".$file_name2."','".$employee_code."','".$employee_full_name."','".$value[12]."','".$value[14]."','".$value[16]."','".$value[18]."','".$value[20]."') 
	            "));
			$i+=1; } // foreach 

			DB::select(DB::raw("UPDATE time_attendance_upload_temp
				JOIN employees ON employees.person_code=time_attendance_upload_temp.employee_code
				SET time_attendance_upload_temp.employee_id=employees.id
				WHERE time_attendance_upload_temp.user_id=".$user_id)
			);

			$uploadHeaderDetails = DB::table('time_attendance_upload_temp')->where('user_id','=',$user_id)->where('leave_code','=','2110')->first();
			//echo "<pre>".dd($data); die;
    		$uploadHeaderDetails = json_decode(json_encode($uploadHeaderDetails));

			$data = DB::table('time_attendance_upload_temp')->where('user_id','=',$user_id)->where('leave_code','=','2110')->get();
			//echo "<pre>".dd($data); die;
    		$data = json_decode(json_encode($data));
			//echo $data->count(); end;

    		return redirect('/timeAttendances/add-absence')->with(compact('term','uploadHeaderDetails','data'))->with('flash_message_success','อัพโหลดสำเร็จ');
    	}
    	$data = DB::table('time_attendance_upload_temp')->get();
    	$rowCount = $data->count();
    	foreach($data as $itm){
    		if(is_numeric($itm->qty)){
    			$sumQty+=$itm->qty; $sumTotal+=$itm->total;
    		}
    	}

    	// 2110		ขาดงาน
		DB::table('time_attendance_upload_temp')->whereRaw("leave_code NOT IN ('2110')")->delete();

    	$uploadHeaderDetails = DB::table('time_attendance_upload_temp')->where('user_id','=',$user_id)->where('leave_code','=','2110')->first();
		//echo "<pre>".dd($data); die;
		$uploadHeaderDetails = json_decode(json_encode($uploadHeaderDetails));
    	$data = DB::table('time_attendance_upload_temp')->where('user_id','=',$user_id)->where('leave_code','=','2110')->get();
		//echo "<pre>".dd($data); die;
		$data = json_decode(json_encode($data));

    	return view('timeAttendances.add_absence')->with(compact('term','uploadHeaderDetails','data','rowCount','sumQty','sumTotal'));
    }

    public function useAbsence(Request $request){
    	$user_id = Session::get('userId');

    	$term = Term::where('current','=',1)->first();
    	$term = json_decode(json_encode($term));

    	if($request->isMethod('post')){
    		$term_id = $request->term_id;
    		$cycle_no = $request->cycle_no;
    		//time_attendane_leave_details
			DB::table('time_attendance_leave_details')->where('user_id','=',$user_id)
			->where('term_id','=',$term_id)
			->where('cycle_no','=',$cycle_no)
			->whereRaw("leave_code NOT IN ('2110')")
			->delete();

			//initialize array
			$inserts = [];
			$bids = DB::table('time_attendance_upload_temp')->where('user_id','=',$user_id)
			->where('term_id','=',$term_id)
			->where('cycle_no','=',$cycle_no)
			->whereRaw("leave_code IN ('2110')")
			->get();
			foreach($bids as $bid) {
			    $inserts[] = [ 'user_id' => $user_id ,
			           'term_id' => $term_id , 
			           'cycle_no' => $bid->cycle_no , 
			           'file_name' => $bid->file_name , 
			           'file_name2' => $bid->file_name2 , 
			           'employee_id' => $bid->employee_id , 
			           'employee_code' => $bid->employee_code , 
			           'employee_full_name' => $bid->employee_full_name , 
			           'leave_code' => $bid->leave_code , 
			           'leave_name' => $bid->leave_name , 
			           'qty' => $bid->qty , 
			           'total' => $bid->total , 
			           'remark' => $bid->remark ]; 
			}
			DB::table('time_attendance_leave_details')->insert($inserts);

			// Use uploaded data.
    		switch($cycle_no){
    			case 1 : 
    				$updateStatus = DB::select(
		                DB::raw("
		                	UPDATE evaluate_headers hd 
		                	SET hd.absence1_count  = 0  
		                	WHERE hd.term_id=".$term_id." 
		                ")
		            );

    				// Absence leave 1
    				$updateStatus = DB::select(
		                DB::raw("
		                	UPDATE evaluate_headers hd 
		                	SET hd.absence1_count = IFNULL((SELECT SUM(ta.total) 
		                						FROM time_attendance_leave_details ta 
		                						WHERE ta.leave_code IN ('2110')
		                						AND ta.employee_id=hd.employee_id 
		                						AND ta.term_id=hd.term_id 
		                						AND ta.cycle_no=".$cycle_no." 
		                							),0)
		                	WHERE hd.term_id=".$term_id." 
		                ")
		            );
    				break;
    			case 2 : 
    				$updateStatus = DB::select(
		                DB::raw("
		                	UPDATE evaluate_headers hd 
		                	SET hd.absence2_count  = 0  
		                	WHERE hd.term_id=".$term_id." 
		                ")
		            );

    				// Absence leave 1
    				$updateStatus = DB::select(
		                DB::raw("
		                	UPDATE evaluate_headers hd 
		                	SET hd.absence2_count = IFNULL((SELECT SUM(ta.total) 
		                						FROM time_attendance_leave_details ta 
		                						WHERE ta.leave_code IN ('2110')
		                						AND ta.employee_id=hd.employee_id 
		                						AND ta.term_id=hd.term_id 
		                						AND ta.cycle_no=".$cycle_no." 
		                							),0)
		                	WHERE hd.term_id=".$term_id." 
		                ")
		            );
    				break;
    			default :
    		} // switch.


    		$updateStatus = DB::select(
                DB::raw("
                	UPDATE evaluate_headers hd 
                	SET hd.absence_score = (hd.absence1_count+hd.absence2_count) * 2 
                	WHERE hd.term_id=".$term_id." 
                ")
            );

			$uploadHeaderDetails = DB::table('time_attendance_leave_details')->where('user_id','=',$user_id)
			->where('term_id','=',$term_id)
			->where('cycle_no','=',$cycle_no)
			->whereRaw("leave_code IN ('2110')")
			->first();
			//echo "<pre>".dd($data); die;
    		$uploadHeaderDetails = json_decode(json_encode($uploadHeaderDetails));

			$data = DB::table('time_attendance_leave_details')->where('user_id','=',$user_id)
			->where('term_id','=',$term_id)
			->where('cycle_no','=',$cycle_no)
			->whereRaw("leave_code IN ('2110')")
			->get();
			//echo "<pre>".dd($data); die;
    		$data = json_decode(json_encode($data));
			//echo $data->count(); end;

    		return redirect('/timeAttendances/add-absence')->with(compact('term','uploadHeaderDetails','data'))->with('flash_message_success','ปรับปรุงข้อมูลสำเร็จ');
    	}
    	
    	$uploadHeaderDetails = DB::table('time_attendance_leave_details')->where('user_id','=',$user_id)
		->where('term_id','=',$term_id)
		->where('cycle_no','=',$cycle_no)
		->whereRaw("leave_code IN ('2110')")
		->first();
		//echo "<pre>".dd($data); die;
		$uploadHeaderDetails = json_decode(json_encode($uploadHeaderDetails));

		$data = DB::table('time_attendane_leave_details')->where('user_id','=',$user_id)
		->where('term_id','=',$term_id)
		->where('cycle_no','=',$cycle_no)
		->whereRaw("leave_code IN ('2110')")
		->get();
		//echo "<pre>".dd($data); die;
		$data = json_decode(json_encode($data));
		//echo $data->count(); end;

    	return view('timeAttendances.add_absence')->with(compact('term','uploadHeaderDetails','data'));
    }

    public function addLate(Request $request){
		$user_id = Session::get('userId');

    	$term = Term::where('current','=',1)->first();
    	$term = json_decode(json_encode($term));

    	$cycle_no = null;

    	$sumQty=$sumTotal=0;
		$rowCount=0;
    	if($request->isMethod('post')){
    		$term_id = $request->term_id;
    		$cycle_no = $request->cycle_no;

    		$this->validate($request, ['upload_files'=>'required|mimes:xls,xlsx']);

    		$path = $request->file('upload_files')->getRealPath();

    		$spreadsheet = new Spreadsheet($path);
			$sheet = $spreadsheet->getActiveSheet();
			/**  Identify the type of $inputFileName  **/
			$inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($path);
			/**  Create a new Reader of the type that has been identified  **/
			$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
			/**  Load $inputFileName to a Spreadsheet Object  **/
			$spreadsheet = $reader->load($path);
			$worksheet = $spreadsheet->getActiveSheet();
			$rows = $worksheet->toArray();
			//var_dump($rows); die;

			// //// Begin : Loop for check index cell value.
			// echo "<table border=1>";
			// echo "<tr>"; for($i=0; $i<26; $i++){
			// 	echo "<td>".$i."</td>";
			// } echo "</tr>";
			// foreach($rows as $key => $value) {
			//     // key is the row count(starts from 0)
			//     // array of values
			//     echo "<tr>"; for($c=0; $c<26; $c++){
			// 		echo "<td>".$value[$c]."</td>";
			// 	} echo "</tr>";

			    
			//     // foreach($value as $iter => $column_value) {
			//     //     //$column_value the value of row
			        
			//     // };
			//     // $i+=1;
			//     echo "</tr>";
			// };
			// echo "</table>"; die;
			// //// End : Loop for check index cell value.

			DB::table('time_attendance_upload_temp')->where('user_id','=',$user_id)->delete();

			$file_name=null;
			$file_name2=null;
			$employee_code=$employee_full_name=null;
    		$i=0; foreach($rows as $key => $value) {
		    // key is the row count(starts from 0)
		    // array of values
    			if($i==3) $file_name = $value[0];
    			if($i==5) $file_name2 = $value[0];
    			if(TRIM($value[0])!=""){
    				$employee_code=$value[0]; $employee_full_name=$value[4];
    			}
				 $insertTempTables = DB::statement(DB::raw("
	                INSERT time_attendance_upload_temp (`user_id`,`term_id`,`cycle_no`,`file_name`,`file_name2`,`employee_code`, `employee_full_name`, `leave_code`, `leave_name`, `qty`, `total`, `remark`) 
	                VALUES (".$user_id.",".$term_id.",".$cycle_no.",'".$file_name."','".$file_name2."','".$employee_code."','".$employee_full_name."','".$value[11]."','".$value[14]."','".$value[19]."','".$value[19]."','".$value[25]."') 
	            "));
			$i+=1; } // foreach 			

			DB::select(DB::raw("UPDATE time_attendance_upload_temp
				JOIN employees ON employees.person_code=time_attendance_upload_temp.employee_code
				SET time_attendance_upload_temp.employee_id=employees.id
				WHERE time_attendance_upload_temp.user_id=".$user_id)
			);

			$uploadHeaderDetails = DB::table('time_attendance_upload_temp')->where('user_id','=',$user_id)->whereRaw("leave_code IN ('2120','2430')")->first();
			//echo "<pre>".dd($data); die;
    		$uploadHeaderDetails = json_decode(json_encode($uploadHeaderDetails));

			$data = DB::table('time_attendance_upload_temp')->where('user_id','=',$user_id)->whereRaw("leave_code IN ('2120','2430')")->get();
			//echo "<pre>".dd($data); die;
    		$data = json_decode(json_encode($data));
			//echo $data->count(); end;

    		return redirect('/timeAttendances/add-late')->with(compact('term','uploadHeaderDetails','data'))->with('flash_message_success','อัพโหลดสำเร็จ');
    	}

    	$data = DB::table('time_attendance_upload_temp')->get();
    	$rowCount = $data->count();
    	foreach($data as $itm){
    		if(is_numeric($itm->qty)){
    			$sumQty+=$itm->qty; $sumTotal+=$itm->total;
    		}
    	}

    	// 2120	หักมาสาย
		// 2430	หักกลับก่อนเวลา
		DB::table('time_attendance_upload_temp')->whereRaw("leave_code NOT IN ('2120','2430')")->delete();

    	$uploadHeaderDetails = DB::table('time_attendance_upload_temp')->where('user_id','=',$user_id)->whereRaw("leave_code IN ('2120','2430')")->first();
		//echo "<pre>".dd($data); die;
		$uploadHeaderDetails = json_decode(json_encode($uploadHeaderDetails));
    	$data = DB::table('time_attendance_upload_temp')->where('user_id','=',$user_id)->whereRaw("leave_code IN ('2120','2430')")->get();
		//echo "<pre>".dd($data); die;
		$data = json_decode(json_encode($data));

    	return view('timeAttendances.add_late')->with(compact('term','uploadHeaderDetails','data','rowCount','sumQty','sumTotal'));
    }

    public function useLate(Request $request){
    	$user_id = Session::get('userId');

    	$term = Term::where('current','=',1)->first();
    	$term = json_decode(json_encode($term));

    	if($request->isMethod('post')){
    		$term_id = $request->term_id;
    		$cycle_no = $request->cycle_no;
    		//time_attendane_leave_details
			DB::table('time_attendance_leave_details')->where('user_id','=',$user_id)
			->where('term_id','=',$term_id)
			->where('cycle_no','=',$cycle_no)
			->whereRaw("leave_code IN ('2120','2430')")
			->delete();

			//initialize array
			$inserts = [];
			$bids = DB::table('time_attendance_upload_temp')->where('user_id','=',$user_id)
			->where('term_id','=',$term_id)
			->where('cycle_no','=',$cycle_no)
			->whereRaw("leave_code IN ('2120','2430')")
			->get();
			foreach($bids as $bid) {
			    $inserts[] = [ 'user_id' => $user_id ,
			           'term_id' => $term_id , 
			           'cycle_no' => $bid->cycle_no , 
			           'file_name' => $bid->file_name , 
			           'file_name2' => $bid->file_name2 , 
			           'employee_id' => $bid->employee_id , 
			           'employee_code' => $bid->employee_code , 
			           'employee_full_name' => $bid->employee_full_name , 
			           'leave_code' => $bid->leave_code , 
			           'leave_name' => $bid->leave_name , 
			           'qty' => $bid->qty , 
			           'total' => $bid->total , 
			           'remark' => $bid->remark ]; 
			}
			DB::table('time_attendance_leave_details')->insert($inserts);

			// Use uploaded data.
			//echo "<pre>".print_r($cycle_no); die;
    		switch($cycle_no){
    			case 1 : 
    				$updateStatus = DB::select(
		                DB::raw("
		                	UPDATE evaluate_headers hd 
		                	SET hd.late1_count  = 0  
		                	WHERE hd.term_id=".$term_id." 
		                ")
		            );

    				// Late leave 1
    				$updateStatus = DB::select(
		                DB::raw("
		                	UPDATE evaluate_headers hd 
		                	SET hd.late1_count = IFNULL((SELECT SUM(ta.total)*0.25 
		                						FROM time_attendance_leave_details ta 
		                						WHERE ta.leave_code IN ('2120','2430')
		                						AND ta.employee_id=hd.employee_id 
		                						AND ta.term_id=hd.term_id 
		                						AND ta.cycle_no=".$cycle_no." 
		                							),0)
		                	WHERE hd.term_id=".$term_id." 
		                ")
		            );
    				break;
    			case 2 : 
    				$updateStatus = DB::select(
		                DB::raw("
		                	UPDATE evaluate_headers hd 
		                	SET hd.late2_count  = 0  
		                	WHERE hd.term_id=".$term_id." 
		                ")
		            );

    				// Late leave 2
    				$updateStatus = DB::select(
		                DB::raw("
		                	UPDATE evaluate_headers hd 
		                	SET hd.late2_count = IFNULL((SELECT SUM(ta.total)*0.25 
		                						FROM time_attendance_leave_details ta 
		                						WHERE ta.leave_code IN ('2120','2430')
		                						AND ta.employee_id=hd.employee_id 
		                						AND ta.term_id=hd.term_id 
		                						AND ta.cycle_no=".$cycle_no." 
		                							),0)
		                	WHERE hd.term_id=".$term_id." 
		                ")
		            );
    				break;
    			default :
    		} // switch.


    		$updateStatus = DB::select(
                DB::raw("
                	UPDATE evaluate_headers hd 
                	SET hd.late_score = (hd.late1_count+hd.late2_count) 
                	WHERE hd.term_id=".$term_id." 
                ")
            );

			$uploadHeaderDetails = DB::table('time_attendance_leave_details')->where('user_id','=',$user_id)
			->where('term_id','=',$term_id)
			->where('cycle_no','=',$cycle_no)
			->whereRaw("leave_code IN ('2120','2430')")
			->first();
			//echo "<pre>".dd($data); die;
    		$uploadHeaderDetails = json_decode(json_encode($uploadHeaderDetails));

			$data = DB::table('time_attendance_leave_details')->where('user_id','=',$user_id)
			->where('term_id','=',$term_id)
			->where('cycle_no','=',$cycle_no)
			->whereRaw("leave_code IN ('2120','2430')")
			->get();
			//echo "<pre>".dd($data); die;
    		$data = json_decode(json_encode($data));
			//echo $data->count(); end;

    		return redirect('/timeAttendances/add-late')->with(compact('term','uploadHeaderDetails','data'))->with('flash_message_success','ปรับปรุงข้อมูลสำเร็จ');
    	}
    	
    	$uploadHeaderDetails = DB::table('time_attendance_leave_details')->where('user_id','=',$user_id)
		->where('term_id','=',$term_id)
		->where('cycle_no','=',$cycle_no)
		->whereRaw("leave_code IN ('2120','2430')")
		->first();
		//echo "<pre>".dd($data); die;
		$uploadHeaderDetails = json_decode(json_encode($uploadHeaderDetails));

		$data = DB::table('time_attendane_leave_details')->where('user_id','=',$user_id)
		->where('term_id','=',$term_id)
		->where('cycle_no','=',$cycle_no)
		->whereRaw("leave_code IN ('2120','2430')")
		->get();
		//echo "<pre>".dd($data); die;
		$data = json_decode(json_encode($data));
		//echo $data->count(); end;

    	return view('timeAttendances.add_late')->with(compact('term','uploadHeaderDetails','data'));
    }

    public function addWarning(Request $request){
		$user_id = Session::get('userId');

    	$term = Term::where('current','=',1)->first();
    	$term = json_decode(json_encode($term));
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

    	$cycle_no = 1;
    	if($request->isMethod('get')){
            if ($request->has('grading_group_id') && $request->grading_group_id != NULL) {
                $grading_group_id = $request->grading_group_id;
            }
            if ($request->has('position_rank_id') && $request->position_rank_id != NULL) {
                $position_rank_id = $request->position_rank_id;
            }
            if ($request->has('department_id') && $request->department_id != NULL) {
                $department_id = $request->department_id;
            }
            if ($request->has('section_id') && $request->section_id != NULL) {
                $section_id = $request->section_id;
            }
            if ($request->has('cycle_no') && $request->cycle_no != NULL) {
                $cycle_no = $request->cycle_no;
            }
        } // isMethod('get');

    	$sql =" SELECT evaluate_headers.*, employees.person_full_name, employees.position_name  
    	FROM evaluate_headers 
    	INNER JOIN employees ON employees.id=evaluate_headers.employee_id 
    	";
    	if ($grading_group_id != NULL) {
            $sql.=" AND employees.grading_group_id=".$grading_group_id;
        }
        if ($position_rank_id != NULL) {
        	$sql.=" AND employees.position_rank_id=".$position_rank_id;
        }
        if ($department_id != NULL) {
        	$sql.=" AND employees.department_id=".$department_id;
        }
        if ($section_id != NULL) {
        	$sql.=" AND employees.section_id=".$section_id;
        }
        $sql.=" WHERE evaluate_headers.term_id=".$term->id;

    	$evaluateHeaders = DB::select(DB::raw($sql));	 
		$evaluateHeaders = json_decode(json_encode($evaluateHeaders));
		//echo "<pre>".dd($evaluateHeaders); die;

    	return view('timeAttendances.add_warning')->with(compact('term','evaluateHeaders','gradingGroups','positionRanks','departments','sections'
    		,'grading_group_id','position_rank_id','department_id','section_id','cycle_no'));
    }

    public function useWarning(Request $request){
		$user_id = Session::get('userId');

    	$term = Term::where('current','=',1)->first();
    	$term = json_decode(json_encode($term));

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

    	$cycle_no = 1;

    	if($request->isMethod('get')){
            if ($request->has('grading_group_id') && $request->grading_group_id != NULL) {
                $grading_group_id = $request->grading_group_id;
            }
            if ($request->has('position_rank_id') && $request->position_rank_id != NULL) {
                $position_rank_id = $request->position_rank_id;
            }
            if ($request->has('department_id') && $request->department_id != NULL) {
                $department_id = $request->department_id;
            }
            if ($request->has('section_id') && $request->section_id != NULL) {
                $section_id = $request->section_id;
            }
            if ($request->has('cycle_no') && $request->cycle_no != NULL) {
                $cycle_no = $request->cycle_no;
            }
        } // isMethod('get');
        if($request->isMethod('post')){
        	$data = $request->all();
    		$term_id = $request->term_id;
    		$cycle_no = $request->cycle_no;
    		DB::beginTransaction();
            try{
            	switch($cycle_no){
            		case 1 : 
            			foreach( $data['ids'] as $index => $id ) {
		                   Evaluate_header::where('id','=',$id)->update([
		                    'warning1_count' => $data['warning'][$index]
		                    ,'warning_latter1_count' => $data['warning_latter'][$index]
		                    ,'suspended1_count'  => $data['suspended'][$index]
		                    ]);
		                }// foreach
            			break;
            		case 2 :
            			foreach( $data['ids'] as $index => $id ) {
		                   Evaluate_header::where('id','=',$id)->update([
		                    'warning2_count' => $data['warning'][$index]
		                    ,'warning_latter2_count' => $data['warning_latter'][$index]
		                    ,'suspended2_count' => $data['suspended'][$index]
		                    ]);
		                }// foreach
            			break;
            	}
                
                $updateStatus = DB::select(DB::raw("UPDATE evaluate_headers 
                	SET warning_score=(warning1_count+warning2_count)*4
                	, warning_latter_score=(warning_latter1_count+warning_latter2_count)*8
                	, suspended_score=(suspended1_count+suspended2_count)*8
                	WHERE term_id=".$term->id."
                	"));

                DB::commit();

		    	$sql =" SELECT evaluate_headers.*, employees.person_full_name, employees.position_name  
		    	FROM evaluate_headers 
		    	INNER JOIN employees ON employees.id=evaluate_headers.employee_id 
		    	";
		    	if ($grading_group_id != NULL) {
		            $sql.=" AND employees.grading_group_id=".$grading_group_id;
		        }
		        if ($position_rank_id != NULL) {
		        	$sql.=" AND employees.position_rank_id=".$position_rank_id;
		        }
		        if ($department_id != NULL) {
		        	$sql.=" AND employees.department_id=".$department_id;
		        }
		        if ($section_id != NULL) {
		        	$sql.=" AND employees.section_id=".$section_id;
		        }
		        $sql.=" WHERE evaluate_headers.term_id=".$term->id;

		    	$evaluateHeaders = DB::select(DB::raw($sql));	 
				$evaluateHeaders = json_decode(json_encode($evaluateHeaders));

                return redirect('/timeAttendances/add-warning')->with(compact('term','evaluateHeaders','gradingGroups','positionRanks','departments','sections'
		    		,'grading_group_id','position_rank_id','department_id','section_id','cycle_no'))->with('flash_message_success','บันทึกเรียบร้อย');
            }catch(\Exception $e){
                DB::rollback();

                // return redirect('/timeAttendances/add_warning')->with('flash_message_error','Error : '.$e->getMessage());
                return redirect('/timeAttendances/add-warning')->with(compact('term','evaluateHeaders','gradingGroups','positionRanks','departments','sections'
		    		,'grading_group_id','position_rank_id','department_id','section_id','cycle_no'))->with('flash_message_error','Error : '.$e->getMessage());
            }
		} // isMethod('post')

    	$sql =" SELECT evaluate_headers.*, employees.person_full_name, employees.position_name  
    	FROM evaluate_headers 
    	INNER JOIN employees ON employees.id=evaluate_headers.employee_id 
    	";
    	if ($grading_group_id != NULL) {
            $sql.=" AND employees.grading_group_id=".$grading_group_id;
        }
        if ($position_rank_id != NULL) {
        	$sql.=" AND employees.position_rank_id=".$position_rank_id;
        }
        if ($department_id != NULL) {
        	$sql.=" AND employees.department_id=".$department_id;
        }
        if ($section_id != NULL) {
        	$sql.=" AND employees.section_id=".$section_id;
        }
        $sql.=" WHERE evaluate_headers.term_id=".$term->id;

    	$evaluateHeaders = DB::select(DB::raw($sql));	 
		$evaluateHeaders = json_decode(json_encode($evaluateHeaders));
		//echo "<pre>".dd($evaluateHeaders); die;

    	return redirect('/timeAttendances/add-warning')->with(compact('term','evaluateHeaders','gradingGroups','positionRanks','departments','sections'
    		,'grading_group_id','position_rank_id','department_id','section_id','cycle_no'));
    }

    public function confirmTimeAttendance(Request $request){
    	$data = DB::table('temp_data')->get();
            //$data = $data->get();
			echo "<pre>".dd($data); die;
    	
    }
}
