@extends('layouts.adminLayout.admin_design')
@section('content')

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
     <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h3 class="page-title">ห้วงการประเมิน</h3>
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin/index') }}">หน้าแรก</a></li>
                            <li class="breadcrumb-item" aria-current="page">ห้วงการประเมิน</li>

                            <li class="breadcrumb-item active" aria-current="page">รายการห้วงการประเมิน</li>
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
                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">รหัส</th>
                                    <th style="text-align: center;">ห้วงการประเมิน</th>
                                    <th style="text-align: center;">สถานะ</th>
                                    <th style="text-align: center;">ห้วงเวลาประเมิน</th>
                                    <th style="text-align: center;">การปฏิบัติ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($terms as $term)
                                <tr class="gradeX">
                                    <td style="text-align: center;">{{ $term->id }}</td>
                                    <td style="text-align: center;">{{ $term->term_name }}</td>
                                    <td style="text-align: center;">
                                        @switch($term->status)
                                            @case(0) 
                                                <span class="badge badge-pill badge-danger">ยกเลิกใช้งาน</span>
                                                @break
                                            @case(1) 
                                                <span class="badge badge-pill badge-info">เปิดใช้งาน</span>
                                                @break
                                            @case(2) 
                                                <span class="badge badge-pill badge-secondary">ปิดการใช้งาน</span>
                                                @break
                                            @case(3)
                                                <span class="badge badge-pill badge-secondary">จบขั้นตอน</span>
                                                @break
                                            @default 
                                                <a href="" class="btn btn-secondary btn-mini">N/A</a>
                                        @endswitch
                                    </td>
                                    <td style="text-align: center;">
                                        @switch($term->status)
                                            @case(1) 
                                                @if($term->current == 1)
                                                    <a href="#" class="btn btn-info btn-mini">ปัจจุบัน</a> 
                                                @else
                                                    <a href="{{ url('/terms/set-current/'.$term->id) }}" class="btn btn-primary btn-mini">ไม่ใช่ปัจจุบัน</a>
                                                @endif
                                                @break
                                            @case(3)
                                                @if($term->current == 1)
                                                    <a href="#" class="btn btn-info btn-mini">ปัจจุบัน</a> 
                                                @else
                                                    <a href="#" class="btn btn-secondary btn-mini">จบขั้นตอน</a>
                                                @endif
                                                @break
                                            @default 
                                                <a href="#" class="btn btn-primary btn-mini">ไม่ใช่ปัจจุบัน</a> 
                                        @endswitch
                                    </td>
                                    <td style="text-align: center;">
                                        @if($term->current == 1)
                                            <a href="{{ url('/terms/create-new-data') }}" class="btn btn-success btn-mini">สร้าง/ปรับปรุง หัวข้อการประเมินจากฐานข้อมูลหลัก</a> 
                                        @endif

                                        @switch($term->status)
                                            @case(1) 
                                                <a href="{{ url('/terms/edit-term/'.$term->id) }}" class="btn btn-primary btn-mini">แก้ไข</a> 

                                                <a href="{{ url('/terms/delete-term/'.$term->id) }}" class="btn btn-danger btn-mini btn-deleteMaster">ลบ</a>

                                                @if($term->current == 1)
                                                    <a href="{{ url('/terms/close-term/'.$term->id) }}" class="btn btn-danger btn-mini btn-closeTerm">สิ้นสุดการประเมิน</a>
                                                @endif
                                                
                                                @break
                                            @case(3)
                                                <a href="{{ url('/terms/create-copy-data/'.$term->id) }}" class="btn btn-success btn-mini">สร้าง/ปรับปรุง หัวข้อการประเมินจากการสำเนาห้วงประเมินก่อนหน้า</a> 
                                                @break
                                            @default 
                                        @endswitch
                                    </td>
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