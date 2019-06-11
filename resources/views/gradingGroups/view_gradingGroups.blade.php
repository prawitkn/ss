@extends('layouts.adminLayout.admin_design')
@section('content')

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
     <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h3 class="page-title">กลุ่มการตัดเกรด</h3>
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">หน้าแรก</a></li>
                            <li class="breadcrumb-item" aria-current="page">รายการกลุ่มการตัดเกรด</li>

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
                    <form id="form2" class="form-inline" method="post" action="{{ url('/admin/edit-position_ranks') }}" novalidate="novalidate" >{{ csrf_field() }}

                    <div class="table-responsive">
                        <table id="tbl_no_paging_info" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 50px;">ลำดับ</th>
                                    <th style="text-align: center;">ชื่อกลุ่มการตัดเกรด</th>
                                    <th style="text-align: center;">คำอธิบายกลุ่มการตัดเกรด</th>
                                    <th style="text-align: center; width: 50px;">สถานะ</th>
                                    <th style="text-align: center; width: 180px;">การปฏิบัติ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($gradingGroups as $gradingGroup)
                                <tr class="gradeX">
                                    <td style="text-align: center; width: 50px;">
                                        <input type="hidden" name="ids[]" value="{{ $gradingGroup->id }}" />

                                        <input type="text" name="seq_nos[]" class="form-control" style="text-align: center; width: 50px;" value="{{ $gradingGroup->seq_no }}" />
                                    </td>
                                    <td style="text-align: center;">{{ $gradingGroup->grading_group_name }}</td>
                                    <td style="text-align: center;">{{ $gradingGroup->grading_group_desc }}</td>
                                    <td style="text-align: center; width: 50px;">
                                        @if($gradingGroup->status==1)
                                            <a href="{{ url('/gradingGroups/set-active/'.$gradingGroup->id.'/'.$gradingGroup->status) }}"><span class="badge badge-success">ใช้งาน</span>
                                            </a>
                                        @else 
                                            <a href="{{ url('/gradingGroups/set-active/'.$gradingGroup->id.'/'.$gradingGroup->status) }}"><span class="badge badge-danger">ไม่ใช้งาน</span></a>
                                        @endif   
                                    </td>
                                    <td style="text-align: center;">
                                        <a href="{{ url('/gradingGroups/edit-gradingGroup/'.$gradingGroup->id) }}" class="btn btn-primary btn-mini"><i class="mdi mdi-table-edit"></i> แก้ไข</a> 

                                        <a href="{{ url('/gradingGroups/delete-gradingGroup/'.$gradingGroup->id) }}" class="btn btn-danger btn-mini btn-deleteMaster"><i class="mdi mdi-delete-forever"></i> ลบ</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!--/.table-responsive-->

                    <div class="border-top">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary"><i class="mdi mdi-content-save"></i> บันทึกข้อมูลในตาราง</button>
                        </div>
                    </div>
                    <!--/.border-top-->
                    </form>
                </div>
                <!--/.card-body-->
            </div>
            <!--/.card-->
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