@extends('layouts.adminLayout.admin_design')
@section('content')

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
     <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h3 class="page-title">การขาดงาน</h3>
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">หน้าแรก</a></li>
                            <li class="breadcrumb-item" aria-current="page">รายการเวลาการทำงาน</li>

                            <li class="breadcrumb-item active" aria-current="page">เพิ่มการขาดงาน</li>
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
                <form class="" enctype="multipart/form-data" method="post" action="{{ url('/timeAttendances/add-absence') }}" novalidate="novalidate" >{{ csrf_field() }}

                    <input type="hidden" name="term_id" value="{{ $term->id }}" />

                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="cycle_no">ห้วงเวลาการประเมิน : </label></br>
                                    <span style="color: blue; font-weight: bold;">{{ $term->term_name }}</span>
                                </div>
                            </div><!--col-->

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="cycle_no">รอบ : </label>
                                    <select name="cycle_no" class="form-control">
                                        <option value="1">1 : พ.ย.-เม.ย.</option>
                                        <option value="2">2 : พ.ค.-ต.ค.</option>
                                    </select>
                                </div>
                            </div><!--col-->

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="upload_files">ไฟล์</label>
                                    <input type="file" name="upload_files" id="upload_files" class="form-control" />
                                </div>
                            </div><!--col-->
                        </div><!--row-->

                        <div class="border-top">
                            <div class="card-body">
                                <button type="submit" class="btn btn-primary">อัพโหลดไฟล์</button>
                            </div>
                        </div>
                    </div>
                    <!--/.card-body-->
                </form>


                <div class="card-body">
                    <h3>@if(isset($uploadHeaderDetails->file_name))
                            {{ $uploadHeaderDetails->file_name }}
                        @endif </h3>
                    <h3>@if(isset($uploadHeaderDetails->file_name2))
                            {{ $uploadHeaderDetails->file_name2 }}
                        @endif </h3>
                    <div class="table-responsive">
                        <table id="tbl_no_searching_paging_info" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">ลำดับ</th>
                                    <th style="text-align: center;">ID</th>
                                    <th style="text-align: center;">รหัส</th>
                                    <th style="text-align: center;">ชื่อ สกุล</th>
                                    <th style="text-align: center;">รหัสการลา</th>
                                    <th style="text-align: center;">ชื่อการลา</th>
                                    <th style="text-align: center;">ครั้ง</th>
                                    <th style="text-align: center;">จำนวน</th>
                                    <th style="text-align: center;">หมายเหตุ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $key => $itm)
                                <tr class="gradeX"
                                    @if($itm->employee_id==null)
                                        style="color: red;" 
                                    @endif 
                                >
                                    <td style="text-align: center;">{{ ($key+1) }}</td>
                                    <td style="text-align: center;">
                                        @if($itm->employee_id==null)
                                            เชื่อมโยงไม่ได้
                                        @else
                                            {{ $itm->employee_id }}
                                        @endif 
                                    </td>
                                    <td style="text-align: center;">{{ $itm->employee_code }}</td>
                                    <td style="text-align: center;">{{ $itm->employee_full_name }}</td>
                                    <td style="text-align: center;">{{ $itm->leave_code }}</td>
                                    <td style="text-align: center;">{{ $itm->leave_name }}</td>
                                    <td style="text-align: center;">{{ $itm->qty }}</td>
                                    <td style="text-align: center;">{{ $itm->total }}</td>
                                    <td style="text-align: center;">{{ $itm->remark }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!--/.table-responsive-->

                    @if($rowCount>0)
                    <h3>จำนวนแถวข้อมูล :{{ $rowCount }}; รวมยอดครั้ง : {{ $sumQty }}; รวมยอดจำนวน : {{ $sumTotal }}</h3>
                    <form class="" enctype="multipart/form-data" method="post" action="{{ url('/timeAttendances/use-absence') }}" novalidate="novalidate" >{{ csrf_field() }}

                        <input type="hidden" name="term_id" value="{{ $term->id }}" />
                        <input type="hidden" name="cycle_no" value="
                        @if(isset($uploadHeaderDetails->cycle_no)) 
                            {{ $uploadHeaderDetails->cycle_no }}
                        @endif" />
                        

                        <div class="border-top">
                            <div class="card-body">
                                <button type="submit" class="btn btn-primary">อัพโหลดข้อมูล</button>
                            </div>
                        </div>
                    </form>
                    @endif 

                    

                </div>
                <!--/.card-body-->
            </div>
            <!--/.card-->
            
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->

@endsection


