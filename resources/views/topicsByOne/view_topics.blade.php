@extends('layouts.adminLayout.admin_design')
@section('content')

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
     <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h3 class="page-title">หัวข้อการประเมิน (ตำแหน่ง/รายบุคคล)</h3>
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">หน้าแรก</a></li>

                            <li class="breadcrumb-item active" aria-current="page">รายการหัวข้อการประเมิน (ตำแหน่ง/รายบุคคล)</li>
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
                    <form id="form1" class="form-inline" method="get" action="{{ url('/topics/view-topics-by-one') }}" novalidate="novalidate" >

                        <input type="hidden" name="isSubmit" value="1" />

                    <div class="row">
                        <div class="form-group col-sm-3">
                            <label for="topic_group_id" class="text-right control-label col-form-label">กลุ่มหัวข้อการประเมิน : </label>
                            <select name="topic_group_id" id="topic_group_id" class="form-control">
                                @foreach($topicGroups as $val)
                                <option value="{{ $val->id }}"
                                    @if($val->id == $topic_group_id) selected 
                                    @endif
                                    >{{ $val->topic_group_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!--/.col-sm-3-->

                        <div class="form-group col-sm-8">
                            <div class="row col-sm-12">
                                <label for="employee_id" class="text-right control-label col-form-label">พนักงาน/ตำแหน่งงาน : </label>
                            </div>
                            <div class="row col-sm-12">
                                <input type="hidden" name="employee_id" value=@if(!empty($employeeDetails)) 
                                    "{{ $employeeDetails->id }}"
                                @else
                                    "" 
                                @endif
                                />

                                <a href="#" name="searchEmployeeCode" data-id="" class="btn btn-outline-primary">
                                @if(!empty($employeeDetails)) 
                                    {{ $employeeDetails->person_full_name }}
                                @else
                                    ค้นหา
                                @endif 
                                </a>
                                <a href="#" name="searchEmployeeCodeRemove" data-id="" class="btn btn-outline-primary"> ลบ</a>
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
                                <tr><th style="text-align: center;">เลือก</th>
                                    <th style="text-align: center;">ลำดับ</th>
                                    <th style="text-align: center;">พนักงาน/ตำแหน่ง</th>
                                    <th style="text-align: center;">หัวข้อการประเมิน</th>
                                    <th style="text-align: center;">KPI</th>
                                    <th style="text-align: center; width: 180px;">การปฏิบัติ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topics as $key => $topic)
                                <tr class="gradeX">
                                    <td style="text-align: center;"><input type="checkbox" name="checkedTopicIds[]" value="{{ $topic->id }}"</td>
                                    <td style="text-align: center;"><input type="text" name="checkedSeqNos[]" class="form-control" style="text-align: center; width: 50px;" disabled value="0"/></td>
                                    <td style="text-align: center;">{{ $topic->person_full_name }}</td>
                                    <td style="text-align: center;">{{ $topic->topic_name }}</td>
                                    <td style="text-align: center;">{{ $topic->topic_desc }}</td>
                                    <td style="text-align: center;">
                                        <a href="{{ url('/topics/edit-topic-by-one/'.$topic->id) }}" class="btn btn-primary btn-mini"> แก้ไข</a> 

                                        <a href="{{ url('/topics/delete-topic-by-one/'.$topic->id) }}" class="btn btn-danger btn-mini btn-deleteMaster"> ลบ</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!--/.table-resosive-->
                    <div class="border-top">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary"> นำหัวข้อการประเมินที่เลือกไปใช้กับพนักงาน</button>
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



<!-- The Modal -->
<div class="modal" id="modalSearchEmployee">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">ค้นหาพนักงาน</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <input type="hidden" name="refId" value="" />

        ชื่อ นามสกุล : <input type="text" name="searchWord" class="form-control" />
        <table class="table">
            <thead>
                <td style="text-align: center; font-weight: bold;">เลือก</td>
                <td style="text-align: center; font-weight: bold;">ชื่อ นามสกุล</td>
                <td style="text-align: center; font-weight: bold;">ตำแหน่ง</td>
            </thead>
            <tbody>                
            </tbody>
        </table>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

@endsection