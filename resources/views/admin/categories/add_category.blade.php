@extends('layouts.adminLayout.admin_design')
@section('content')

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
     <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h3 class="page-title">Categories</h3>
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item" aria-current="page">Categories</li>

                            <li class="breadcrumb-item active" aria-current="page">Add Category</li>
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
                <form class="form-horizontal" method="post" action="{{ url('/admin/add-category') }}" novalidate="novalidate" >{{ csrf_field() }}
                    <div class="card-body">
                        <!-- <h4 class="card-title">Category</h4> -->
                        <div class="form-group row">
                            <label for="name" class="col-sm-3 text-right control-label col-form-label">Category Name</label>
                            <div class="col-sm-9">
                                <input type="text" name="name" id="name" class="form-control" placeholder="First Name Here">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="parent_id" class="col-sm-3 text-right control-label col-form-label">Category Levels</label>
                            <div class="col-sm-9">
                                <select name="parent_id" id="parent_id" class="form-control">
                                    @foreach($levels as $val)
                                    <option value="{{ $val->id }}">{{ $val->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="description" class="col-sm-3 text-right control-label col-form-label">Description</label>
                            <div class="col-sm-9">
                            	<textarea class="form-control" name="description" id="description" ></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="url" class="col-sm-3 text-right control-label col-form-label">URL</label>
                            <div class="col-sm-9">
                                <input type="text" name="url" id="url" class="form-control" placeholder="Password Here">
                            </div>
                        </div>
                    </div>
                    <div class="border-top">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary">Add Category</button>
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