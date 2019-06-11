@extends('layouts.adminLayout.admin_design')
@section('content')

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
     <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h3 class="page-title"> นำกลุ่มหัวข้อการประเมิน ไปใช้กับ กลุ่มพนักงาน</h3>
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"> หน้าแรก</a></li>
                            <li class="breadcrumb-item" aria-current="page"> นำกลุ่มหัวข้อการประเมิน ไปใช้กับ กลุ่มพนักงาน</li>

                        </ol>
                    </nav>
                </div>
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
        <div class="col-12">
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
                <div class="card-body row">

                <div class="col-sm-5">
                    <h3>{{ $topicGroupDetail->topic_group_name }}</h3>

                  <!-- @foreach($topics as $indexKey => $topic)
                    {{ ($indexKey+1).'. '. $topic->topic_name }}<br/>
                    @endforeach -->
                    @foreach($topics as $topic)
                        {{ $topic->seq_no.'. '.$topic->topic_name }} </br> 
                    @endforeach
                </div>
                <!--col-sm-4-->


                <div class="col-sm-7">
                    <h3>รายชื่อพนักงาน</h3>

                    <form id="form1" class="form-control" method="post" action="{{ url('/topics/apply-topics-to-employees') }}" novalidate="novalidate" >
                        
                            <input type="hidden" name="isSubmit" value="1" />

                        <div class="row">    
                            <div class="col-sm-3">
                                <label for="grading_group_id" class="text-right control-label col-form-label">กลุ่มการตัดเกรด : </label>
                                <div class="">
                                    <select name="grading_group_id" id="grading_group_id" class="form-control">
                                        <option value=""></option>
                                        @foreach($gradingGroups as $val)
                                        <option value="{{ $val->id }}"
                                            @if($val->id == $grading_group_id) selected 
                                            @endif
                                            >{{ $val->grading_group_name.' - '.$val->grading_group_desc }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!--/.col-sm-3-->

                            <div class="col-sm-3">
                                <label for="position_rank_id" class="text-right control-label col-form-label">ระดับตำแหน่ง : </label>
                                <div class="">
                                    <select name="position_rank_id" id="position_rank_id" class="form-control">
                                        <option value=""></option>
                                        @foreach($positionRanks as $val)
                                        <option value="{{ $val->id }}"
                                            @if($val->id == $position_rank_id) selected 
                                            @endif
                                            >{{ $val->position_rank_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!--/.col-sm-3-->

                            <div class="col-sm-3">
                                <label for="section_id" class="text-right control-label col-form-label">ส่วน : </label>
                                <div class="">
                                    <select name="section_id" id="section_id" class="form-control">
                                        <option value=""></option>
                                        @foreach($sections as $val)
                                        <option value="{{ $val->id }}"
                                            @if($val->id == $section_id) selected 
                                            @endif
                                            >{{ $val->section_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!--/.col-sm-3-->

                            <div class="col-sm-3">
                                <label for="department_id" class="text-right control-label col-form-label">   ฝ่าย : </label>
                                <div class="">
                                    <select name="department_id" id="department_id" class="form-control">
                                        <option value=""></option>
                                        @foreach($departments as $val)
                                        <option value="{{ $val->id }}"
                                            @if($val->id == $department_id) selected 
                                            @endif
                                            >{{ $val->department_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!--/.col-sm-3-->
                        </div>
                        <!--/.row-->

                        <div class="row col-sm-12">
                                <a href="#" name="btnApplyTopicListEmployeeSearch" class="btn btn-outline-primary"> ค้นหา</a>
                        </div>
                        <!--/.row-->

                          </form>
                          <!--/.form1-->

                        <form id="frmApplyTopicsToEmployees" class="form-control" method="post" action="{{ url('/topics/save-topics-to-employees') }}" novalidate="novalidate" >{{ csrf_field() }}

                            <input type="hidden" name="apply_type_name" value="" />

                            <input type="hidden" name="topic_group_id" value="{{ $topicGroupDetail->id }}" />
                            @foreach($checkedTopicArr as $val)
                            <input type="hidden" name="checkedTopicIds[]" value="{{ $val }}" />
                            @endforeach

                          <div class="table-responsive">
                                <table id="table1" name="tblApplyEmployees" class="table table-striped table-bordered tbl_no_searching_paging_info">
                                    <thead>
                                        <tr><th style="text-align: center;">เลือก</th>
                                            <th style="text-align: center;">ชื่อ นามสกุล</th>
                                            <th style="text-align: center;">ตำแหน่งงาน</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!--/.Ajax result-->
                                    </tbody>
                                </table>
                            </div>

                    <a href="#" name="btnApplyTopicListEmployeeSubmitReNew" class="btn btn-outline-primary"> บันทึกเพิ่มหัวข้อการประเมินใหม่ โดย ลบ หัวข้อการประเมินเดิมที่มี</a>

                            <a href="#" name="btnApplyTopicListEmployeeSubmitAppend" class="btn btn-outline-primary"> บันทึกเพิ่มหัวข้อการประเมินใหม่ โดย ไม่ลบ หัวข้อการประเมินเดิมที่มี</a>
                    </form>
                    <!--/.form2-->
                </div>
                <!--/.col-sm-6-->
                </div>
                <!--/.card-body-->
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->

@endsection