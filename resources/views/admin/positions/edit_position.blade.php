@extends('layouts.adminLayout.admin_design')
@section('content')

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
     <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h3 class="page-title">Positions</h3>
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item" aria-current="page">Positions</li>

                            <li class="breadcrumb-item active" aria-current="page">Edit Position</li>
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
                <form class="form-horizontal" method="post" action="{{ url('/admin/edit-category/'.$categoryDetails->id) }}" novalidate="novalidate" >{{ csrf_field() }}
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="position_name" class="col-sm-3 text-right control-label col-form-label">Position Name (ไทย)</label>
                            <div class="col-sm-9">
                                <input type="text" name="position_name" id="position_name" class="form-control" placeholder="ชื่อตำแหน่ง" value="{{ $positionDetails->position_name }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="position_name_eng" class="col-sm-3 text-right control-label col-form-label">Position Name (ไทย)</label>
                            <div class="col-sm-9">
                                <input type="text" name="position_name_eng" id="position_name_eng" class="form-control" placeholder="Position name (english)" value="{{ $positionDetails->position_name_eng }}" >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-sm-3 text-right control-label col-form-label">Position Name</label>
                            <div class="col-sm-9">
                                <input type="text" name="name" id="name" class="form-control" placeholder=""   >
                            </div>
                        </div>
                        
                    </div>
                    <div class="border-top">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary">Edit Position</button>
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