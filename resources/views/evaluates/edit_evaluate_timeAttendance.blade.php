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
                <form  id="form1" class="form-horizontal" method="post" action="{{ url('/evaluates/save-evaluate-timeAttendance') }}" 
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
                        <div class="col-md-2">                            
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
                        <a href="{{ url('/evaluates/edit-evaluate-comments/'.$evaluate_headers->id).'/'.$topicGroup->id.'/'.$evaluator->id }}" class="btn btn-outline-success" > บันทึกความคิดเห็น </a>&nbsp;
                    </div>

                    
                    
                    <div class="row">
                        <!-- <h4 class="card-title">{{ $topic_group->id.' '.$topic_group->topic_group_name }}</h4> -->
                        
                        <table class="table table-striped table-bordered table-reflow">
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 50px;">ลำดับ</th>
                                    <th>หัวข้อ</th>
                                    <th>คำอธิบายหัวข้อ</th>
                                    <th style="text-align: center; width: 50px;">พ.ย.-เม.ย.</th>
                                    <th style="text-align: center; width: 50px;">พ.ค.-ต.ค.</th>
                                    <th style="text-align: center; width: 50px;">คะแนนหัก</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topics as $key => $topic)
                                <tr class="gradeX">
                                    <td style="text-align: center; width: 50px;">{{ ($key+1) }}</td>
                                    <td><input type="hidden" name="ids[]" value="{{ $topic->id }}" />{{ $topic->topic_name }}</td>
                                    <td>{{ $topic->topic_desc }}</td>
                                    <td style="text-align: center; width: 50px;">
                                        @switch($key+1)
                                            @case(1) {{ $evaluate_headers->sick_leave1_count }} @break
                                            @case(2) {{ $evaluate_headers->personal_leave1_count }} @break
                                            @case(3) {{ $evaluate_headers->late1_count }} @break
                                            @case(4) {{ $evaluate_headers->absence1_count }} @break
                                            @case(5) {{ $evaluate_headers->warning1_count }} @break
                                            @case(6) {{ $evaluate_headers->warning_latter1_count }} @break
                                            @case(7) {{ $evaluate_headers->suspended1_count }} @break
                                        @endswitch
                                    </td>
                                    <td style="text-align: center; width: 50px;">
                                        @switch($key+1)
                                            @case(1) {{ $evaluate_headers->sick_leave2_count }} @break
                                            @case(2) {{ $evaluate_headers->personal_leave2_count }} @break
                                            @case(3) {{ $evaluate_headers->late2_count }} @break
                                            @case(4) {{ $evaluate_headers->absence2_count }} @break
                                            @case(5) {{ $evaluate_headers->warning2_count }} @break
                                            @case(6) {{ $evaluate_headers->warning_latter2_count }} @break
                                            @case(7) {{ $evaluate_headers->suspended2_count }} @break
                                        @endswitch
                                    </td>
                                    <td style="text-align: center; width: 50px;">
                                        @switch($key+1)
                                            @case(1) {{ $evaluate_headers->sick_leave_score }} @break
                                            @case(2) {{ $evaluate_headers->personal_leave_score }} @break
                                            @case(3) {{ $evaluate_headers->late_score }} @break
                                            @case(4) {{ $evaluate_headers->absence_score }} @break
                                            @case(5) {{ $evaluate_headers->warning_score }} @break
                                            @case(6) {{ $evaluate_headers->warning_latter_score }} @break
                                            @case(7) {{ $evaluate_headers->suspended_score }} @break
                                        @endswitch
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
                                            <button type="submit" id="submit1" class="btn btn-primary" value="บันทึก">ถัดไป</button>&nbsp;
                                    @endswitch
                                @break
                                @case(2)
                                    @switch ($evaluate_headers->evaluator2_status)
                                        @case(2) 
                                            @break
                                        @default
                                            <button type="submit" id="submit1" class="btn btn-primary" value="บันทึก">ถัดไป</button>&nbsp;
                                    @endswitch
                                @default
                            @endswitch   

                        <a href="{{ url('/evaluates/view-evaluate/'.$evaluate_headers->id.'/1/'.$evaluator->id) }}"  class="btn btn-primary">สรุปการประเมิน</a> 

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