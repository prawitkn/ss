<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\Term;

class TermController extends Controller
{
    public function viewTerms(){
    	$terms = Term::orderByRaw('id','desc')->get();
    	$terms = json_decode(json_encode($terms));
    	return view('terms.view_terms')->with(compact('terms'));
    }

    public function addTerm(Request $request){
    	if($request->isMethod('post')){
    		$data = $request->all();
    		//echo "<pre>"; print_r($data); die;
    		$term = new Term;
    		$term->term_name = $data['term_name'];
    		$term->status = 1;
    		$term->save();
    		return redirect('/terms/view-terms')->with('flash_message_success','Term added Successfully');
    	}
        //$levels = Category::where(['parent_id'=>0])->get();
    	//return view('admin.persons.add_person')->with(compact('levels'));
    	return view('terms.add_term');
    }
    public function editTerm(Request $request, $id = null){
    	if($request->isMethod('post')){
    		$data = $request->all();
    		//echo "<pre>"; print_r($data); die;
    		Term::where(['id'=>$id])->update(['term_name'=>$data['term_name']]);
    		return redirect('/terms/view-terms')->with('flash_message_success','Term updated Successfully');
    	}
    	$termDetails = Term::where(['id'=>$id])->first();
    	return view('terms.edit_term')->with(compact('termDetails'));
    }
    public function deleteTerm(Request $request, $id = null){
    	if(!empty($id)){
    		Term::where(['id'=>$id])->delete();
    		return redirect('/term/view-terms')->with('flash_message_success','Term deleted Successfully');
    	}
    	$termDetails = Term::where(['id'=>$id])->first();
    	return view('terms.edit_term')->with(compact('termDetails'));
    }
    public function createEvaluateHeader($id = null){
        DB::beginTransaction();
        try{
            $createTempTables = DB::select(
                DB::raw("
                    CREATE TEMPORARY TABLE temp_table
                    SELECT tm.id as term_id, emp.id as employee_id, emp.position_name, emp.grading_group_id, emp.evaluator1_id, emp.evaluator2_id 
                    FROM terms tm 
                    CROSS JOIN employees emp ON emp.status=1 
                    WHERE tm.id=".$id."  
                ")
            );
            $updateTempTables = DB::statement(DB::raw("
                UPDATE evaluate_headers hd 
                INNER JOIN temp_table tmp ON tmp.term_id=hd.term_id AND tmp.employee_id=hd.employee_id
                SET hd.employee_position=tmp.position_name
                , hd.grading_group_id=tmp.grading_group_id
                , hd.evaluator1_id=tmp.evaluator1_id
                , hd.evaluator2_id=tmp.evaluator2_id "
                ));
            $insertTempTables = DB::statement(DB::raw("
                INSERT INTO evaluate_headers (`term_id`, `employee_id`, `employee_position`, `grading_group_id`
                , `evaluator1_id`, `evaluator1_score`, `evaluator1_status`
                , `evaluator2_id`, `evaluator2_score`, `evaluator2_status`
                , `status`)
                SELECT hd.term_id, hd.employee_id, hd.position_name, hd.grading_group_id, hd.evaluator1_id, 0, 1, hd.evaluator2_id, 0, 1, 1
                FROM temp_table hd 
                WHERE NOT EXISTS (SELECT * FROM evaluate_headers x 
                                WHERE x.term_id=hd.term_id
                                AND x.employee_id=hd.employee_id
                                )
            "));

            return redirect('/terms/view-terms')->with('flash_message_success','เตรียมข้อมูลเรียบร้อย');
        }catch(\Exception $e){
            DB::rollback();

            return redirect('/terms/view-terms')->with('flash_message_error','Error : '.$e->getMessage());
        }
    }
    public function createNewEvaluateData(){
        
        $term = Term::where('current','=',1)->first();
        if(!$term){
            return redirect('/terms/view-terms')->with('flash_message_error','คุณยังไม่ได้กำหนดห้วงเวลาการประเมินปัจจุบัน');
        }

        DB::beginTransaction();
        try{
            $createTempTables = DB::select(
                DB::raw("
                    CREATE TEMPORARY TABLE temp_table
                    SELECT tm.id as term_id, emp.id as employee_id, emp.position_name, emp.grading_group_id, emp.evaluator1_id, emp.evaluator2_id 
                    FROM terms tm 
                    CROSS JOIN employees emp ON emp.status=1 
                    WHERE tm.id=".$term->id."  
                ")
            );
            $updateTempTables = DB::statement(DB::raw("
                UPDATE evaluate_headers hd 
                INNER JOIN temp_table tmp ON tmp.term_id=hd.term_id AND tmp.employee_id=hd.employee_id
                SET hd.employee_position=tmp.position_name
                , hd.grading_group_id=tmp.grading_group_id
                , hd.evaluator1_id=tmp.evaluator1_id
                , hd.evaluator2_id=tmp.evaluator2_id "
                ));
            $insertTempTables = DB::statement(DB::raw("
                INSERT INTO evaluate_headers (`term_id`, `employee_id`, `employee_position`, `grading_group_id`
                , `evaluator1_id`, `evaluator1_score`, `evaluator1_status`
                , `evaluator2_id`, `evaluator2_score`, `evaluator2_status`
                , `status`)
                SELECT hd.term_id, hd.employee_id, hd.position_name, hd.grading_group_id, hd.evaluator1_id, 0, 1, hd.evaluator2_id, 0, 1, 1
                FROM temp_table hd 
                WHERE NOT EXISTS (SELECT * FROM evaluate_headers x 
                                WHERE x.term_id=hd.term_id
                                AND x.employee_id=hd.employee_id
                                )
            "));

            // topic_position_group_id=1 : all level
             $insertTempTables = DB::statement(DB::raw("
                    UPDATE evaluate_details ed 
                    INNER JOIN (SELECT ehd.id as header_id
                        , tg.id as topic_group_id, tg.seq_no as topic_group_seq_no, tg.topic_group_name as topic_group_name, tg.ratio as topic_group_ratio
                        , tp.id as topic_id, tp.topic_name, tp.topic_desc 
                        FROM evaluate_headers ehd 
                        INNER JOIN employees emp ON emp.id=ehd.employee_id 
                        LEFT JOIN topic_groups tg ON tg.id<>1 AND tg.status=1 
                        INNER JOIN topics tp ON tp.status=1 
                            AND tp.topic_group_id = tg.id 
                            AND tp.topic_setting_type_id=1
                            AND tp.topic_position_group_id=1 
                        WHERE ehd.term_id=".$term->id."
                        ORDER BY emp.id, tg.seq_no, tp.id ) as tmp
                            ON ed.header_id=tmp.header_id
                            AND ed.topic_group_id=tmp.topic_group_id
                            AND ed.topic_id=tmp.topic_id 
                    SET ed.topic_group_seq_no=tmp.topic_group_seq_no
                    , ed.topic_group_name=tmp.topic_group_name
                    , ed.topic_group_ratio=tmp.topic_group_ratio
                    , ed.topic_name=tmp.topic_name
                    , ed.topic_desc=tmp.topic_desc
                    , ed.status=1 "
                    ));

            $createTempTables = DB::select(
                DB::raw("
                    INSERT INTO evaluate_details (`header_id`
                    , `topic_group_id`, `topic_group_seq_no`, `topic_group_name`, `topic_group_ratio`
                    , `topic_id`, `topic_name`, `topic_desc`, `status`)
                    SELECT *, 1 as status 
                    FROM (SELECT ehd.id as header_id
                        , tg.id as topic_group_id, tg.seq_no as topic_group_seq_no, tg.topic_group_name as topic_group_name, tg.ratio as topic_group_ratio
                        , tp.id as topic_id, tp.topic_name, tp.topic_desc 
                        FROM evaluate_headers ehd 
                        INNER JOIN employees emp ON emp.id=ehd.employee_id 
                        LEFT JOIN topic_groups tg ON tg.id<>1 AND tg.status=1 
                        INNER JOIN topics tp ON tp.status=1 
                            AND tp.topic_group_id = tg.id 
                            AND tp.topic_setting_type_id=1
                            AND tp.topic_position_group_id=1 
                        WHERE ehd.term_id=".$term->id."
                        ORDER BY emp.id, tg.seq_no, tp.id ) as tmp
                    WHERE NOT EXISTS (SELECT * FROM evaluate_details ed 
                                    WHERE ed.header_id=tmp.header_id
                                    AND ed.topic_group_id=tmp.topic_group_id
                                    AND ed.topic_id=tmp.topic_id)
                ")
            );

            // topic_position_group_id=2 : management level
            $insertTempTables = DB::statement(DB::raw("
                    UPDATE evaluate_details ed 
                    INNER JOIN (SELECT ehd.id as header_id
                        , tg.id as topic_group_id, tg.seq_no as topic_group_seq_no, tg.topic_group_name as topic_group_name, tg.ratio as topic_group_ratio
                        , tp.id as topic_id, tp.topic_name, tp.topic_desc 
                        FROM evaluate_headers ehd 
                        INNER JOIN employees emp ON emp.id=ehd.employee_id 
                        INNER JOIN position_ranks pr ON pr.id=emp.position_rank_id AND pr.position_rank_group_id=2 
                        LEFT JOIN topic_groups tg ON tg.id<>1 AND tg.status=1 
                        INNER JOIN topics tp ON tp.status=1 
                            AND tp.topic_group_id = tg.id 
                            AND tp.topic_setting_type_id=1
                            AND tp.topic_position_group_id=2 
                        WHERE ehd.term_id=".$term->id."
                        ORDER BY emp.id, tg.seq_no, tp.id ) as tmp
                            ON ed.header_id=tmp.header_id
                            AND ed.topic_group_id=tmp.topic_group_id
                            AND ed.topic_id=tmp.topic_id 
                    SET ed.topic_group_seq_no=tmp.topic_group_seq_no
                    , ed.topic_group_name=tmp.topic_group_name
                    , ed.topic_group_ratio=tmp.topic_group_ratio
                    , ed.topic_name=tmp.topic_name
                    , ed.topic_desc=tmp.topic_desc
                    , ed.status=1 "
                    ));

            $createTempTables = DB::select(
                DB::raw("
                    INSERT INTO evaluate_details (`header_id`
                    , `topic_group_id`, `topic_group_seq_no`, `topic_group_name`, `topic_group_ratio`
                    , `topic_id`, `topic_name`, `topic_desc`, `status`)
                    SELECT *, 1 as status
                    FROM (SELECT ehd.id as header_id
                        , tg.id as topic_group_id, tg.seq_no as topic_group_seq_no, tg.topic_group_name as topic_group_name, tg.ratio as topic_group_ratio
                        , tp.id as topic_id, tp.topic_name, tp.topic_desc 
                        FROM evaluate_headers ehd 
                        INNER JOIN employees emp ON emp.id=ehd.employee_id 
                        INNER JOIN position_ranks pr ON pr.id=emp.position_rank_id AND pr.position_rank_group_id=2 
                        LEFT JOIN topic_groups tg ON tg.id<>1 AND tg.status=1 
                        INNER JOIN topics tp ON tp.status=1 
                            AND tp.topic_group_id = tg.id 
                            AND tp.topic_setting_type_id=1
                            AND tp.topic_position_group_id=2 
                        WHERE ehd.term_id=".$term->id."
                        ORDER BY emp.id, tg.seq_no, tp.id ) as tmp
                    WHERE NOT EXISTS (SELECT * FROM evaluate_details ed 
                                    WHERE ed.header_id=tmp.header_id
                                    AND ed.topic_group_id=tmp.topic_group_id
                                    AND ed.topic_id=tmp.topic_id)
     
                ")
            );

            // topic_position_group_id=3 : operator level
            $insertTempTables = DB::statement(DB::raw("
                    UPDATE evaluate_details ed 
                    INNER JOIN (SELECT ehd.id as header_id
                        , tg.id as topic_group_id, tg.seq_no as topic_group_seq_no, tg.topic_group_name as topic_group_name, tg.ratio as topic_group_ratio
                        , tp.id as topic_id, tp.topic_name, tp.topic_desc 
                        FROM evaluate_headers ehd 
                        INNER JOIN employees emp ON emp.id=ehd.employee_id 
                        INNER JOIN position_ranks pr ON pr.id=emp.position_rank_id AND pr.position_rank_group_id=3 
                        LEFT JOIN topic_groups tg ON tg.id<>1 AND tg.status=1 
                        INNER JOIN topics tp ON tp.status=1 
                            AND tp.topic_group_id = tg.id 
                            AND tp.topic_setting_type_id=1
                            AND tp.topic_position_group_id=3 
                        WHERE ehd.term_id=".$term->id."
                        ORDER BY emp.id, tg.seq_no, tp.id ) as tmp
                            ON ed.header_id=tmp.header_id
                            AND ed.topic_group_id=tmp.topic_group_id
                            AND ed.topic_id=tmp.topic_id 
                    SET ed.topic_group_seq_no=tmp.topic_group_seq_no
                    , ed.topic_group_name=tmp.topic_group_name
                    , ed.topic_group_ratio=tmp.topic_group_ratio
                    , ed.topic_name=tmp.topic_name
                    , ed.topic_desc=tmp.topic_desc
                    , ed.status=1 "
                    ));

            $createTempTables = DB::select(
                DB::raw("
                    INSERT INTO evaluate_details (`header_id`
                    , `topic_group_id`, `topic_group_seq_no`, `topic_group_name`, `topic_group_ratio`
                    , `topic_id`, `topic_name`, `topic_desc`, `status`)
                    SELECT *, 1 as status 
                    FROM (SELECT ehd.id as header_id
                        , tg.id as topic_group_id, tg.seq_no as topic_group_seq_no, tg.topic_group_name as topic_group_name, tg.ratio as topic_group_ratio
                        , tp.id as topic_id, tp.topic_name, tp.topic_desc 
                        FROM evaluate_headers ehd 
                        INNER JOIN employees emp ON emp.id=ehd.employee_id 
                        INNER JOIN position_ranks pr ON pr.id=emp.position_rank_id AND pr.position_rank_group_id=3 
                        LEFT JOIN topic_groups tg ON tg.id<>1 AND tg.status=1 
                        INNER JOIN topics tp ON tp.status=1 
                            AND tp.topic_group_id = tg.id 
                            AND tp.topic_setting_type_id=1
                            AND tp.topic_position_group_id=3 
                        WHERE ehd.term_id=".$term->id."
                        ORDER BY emp.id, tg.seq_no, tp.id ) as tmp
                    WHERE NOT EXISTS (SELECT * FROM evaluate_details ed 
                                    WHERE ed.header_id=tmp.header_id
                                    AND ed.topic_group_id=tmp.topic_group_id
                                    AND ed.topic_id=tmp.topic_id)
                ")
            );


            // topic_setting_type_id=2 : by one / position / person
            $insertTempTables = DB::statement(DB::raw("
                    UPDATE evaluate_details ed 
                    INNER JOIN (SELECT ehd.id as header_id
                        , tg.id as topic_group_id, tg.seq_no as topic_group_seq_no, tg.topic_group_name as topic_group_name, tg.ratio as topic_group_ratio
                        , tp.id as topic_id, tp.topic_name, tp.topic_desc 
                        FROM evaluate_headers ehd 
                        INNER JOIN employees emp ON emp.id=ehd.employee_id 
                        INNER JOIN topic_groups tg ON tg.status=1 
                        INNER JOIN topics tp ON tp.status=1 
                            AND tp.topic_group_id = tg.id 
                            AND tp.topic_setting_type_id=2
                            AND tp.employee_id=ehd.employee_id 
                        WHERE ehd.term_id=".$term->id."
                        ORDER BY emp.id, tg.seq_no, tp.id ) as tmp
                            ON ed.header_id=tmp.header_id
                            AND ed.topic_group_id=tmp.topic_group_id
                            AND ed.topic_id=tmp.topic_id 
                    SET ed.topic_group_seq_no=tmp.topic_group_seq_no
                    , ed.topic_group_name=tmp.topic_group_name
                    , ed.topic_group_ratio=tmp.topic_group_ratio
                    , ed.topic_name=tmp.topic_name
                    , ed.topic_desc=tmp.topic_desc 
                    , ed.status=1 "
                    ));

            $createTempTables = DB::select(
                DB::raw("
                    INSERT INTO evaluate_details (`header_id`
                    , `topic_group_id`, `topic_group_seq_no`, `topic_group_name`, `topic_group_ratio`
                    , `topic_id`, `topic_name`, `topic_desc`, `status`)
                    SELECT *, 1 as status  
                    FROM (SELECT ehd.id as header_id
                        , tg.id as topic_group_id, tg.seq_no as topic_group_seq_no, tg.topic_group_name as topic_group_name, tg.ratio as topic_group_ratio
                        , tp.id as topic_id, tp.topic_name, tp.topic_desc 
                        FROM evaluate_headers ehd 
                        INNER JOIN employees emp ON emp.id=ehd.employee_id 
                        INNER JOIN topic_groups tg ON tg.status=1 
                        INNER JOIN topics tp ON tp.status=1 
                            AND tp.topic_group_id = tg.id 
                            AND tp.topic_setting_type_id=2
                            AND tp.employee_id=ehd.employee_id 
                        WHERE ehd.term_id=".$term->id."
                        ORDER BY emp.id, tg.seq_no, tp.id ) as tmp
                    WHERE NOT EXISTS (SELECT * FROM evaluate_details ed 
                                    WHERE ed.header_id=tmp.header_id
                                    AND ed.topic_group_id=tmp.topic_group_id
                                    AND ed.topic_id=tmp.topic_id)
                ")
            );

            DB::commit();


            return redirect('/terms/view-terms')->with('flash_message_success','สร้างข้อมูลจากฐานข้อมูลหลักหัวข้อการประเมินเรียบร้อย');
        }catch(\Exception $e){
            DB::rollback();

            return redirect('/terms/view-terms')->with('flash_message_error','Error : '.$e->getMessage());
        }
    }
    public function createCopyEvaluateData($id = null){

        $term = Term::where('current','=',1)->first();

        if(!$term){
            return redirect('/terms/view-terms')->with('flash_message_error','คุณยังไม่ได้กำหนดห้วงเวลาการประเมินปัจจุบัน');
        }else{
            $prev_term_id=$id;
            $curr_term_id=$term->id;

            DB::beginTransaction();
            try{
                $createTempTables = DB::select(
                    DB::raw("
                        CREATE TEMPORARY TABLE temp_table
                        SELECT tm.id as term_id, emp.id as employee_id, emp.position_name, emp.grading_group_id, emp.evaluator1_id, emp.evaluator2_id 
                        FROM terms tm 
                        CROSS JOIN employees emp ON emp.status=1 
                        WHERE tm.id=".$curr_term_id."   
                    ")
                );
                // Delete
                $deleteTables = DB::statement(DB::raw("
                    DELETE hd
                    FROM evaluate_headers hd 
                    WHERE NOT EXISTS (SELECT * FROM temp_table x 
                                    WHERE x.term_id=hd.term_id
                                    AND x.employee_id=hd.employee_id
                                    )
                    AND hd.term_id=".$curr_term_id." 
                "));

                $deleteTables = DB::statement(DB::raw("
                    DELETE dt
                    FROM evaluate_headers hd 
                    INNER JOIN evaluate_details dt ON dt.header_id=hd.id 
                    WHERE NOT EXISTS (SELECT * FROM temp_table x 
                                    WHERE x.term_id=hd.term_id
                                    AND x.employee_id=hd.employee_id
                                    )
                    AND hd.term_id=".$curr_term_id." 
                "));

                $updateTempTables = DB::statement(DB::raw("
                    UPDATE evaluate_headers hd 
                    INNER JOIN temp_table tmp ON tmp.term_id=hd.term_id AND tmp.employee_id=hd.employee_id
                    SET hd.employee_position=tmp.position_name
                    , hd.grading_group_id=tmp.grading_group_id
                    , hd.evaluator1_id=tmp.evaluator1_id
                    , hd.evaluator2_id=tmp.evaluator2_id 
                    , hd.status=1 "
                    ));                


                $insertTempTables = DB::statement(DB::raw("
                    INSERT INTO evaluate_headers (`term_id`, `employee_id`, `employee_position`, `grading_group_id`
                    , `evaluator1_id`, `evaluator1_score`, `evaluator1_status`
                    , `evaluator2_id`, `evaluator2_score`, `evaluator2_status`
                    , `status`)
                    SELECT hd.term_id, hd.employee_id, hd.position_name, hd.grading_group_id, hd.evaluator1_id, 0, 1, hd.evaluator2_id, 0, 1, 1
                    FROM temp_table hd 
                    WHERE NOT EXISTS (SELECT * FROM evaluate_headers x 
                                    WHERE x.term_id=hd.term_id
                                    AND x.employee_id=hd.employee_id
                                    )
                "));

                // Detail
                 $updateTempTables = DB::statement(DB::raw("
                        UPDATE evaluate_details ed 
                        INNER JOIN (SELECT hd.id as header_id
                            , edo.topic_group_id, edo.topic_group_seq_no, edo.topic_group_name, edo.topic_group_ratio
                            , edo.topic_id, edo.topic_name, edo.topic_desc 

                            FROM evaluate_headers hd 
                            LEFT JOIN evaluate_headers hdo ON hdo.employee_id=hd.employee_id
                                AND hdo.term_id=".$prev_term_id."
                            INNER JOIN evaluate_details edo ON edo.header_id=hdo.id 
                            WHERE hd.term_id=".$curr_term_id."
                            ORDER BY hd.id, edo.topic_seq_no, edo.id ) as tmp
                                ON ed.header_id=tmp.header_id
                                AND ed.topic_group_id=tmp.topic_group_id
                                AND ed.topic_id=tmp.topic_id 
                        SET ed.topic_group_seq_no=tmp.topic_group_seq_no
                        , ed.topic_group_name=tmp.topic_group_name
                        , ed.topic_group_ratio=tmp.topic_group_ratio
                        , ed.topic_name=tmp.topic_name
                        , ed.topic_desc=tmp.topic_desc "
                        ));

                $insertTempTables = DB::select(
                    DB::raw("
                        INSERT INTO evaluate_details (`header_id`
                        , `topic_group_id`, `topic_group_seq_no`, `topic_group_name`, `topic_group_ratio`
                        , `topic_id`, `topic_name`, `topic_desc`, `status`)
                        SELECT * 
                        FROM (SELECT hd.id as header_id
                            , edo.topic_group_id, edo.topic_group_seq_no, edo.topic_group_name, edo.topic_group_ratio
                            , edo.topic_id, edo.topic_name, edo.topic_desc, 1

                            FROM evaluate_headers hd 
                            LEFT JOIN evaluate_headers hdo ON hdo.employee_id=hd.employee_id
                                AND hdo.term_id=".$prev_term_id."
                            INNER JOIN evaluate_details edo ON edo.header_id=hdo.id 
                            WHERE hd.term_id=".$curr_term_id."
                            ORDER BY hd.id, edo.topic_seq_no, edo.id ) as tmp
                            
                        WHERE NOT EXISTS (SELECT * FROM evaluate_details ed 
                                        WHERE ed.header_id=tmp.header_id
                                        AND ed.topic_group_id=tmp.topic_group_id
                                        AND ed.topic_id=tmp.topic_id)
                    ")
                );

                return redirect('/terms/view-terms')->with('flash_message_success','สร้างข้อมูลจากการสำเนาห้วงประเมินก่อนหน้าเรียบร้อย');
            }catch(\Exception $e){
                DB::rollback();

                return redirect('/terms/view-terms')->with('flash_message_error','Error : '.$e->getMessage());
            }//.catch
        } //.!$term
        return redirect('/terms/view-terms')->with('flash_message_success','Term updated Successfully');
    }
    public function setCurrent(Request $request, $id = null){
        if($request->isMethod('get')){
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            Term::where(['current'=>1])->update(['current'=>0]);
            Term::where(['id'=>$id])->update(['current'=>1]);

            $terms = Term::orderByRaw('id','desc')->get();
            $terms = json_decode(json_encode($terms));
            return redirect('/terms/view-terms')->with('flash_message_success','Term updated Successfully', compact('terms'));
        }
        
        $terms = Term::orderByRaw('id','desc')->get();
        $terms = json_decode(json_encode($terms));
        return view('terms.view_terms')->with(compact('terms'));
    }
    public function closeTerm(Request $request, $id = null){
        if($request->isMethod('get')){
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            Term::where(['id'=>$id])->update(['status'=>3]);

            $terms = Term::orderByRaw('id','desc')->get();
            $terms = json_decode(json_encode($terms));
            return redirect('/terms/view-terms')->with('flash_message_success','Term close Successfully', compact('terms'));
        }
        
        $terms = Term::orderByRaw('id','desc')->get();
        $terms = json_decode(json_encode($terms));
        return view('terms.view_terms')->with(compact('terms'));
    }
}
