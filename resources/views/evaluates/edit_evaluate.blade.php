@extends('layouts.adminLayout.admin_design')
@section('content')

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
     <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h3 class="page-title">การประเมินผลการทำงาน</h3>

                <!-- BreadCrumb right -->
                <!-- <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item" aria-current="page">Topics</li>

                            <li class="breadcrumb-item active" aria-current="page">Edit Topic</li>
                        </ol>
                    </nav>
                </div> -->
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
   
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
   <div class="row">
        <div class="col-md-12">

            <div class="card">
                <form  id="form1" class="form-horizontal" method="post" action="{{ url('/evaluates/save-evaluate') }}" 
     novalidate="novalidate" >{{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $evaluate_headers->id }}" />
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">                            
                            <img style="width: 80px;" src="{{ asset('/assets/images/employees/small/'.$evaluate_headers->image) }}" />
                        </div><!--col-md-4-->
                            
                        <div class="col-md-4">
                            ผู้รับการประเมิน : <strong>{{ $evaluate_headers->person_full_name }}</strong><br/>
                            {{ $evaluate_headers->employee_position }}
                        </div><!--col-md-4-->


                        <div class="col-md-4">
                            <input type="hidden" name="evaluator_id" id="evaluator_id" value="{{ $evaluator->id }}" />

                            ผู้ประเมิน : <select name="evaluator_ids" id="evaluator_ids" class="form-control">
                                @foreach($evaluators as $val)
                                <option value="{{ $val->id }}" 
                                    @if($val->id == $evaluator->id) selected  @endif
                                    >{{ $val->person_full_name }}</option>
                                @endforeach
                            </select>
                        </div><!--col-md-4-->
                        <div class="col-md-2" style="text-align: center;">                            
                            @switch ($evaluate_seq_no)
                                @case(1) 
                                    @switch ($evaluate_headers->evaluator1_status)
                                        @case(2) 
                                            <span class="badge badge-pill badge-success">ยืนยันผลประเมินแล้ว</span>
                                            @break
                                        @default
                                            <span class="badge badge-pill badge-danger">ยังไม่ยืนยันผลประเมิน</span>
                                    @endswitch
                                    <button class="btn btn-outline-danger">คะแนนรวม : {{ $evaluate_headers->evaluator1_score }}</button>
                                @break
                                @case(2)
                                    @switch ($evaluate_headers->evaluator2_status)
                                        @case(2) 
                                            <span class="badge badge-pill badge-success">ยืนยันผลประเมินแล้ว</span>
                                            @break
                                        @default
                                            <span class="badge badge-pill badge-danger">ยังไม่ยืนยันผลประเมิน</span>
                                    @endswitch
                                    <button class="btn btn-outline-danger">คะแนนรวม : {{ $evaluate_headers->evaluator2_score }}</button>
                                @default
                            @endswitch   
                        </div><!--col-md-2-->
                    </div><!--row-->

                    <div class="row">
                        <input type="hidden" name="topic_group_id" value="{{ $topic_group->id }}" />
                        @foreach($topicGroups as $topicGroup)
                        @switch($topicGroup->id)
                            @case(1) @case(2) @case(3)
                        <a href="{{ url('/evaluates/edit-evaluate/'.$evaluate_headers->id).'/'.$topicGroup->id.'/'.$evaluator->id }}" class="btn btn-{{ ($topicGroup->id == $topic_group->id ? 'success':'outline-success')}}" >{{ $topicGroup->id.'. '.$topicGroup->topic_group_name }} </a>&nbsp;
                            @break;
                            @case(4) 
                        <a href="{{ url('/evaluates/edit-evaluate-timeAttendance/'.$evaluate_headers->id).'/'.$topicGroup->id.'/'.$evaluator->id }}" class="btn btn-{{ ($topicGroup->id == $topic_group->id ? 'success':'outline-success')}}" >{{ $topicGroup->id.'. '.$topicGroup->topic_group_name }} </a>&nbsp;
                            @break;
                        @endswitch
                        @endforeach
                        <a href="{{ url('/evaluates/edit-evaluate-comments/'.$evaluate_headers->id).'/'.$topicGroup->id.'/'.$evaluator->id }}" class="btn btn-outline-success" > บันทึกความเห็น</a>
                    </div>

                    
                    
                    <div class="row table-responsive">
                        <!-- <h4 class="card-title">{{ $topic_group->id.' '.$topic_group->topic_group_name }}</h4> -->

                        <table class="table table-striped table-bordered table-reflow">
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 50px;">ลำดับ</th>
                                    <th style="text-align: center;">หัวข้อ</th>
                                    <th style="text-align: center;">คำอธิบายหัวข้อ</th>
                                    <th>5</th>
                                    <th>4</th>
                                    <th>3</th>
                                    <th>2</th>
                                    <th>1</th>
                                    <th>0</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($evaluateDetails as $evaluateDetail)
                                <tr class="gradeX">
                                    <td style="text-align: center; width: 50px;">{{ $evaluateDetail->topic_seq_no }}</td>
                                    <td><input type="hidden" name="ids[]" value="{{ $evaluateDetail->id }}" />{{ $evaluateDetail->topic_name }}</td>
                                    <td>{{ $evaluateDetail->topic_desc }}</td>
                                    <td>
                                        <input type="radio" name="score_{{ $evaluateDetail->id }}" value="5"
                                        @switch ($evaluate_seq_no)
                                            @case(1) 
                                                @if ($evaluateDetail->score1==5) echo ' checked ';
                                                @endif 
                                            @break
                                            @case(2)
                                                @if ($evaluateDetail->score2==5) echo ' checked ';
                                                @endif 
                                            @default
                                        @endswitch
                                        />
                                    </td>
                                    <td>
                                        <input type="radio" name="score_{{ $evaluateDetail->id }}" class="custom-control" value="4" 
                                        @switch ($evaluate_seq_no)
                                            @case(1) 
                                                @if ($evaluateDetail->score1==4) echo ' checked ';
                                                @endif 
                                            @break
                                            @case(2)
                                                @if ($evaluateDetail->score2==4) echo ' checked ';
                                                @endif 
                                            @default
                                        @endswitch
                                        />
                                    </td>
                                    <td>
                                        <input type="radio" name="score_{{ $evaluateDetail->id }}" value="3"
                                        @switch ($evaluate_seq_no)
                                            @case(1) 
                                                @if ($evaluateDetail->score1==3) echo ' checked ';
                                                @endif 
                                            @break
                                            @case(2)
                                                @if ($evaluateDetail->score2==3) echo ' checked ';
                                                @endif 
                                            @default
                                        @endswitch
                                        />
                                    </td>
                                    <td>
                                        <input type="radio" name="score_{{ $evaluateDetail->id }}" value="2" 
                                        @switch ($evaluate_seq_no)
                                            @case(1) 
                                                @if ($evaluateDetail->score1==2) echo ' checked ';
                                                @endif 
                                            @break
                                            @case(2)
                                                @if ($evaluateDetail->score2==2) echo ' checked ';
                                                @endif 
                                            @default
                                        @endswitch
                                        />
                                    </td>
                                    <td>
                                        <input type="radio" name="score_{{ $evaluateDetail->id }}" value="1" 
                                        @switch ($evaluate_seq_no)
                                            @case(1) 
                                                @if ($evaluateDetail->score1==1) echo ' checked ';
                                                @endif 
                                            @break
                                            @case(2)
                                                @if ($evaluateDetail->score2==1) echo ' checked ';
                                                @endif 
                                            @default
                                        @endswitch
                                        />
                                    </td>
                                    <td>
                                        <input type="radio" name="score_{{ $evaluateDetail->id }}" value="0" 
                                        @switch ($evaluate_seq_no)
                                            @case(1) 
                                                @if ($evaluateDetail->score1==0) echo ' checked ';
                                                @endif 
                                            @break
                                            @case(2)
                                                @if ($evaluateDetail->score2==0) echo ' checked ';
                                                @endif 
                                            @default
                                        @endswitch
                                        />
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="border-top"> 
                        @switch ($evaluate_seq_no)
                                @case(1) 
                                    @switch ($evaluate_headers->evaluator1_status)
                                        @case(2)    
                                            @break
                                        @default
                                            <button type="submit" id="submit1" class="btn btn-primary" value="บันทึก">บันทึก</button>&nbsp;
                                    @endswitch
                                @break
                                @case(2)
                                    @switch ($evaluate_headers->evaluator2_status)
                                        @case(2) 
                                            @break
                                        @default
                                            <button type="submit" id="submit1" class="btn btn-primary" value="บันทึก">บันทึก</button>&nbsp;
                                    @endswitch
                                @default
                            @endswitch   

                        <a href="{{ url('/evaluates/view-evaluate/'.$evaluate_headers->id.'/1') }}"  class="btn btn-primary">สรุปการประเมิน</a> 

                    </div>

                </div><!--card-body-->
                </form>
               
            </div><!--card-->            
        </div><!--col-md-12-->
    </div><!--row-->
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->

@endsection
<script>
$( document ).ready(function() {
    $('#sub1').on('click',function(){
         alert('1');
            return false;
     });

});
    //action="{{ url('/evaluates/save-evaluate') }}" 
    
</script>