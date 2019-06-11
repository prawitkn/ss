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

                  @foreach($topics as $topic)
                        {{ $topic->seq_no.'. '.$topic->topic_name }} </br> 
                    @endforeach
                </div>
                <!--col-sm-4-->


                <div class="col-sm-7">
                    <h3>รายชื่อพนักงาน</h3>

                    @foreach($employees as $indexKey => $employee)
                    {{ ($indexKey+1).'. '. $employee->person_full_name }}<br/>
                    @endforeach

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