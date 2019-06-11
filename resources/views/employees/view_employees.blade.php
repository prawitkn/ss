@extends('layouts.adminLayout.admin_design')
@section('content')

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
     <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h3 class="page-title">พนักงาน</h3>
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">หน้าแรก</a></li>

                            <li class="breadcrumb-item active" aria-current="page">รายการพนักงาน</li>
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
                    <form id="form1" class="form-inline" method="get" action="{{ url('/employees/view-employees') }}" novalidate="novalidate" >

                     <div class="row">
                        <div class="form-group col-sm-4">
                            <label for="section_name" class="text-right control-label col-form-label">ส่วน : </label>
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
                        <!--/.col-sm-3-->

                        <div class="form-group col-sm-4">
                            <label for="section_name" class="text-right control-label col-form-label">   ฝ่าย : </label>
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
                        <!--/.col-sm-3-->

                        <div class="form-group col-sm-1">
                            <label for="submit" class="text-right control-label col-form-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                            <div class="">
                                <button type="submit" class="btn btn-primary"> ค้นหา</button>
                            </div>
                        </div>
                        <!--/.col-sm-3-->

                        <div class="form-group col-sm-3">
                            
                        </div>
                        <!--/.col-sm-3-->
                    </div>
                    <!--/.row-->
                            
                      </form>
                </div>
                <!--card-body-->

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 50px;">ลำดับ</th>
                                    <th style="text-align: center;">รหัส</th>
                                    <th style="text-align: center;">ภาพถ่าย</th>
                                    <th style="text-align: center;">ชื่อ นามสกุล</th>
                                    <th style="text-align: center;">ตำแหน่ง</th>
                                    <th style="text-align: center;">ระดับตำแหน่ง</th>
                                    <th style="text-align: center;">ส่วน</th>
                                    <th style="text-align: center;">ฝ่าย</th>
                                    <th style="text-align: center;">สถานะ</th>
                                    <th style="text-align: center; width: 180px;">การปฏิบัติ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employees as $key => $employee)
                                <tr class="gradeX">
                                    <td style="text-align: center;">{{ ($key+1) }}</td>
                                    <td style="text-align: center;">{{ $employee->person_code }}</td>
                                    <td style="text-align: center;"><img style="width: 40px;" src="{{ asset('/assets/images/employees/small/'.$employee->image) }}" /></td>
                                    <td style="text-align: center;">{{ $employee->person_full_name }}</td>
                                    <td style="text-align: center;">{{ $employee->position_name }}</td>
                                    <td style="text-align: center;">{{ $employee->position_rank_name }}</td>
                                    <td style="text-align: center;">{{ $employee->section_name }}</td>
                                    <td style="text-align: center;">{{ $employee->department_name }}</td>
                                    <td style="text-align: center;">
                                        @if($employee->status==1)
                                            <a href="{{ url('/employees/set-active-employee/'.$employee->id.'/'.$employee->status) }}"><span class="badge badge-success">ใช้งาน</span>
                                            </a>
                                        @else 
                                            <a href="{{ url('/employees/set-active-employee/'.$employee->id.'/'.$employee->status) }}"><span class="badge badge-danger">ไม่ใช้งาน</span></a>
                                        @endif      
                                    <td style="text-align: center;">
                                        <a href="{{ url('/employees/edit-employee/'.$employee->id) }}" class="btn btn-primary btn-mini">แกไข</a> 

                                        <a href="{{ url('/employees/delete-employee/'.$employee->id) }}" class="btn btn-danger btn-mini btn-deleteMaster">ลบ</a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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