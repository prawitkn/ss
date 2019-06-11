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

                            <li class="breadcrumb-item active" aria-current="page"> แก้ไขหัวข้อการประเมิน (กลุ่มระดับตำแหน่งงาน)</li>
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
                <form class="form-horizontal" method="post" action="{{ url('/topics/edit-topic/'.$topicDetails->id) }}" novalidate="novalidate" >{{ csrf_field() }}
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="topic_group_id" class="col-sm-3 text-right control-label col-form-label">กลุ่มหัวข้อการประเมิน</label>
                            <div class="col-sm-3">
                                <select name="topic_group_id" id="topic_group_id" class="form-control">
                                @foreach($topicGroups as $val)
                                <option value="{{ $val->id }}"
                                    @if($val->id == $topicDetails->topic_group_id) selected @endif
                                    >{{ $val->topic_group_name }}</option>
                                @endforeach
                            </select>
                            </div>                            
                        </div>

                        <div class="form-group row">
                            <label for="topic_position_group_id" class="col-sm-3 text-right control-label col-form-label">กลุ่มระดับตำแหน่งงาน</label>
                            <div class="col-sm-3">
                                <select name="topic_position_group_id" id="topic_position_group_id" class="form-control">
                                @foreach($topicPositionGroups as $val)
                                <option value="{{ $val->id }}" 
                                    @if($val->id == $topicDetails->topic_position_group_id) selected @endif
                                    >{{ $val->topic_position_group_name }}</option>
                                @endforeach
                            </select>
                            </div>                            
                        </div>

                        <div class="form-group row">
                            <label for="section_id" class="col-sm-3 text-right control-label col-form-label">แผนก : </label>
                            <div class="col-sm-3">
                                <select name="section_id" id="section_id" class="form-control">
                                    <option value=""> -- ไม่กำหนด -- </option>
                                    @foreach($sections as $val)
                                    <option value="{{ $val->id }}"
                                        @if($val->id == $topicDetails->section_id) selected 
                                        @endif
                                        >{{ $val->section_name }}</option>
                                    @endforeach
                                </select>
                            </div>                           
                        </div>

                        <div class="form-group row">
                            <label for="topic_desc" class="col-sm-3 text-right control-label col-form-label">หัวข้อการประเมิน</label>
                            <div class="col-sm-6">
                                <textarea name="topic_name" id="topic_name" class="form-control" placeholder="Topic Here"> {{ $topicDetails->topic_name }}</textarea>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="topic_desc" class="col-sm-3 text-right control-label col-form-label">เป้าหมายที่ใช้ประเมิน (KPI)</label>
                            <div class="col-sm-6">
                                <textarea name="topic_desc" id="topic_desc" class="form-control" placeholder="Topic Description Here">{{ $topicDetails->topic_desc }}</textarea>
                            </div>
                        </div>
                        
                    </div>
                    <div class="border-top">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary"> แก้ไขหัวข้อการประเมิน (กลุ่มระดับตำแหน่งงาน)</button>
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