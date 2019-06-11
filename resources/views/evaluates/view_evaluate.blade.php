@extends('layouts.adminLayout.admin_design')
@section('content')

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
     <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h3 class="page-title">แสดงการประเมินผลการทำงาน</h3>

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
            @if(Session::has('flash_message_error'))
                <div class="alert alert-danger alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button> 
                        <strong>{!! session('flash_message_error') !!}</strong>
                </div>
            @endif
            
            @if(Session::has('flash_message_success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button> 
                        <strong>{!! session('flash_message_success') !!}</strong>
                </div>
            @endif
            
            <div class="card">
                <form  id="form1" class="form-horizontal" method="post" action="{{ url('/evaluates/confirm-evaluate/'.$evaluate_headers->id) }}" 
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

                            ผู้ประเมิน : <strong>{{ $evaluator->person_full_name }} - {{ $evaluator->person_code }}</strong><br/>
                            {{ $evaluator->position_name }}

                        </div><!--col-md-4-->
                        <div class="col-md-2" style="text-align: center;;">                            
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
                        @foreach($topicGroupsMenu as $itm)
                        <a href="{{ url('/evaluates/view-evaluate/'.$evaluate_headers->id).'/'.$itm->id.'/'.$evaluator->id }}" class="btn btn-{{ ($itm->id == $topic_group->id ? 'success':'outline-success')}}" >{{ $itm->id.'. '.$itm->topic_group_name }} </a>&nbsp;
                        @endforeach
                    </div>    
                    <!--/.row-->                
                    
                    <div class="row table-responsive">
                        <!-- <h4 class="card-title">{{ $topic_group->id.' '.$topic_group->topic_group_name }}</h4> -->

                        <table class="table table-striped table-bordered table-reflow">
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 50px;">ลำดับ</th>
                                    <th>หัวข้อ</th>
                                    <th>คำอธิบายหัวข้อ</th>
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
                                        @switch ($evaluate_seq_no)
                                            @case(1) 
                                                @if ($evaluateDetail->score1==5) 
                                                <i class="mdi mdi-check-circle"></i>
                                                @endif 
                                                @break
                                            @case(2)
                                                @if ($evaluateDetail->score2==5) 
                                                <i class="mdi mdi-check-circle"></i>
                                                @endif 
                                                @break
                                            @default   
                                                <i class="mdi mdi-checkbox-blank-circle-outline"></i>
                                        @endswitch
                                    </td>
                                    <td>
                                        @switch ($evaluate_seq_no)
                                            @case(1) 
                                                @if ($evaluateDetail->score1==4) 
                                                <i class="mdi mdi-check-circle"></i>
                                                @endif 
                                                @break
                                            @case(2)
                                                @if ($evaluateDetail->score2==4) 
                                                <i class="mdi mdi-check-circle"></i>
                                                @endif 
                                                @break
                                            @default   
                                                <i class="mdi mdi-checkbox-blank-circle-outline"></i>
                                        @endswitch
                                    </td>
                                    <td>
                                        @switch ($evaluate_seq_no)
                                            @case(1) 
                                                @if ($evaluateDetail->score1==3) 
                                                <i class="mdi mdi-check-circle"></i>
                                                @endif 
                                                @break
                                            @case(2)
                                                @if ($evaluateDetail->score2==3) 
                                                <i class="mdi mdi-check-circle"></i>
                                                @endif 
                                                @break
                                            @default   
                                                <i class="mdi mdi-checkbox-blank-circle-outline"></i>
                                        @endswitch
                                    </td>
                                    <td>
                                        @switch ($evaluate_seq_no)
                                            @case(1) 
                                                @if ($evaluateDetail->score1==2) 
                                                <i class="mdi mdi-check-circle"></i>
                                                @endif 
                                                @break
                                            @case(2)
                                                @if ($evaluateDetail->score2==2) 
                                                <i class="mdi mdi-check-circle"></i>
                                                @endif 
                                                @break
                                            @default   
                                                <i class="mdi mdi-checkbox-blank-circle-outline"></i>
                                        @endswitch
                                    </td>
                                    <td>
                                        @switch ($evaluate_seq_no)
                                            @case(1) 
                                                @if ($evaluateDetail->score1==1) 
                                                <i class="mdi mdi-check-circle"></i>
                                                @endif 
                                                @break
                                            @case(2)
                                                @if ($evaluateDetail->score2==1) 
                                                <i class="mdi mdi-check-circle"></i>
                                                @endif 
                                                @break
                                            @default   
                                                <i class="mdi mdi-checkbox-blank-circle-outline"></i>
                                        @endswitch
                                    </td>
                                    <td>
                                        @switch ($evaluate_seq_no)
                                            @case(1) 
                                                @if ($evaluateDetail->score1==0) 
                                                <i class="mdi mdi-check-circle"></i>
                                                @endif 
                                                @break
                                            @case(2)
                                                @if ($evaluateDetail->score2==0) 
                                                <i class="mdi mdi-check-circle"></i>
                                                @endif 
                                                @break
                                            @default   
                                                <i class="mdi mdi-checkbox-blank-circle-outline"></i>
                                        @endswitch
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!--/.row-->

                    
                    @foreach($topicGroupsHeader as $key => $itm)
                        @switch($itm->id)
                            @case(4) 
                            <h3>{{ $itm->topic_group_name }}</h3>
                            <div class="row table-responsive">
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
                                    @foreach($topicsHeader as $key => $itm2)
                                    @if($itm2->topic_group_id==4)
                                    <tr class="gradeX">
                                        <td style="text-align: center; width: 50px;">{{ ($key+1) }}</td>
                                        <td><input type="hidden" name="ids[]" value="{{ $itm2->id }}" />{{ $itm2->topic_name }}</td>
                                        <td>{{ $itm2->topic_desc }}</td>
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
                                    @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!--/.row-->
                            @break
                            @case(5)
                                <h3>{{ $itm->topic_group_name }}</h3> 
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label for="comment1" class="text-right control-label col-form-label">จุดแข็งของผู้รับการประเมิน : </label>
                                            <div class="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <span>
                                                    @switch ($evaluate_seq_no)
                                                        @case(1) 
                                                            {{ $evaluate_headers->evaluator1_comment1 }}
                                                            @break
                                                        @case(2)
                                                            {{ $evaluate_headers->evaluator2_comment1 }}
                                                            @break
                                                        @default   
                                                    @endswitch
                                                </span>
                                            </div>
                                    </div>
                                    <!--/.col-sm-4-->

                                    <div class="col-sm-4">
                                        <label for="comment1" class="text-right control-label col-form-label">จุดอ่อนของผู้รับการประเมิน : </label>
                                            <div class="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <span>
                                                    @switch ($evaluate_seq_no)
                                                        @case(1) 
                                                            {{ $evaluate_headers->evaluator1_comment2 }}
                                                            @break
                                                        @case(2)
                                                            {{ $evaluate_headers->evaluator2_comment2 }}
                                                            @break
                                                        @default   
                                                    @endswitch
                                                </span>
                                            </div>
                                    </div>
                                    <!--/.col-sm-4-->

                                    <div class="col-sm-4">
                                        <label for="comment1" class="text-right control-label col-form-label">การส่งเสริมทักษะการทำงาน : </label>
                                            <div class="">
                                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    @switch ($evaluate_seq_no)
                                                        @case(1) 
                                                            {{ $evaluate_headers->evaluator1_comment3 }}
                                                            @break
                                                        @case(2)
                                                            {{ $evaluate_headers->evaluator2_comment3 }}
                                                            @break
                                                        @default   
                                                    @endswitch
                                                </span>
                                            </div>
                                    </div>
                                    <!--/.col-sm-4-->
                                </div>    
                                <!--/.row-->
                            @break
                            @defalut
                                <h1 style="color: red;">Error : Topic Group Header Default Item</h1>
                        @endswitch
                @endforeach                


                    


                    <div class="border-top">
                        @switch ($evaluate_seq_no)
                            @case(1) 
                                @if ($evaluate_headers->evaluator1_status==1) 
                                <button type="submit" id="submit1" class="btn btn-primary" value="บันทึก">ยืนยันผลการประเมิน</button>
                                @endif 
                                @if ($evaluate_headers->evaluator1_status==2) 
                                    @foreach(Session::get('userRoles') as $role)
                                        @if($role->user_role_group_id == 1)
                                            <a href="{{ url('/evaluates/reject-evaluate/'.$evaluate_headers->id.'/'.$evaluator->id) }}" name="btnEvaluateReject" class="btn btn-danger"> ยกเลิกผลการประเมินเพื่อแก้ไข</a>
                                        @endif
                                    @endforeach
                                @endif 
                            @break
                            @case(2)
                                @if ($evaluate_headers->evaluator2_status==1) 
                                <button type="submit" id="submit1" class="btn btn-danger" value="บันทึก">ยืนยันผลการประเมิน</button>
                                @endif 
                                @if ($evaluate_headers->evaluator2_status==2) 
                                    @foreach(Session::get('userRoles') as $role)
                                        @if($role->user_role_group_id == 1)
                                            <a href="{{ url('/evaluates/reject-evaluate/'.$evaluate_headers->id.'/'.$evaluator->id) }}" name="btnEvaluateReject" class="btn btn-danger"> ยกเลิกผลการประเมินเพื่อแก้ไข</a>
                                        @endif
                                    @endforeach
                                @endif 
                            @default
                        @endswitch   

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