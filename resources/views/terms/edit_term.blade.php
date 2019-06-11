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

                            <li class="breadcrumb-item active" aria-current="page">แก้ไขห้วงการประเมิน</li>
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
                <form class="form-horizontal" method="post" action="{{ url('/terms/edit-term/'.$termDetails->id) }}" novalidate="novalidate" >{{ csrf_field() }}
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="term_name" class="col-sm-3 text-right control-label col-form-label">ชื่อห้วงการประเมิน</label>
                            <div class="col-sm-6">
                                <input type="text" name="term_name" id="term_name" class="form-control" placeholder="Section Name Here" value="{{ $termDetails->term_name }}">
                            </div>
                        </div>
                        
                    </div>
                    <div class="border-top">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary">แก้ไขห้วงการประเมิน</button>
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