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

                            <li class="breadcrumb-item active" aria-current="page">เพิ่มกลุ่มการตัดเกรด</li>
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
        	<div class="card">
                <form class="form-horizontal" method="post" action="{{ url('/gradingGroups/add-gradingGroup') }}" novalidate="novalidate" >{{ csrf_field() }}
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="seq_no" class="col-sm-3 text-right control-label col-form-label">ลำดับ</label>
                            <div class="col-sm-3">
                                <input type="text" name="seq_no" id="seq_no" class="form-control" placeholder="...">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="grading_group_name" class="col-sm-3 text-right control-label col-form-label">ชื่อกลุ่มการตัดเกรด</label>
                            <div class="col-sm-6">
                                <input type="text" name="grading_group_name" id="grading_group_name" class="form-control" placeholder="...">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="grading_group_desc" class="col-sm-3 text-right control-label col-form-label">คำอธิบายกลุ่มการตัดเกรด</label>
                            <div class="col-sm-6">
                                <input type="text" name="grading_group_desc" id="grading_group_desc" class="form-control" placeholder="...">
                            </div>
                        </div>
                        
                    <div class="border-top">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary">เพิ่มกลุ่มการตัดเกรด</button>
                        </div>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->

@endsection