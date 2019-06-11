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

                            <li class="breadcrumb-item active" aria-current="page">View Categories</li>
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
                    <h5 class="card-title">Basic Datatable</h5>
                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Person ID</th>
                                    <th>Person Fullname</th>
                                    <th>Date of Work</th>
                                    <th>Position</th>
                                    <th>Rank</th>
                                    <th>Section</th>
                                    <th>Department</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($persons as $person)
                                <tr class="gradeX">
                                    <td>{{ $person->id }}</td>
                                    <td>{{ $person->code }}</td>
                                    <td>{{ $person->person_full_name }}</td>
                                    <td>{{ $person->date_of_work }}</td>
                                    <td>{{ $person->position_name }}</td>
                                    <td>{{ $person->positon_rank_id }}</td>
                                    <td>{{ $person->section_id }}</td>
                                    <td>{{ $person->department_id }}</td>
                                    <td class="center"><a href="{{ url('/persons/edit-person/'.$person->id) }}" class="btn btn-primary btn-mini">Edit</a> <a href="{{ url('/persons/delete-person/'.$person->id) }}" class="btn btn-danger btn-mini btn-deleteCategory">Delete</a></td>
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