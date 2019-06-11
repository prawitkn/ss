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
                            <li class="breadcrumb-item" aria-current="page">รายการพนักงาน</li>

                            <li class="breadcrumb-item active" aria-current="page">เพิ่มพนักงาน</li>
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
                <form class="" enctype="multipart/form-data" method="post" action="{{ url('/employees/add-employee') }}" novalidate="novalidate" >{{ csrf_field() }}
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="person_code">รหัสพนักงาน</label>
                                    <input id="person_code" type="text" class="form-control" name="person_code" data-smk-msg="Require." required>
                                </div>
                            </div><!--col-->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="date_of_work">วันที่เริ่มงาน</label>
                                    <input id="date_of_work" type="text" class="form-control" name="date_of_work" data-smk-msg="Require." required>
                                </div>
                            </div><!--col-->
                            <div class="col-md-2">
                                <div id="images_preview" class="form-group"></div>
                            </div><!--col-->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="upload_images">ภาพถ่าย</label>
                                    <input type="file" name="upload_images" id="upload_images" class="form-control" />
                                </div>
                            </div><!--col-->
                            <div class="col-md-2">
                                
                            </div><!--col-->
                        </div><!--row-->

                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="person_title">คำนำหน้า</label>
                                    <input id="person_title" type="text" class="form-control" name="person_title" data-smk-msg="Require." required>
                                </div>
                            </div><!--col-->
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="person_name">ชื่อ</label>
                                    <input id="person_name" type="text" class="form-control" name="person_name" data-smk-msg="Require." required>
                                </div>
                            </div><!--col-->
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="person_surname">นามสกุล</label>
                                    <input id="person_surname" type="text" class="form-control" name="person_surname" data-smk-msg="Require." required>
                                </div>
                            </div><!--col-->
                        </div><!--row-->

                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="person_title_en">Title</label>
                                    <input id="person_title_en" type="text" class="form-control" name="person_title_en" data-smk-msg="Require." required>
                                </div>
                            </div><!--col-->
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="person_name_en">Name</label>
                                    <input id="person_name_en" type="text" class="form-control" name="person_name_en" data-smk-msg="Require." required>
                                </div>
                            </div><!--col-->
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="person_surname_en">Surname</label>
                                    <input id="person_surname_en" type="text" class="form-control" name="person_surname_en" data-smk-msg="Require." required>
                                </div>
                            </div><!--col-->
                        </div><!--row-->

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="position_name">ตำแหน่ง</label>
                                    <input id="position_name" type="text" class="form-control" name="position_name" data-smk-msg="Require." required>
                                </div>
                            </div><!--col-->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="position_rank_id">ระดับ</label>
                                    <select name="position_rank_id" id="position_rank_id" class="form-control">
                                        @foreach($positionRanks as $val)
                                        <option value="{{ $val->id }}">{{ $val->position_rank_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div><!--col-->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="section_id">ส่วน</label>
                                    <select name="section_id" id="section_id" class="form-control">
                                        @foreach($sections as $val)
                                        <option value="{{ $val->id }}">{{ $val->section_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div><!--col-->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="department_id">ฝ่าย</label>
                                    <select name="department_id" id="department_id" class="form-control">
                                        @foreach($departments as $val)
                                        <option value="{{ $val->id }}">{{ $val->department_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div><!--col-->
                        </div><!--row-->


                        <div class="border-top">
                            <div class="card-body">
                                <button type="submit" class="btn btn-primary">เพิ่มพนักงาน</button>
                            </div>
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


