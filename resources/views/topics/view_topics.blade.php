@extends('layouts.adminLayout.admin_design')
@section('content')

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
     <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h3 class="page-title"> หัวข้อการประเมิน (กลุ่มระดับตำแหน่งงาน)</h3>
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"> หน้าแรก</a></li>
                            <li class="breadcrumb-item" aria-current="page"> รายการหัวข้อการประเมิน (กลุ่มระดับตำแหน่งงาน)</li>

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
                <div class="card-body">
                    <form id="form1" class="form-control" method="get" action="{{ url('/topics/view-topics') }}" novalidate="novalidate" >

                        <input type="hidden" name="isSubmit" value="1" />
<div class="row">
                        <div class="form-group col-sm-3">
                            <label for="topic_group_id" class="text-right control-label col-form-label">กลุ่มหัวข้อการประเมิน : </label>
                            <div class="">
                                <select name="topic_group_id" id="topic_group_id" class="form-control">
                                    @foreach($topicGroups as $val)
                                    <option value="{{ $val->id }}"
                                        @if($val->id == $topic_group_id) selected 
                                        @endif
                                        >{{ $val->topic_group_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!--/.col-sm-3-->

                        <div class="form-group col-sm-3">
                            <label for="topic_position_group_id" class="text-right control-label col-form-label">กลุ่มระดับตำแหน่งงาน : </label>
                            <div class="">
                                <select name="topic_position_group_id" id="topic_position_group_id" class="form-control">
                                    <option value=""> -- ทั้งหมด -- </option>
                                    @foreach($topicPositionGroups as $val)
                                    <option value="{{ $val->id }}"
                                        @if($val->id == $topic_position_group_id) selected 
                                        @endif
                                        >{{ $val->topic_position_group_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!--/.col-sm-3-->

                        <div class="form-group col-sm-3">
                            <label for="section_id" class="text-right control-label col-form-label">แผนก : </label>
                            <div class="">
                                <select name="section_id" id="section_id" class="form-control">
                                    <option value=""> -- ทั้งหมด -- </option>
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
                        
                        <div class="form-group col-sm-1">
                            <label for="submit" class="text-right control-label col-form-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                            <div class="">
                                <button type="submit" class="btn btn-primary"> ค้นหา</button>
                            </div>
                        </div>
                        <!--/.col-sm-3-->

                        <div class="form-group col-sm-2">
                        </div>
                        <!--/.col-sm-3-->
                    </div>
                    <!--/.row-->

                            
                      </form>
                </div>
                <!--card-body-->

                <div class="card-body">
                    <form id="frmTopicList" class="form-inline" method="post" action="{{ url('/topics/apply-topics-to-employees') }}" novalidate="novalidate" >{{ csrf_field() }}

                        <input type="hidden" name="topic_group_id" value="{{ $topic_group_id }}" />

                    <div class="table-responsive">
                        <table id="tbl_no_paging_info" class="table table-striped table-bordered">
                            <thead>
                                <tr><th style="text-align: center; width: 50px;">เลือก</th>
                                    <th style="text-align: center; width: 50px;">ลำดับ</th>
                                    <th style="text-align: center;">กลุ่มระดับตำแหน่งงาน</th>
                                    <th style="text-align: center;">หัวข้อการประเมิน</th>
                                    <th style="text-align: center;">KPI</th>
                                    <th style="text-align: center; width: 180px;">การปฏิบัติ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topics as $key => $topic)
                                <tr class="gradeX">
                                    <td style="text-align: center;"><input type="checkbox" name="checkedTopicIds[]" value="{{ $topic->id }}"/></td>

                                    <td style="text-align: center;"><input type="text" name="checkedSeqNos[]" class="form-control" style="text-align: center; width: 50px;" style="text-align: right;" disabled="" value="0"/></td>
                                    <td style="text-align: center;">{{ $topic->topic_position_group_name }}</td>
                                    <td style="text-align: center;">{{ $topic->topic_name }}</td>
                                    <td style="text-align: center;">{{ $topic->topic_desc }}</td>
                                    <td style="text-align: center;">
                                        <a href="{{ url('/topics/edit-topic/'.$topic->id) }}" class="btn btn-primary btn-mini"><i class="mdi mdi-table-edit"></i> แก้ไข</a> 

                                        <a href="{{ url('/topics/delete-topic/'.$topic->id) }}" class="btn btn-danger btn-mini btn-deleteMaster"><i class="mdi mdi-delete-forever"></i> ลบ</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="border-top">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary"> นำหัวข้อการประเมินที่เลือกไปใช้กับกลุ่มพนักงาน</button>
                        </div>
                    </div>

                </div>
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