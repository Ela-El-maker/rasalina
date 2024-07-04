@extends('admin.admin_master')

@section('admin')

    <div class="page-content">
        <div class="container-fluid">


            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title">Change Password Page</h4><br><br>


                            @if (count($errors))
                                @foreach ($errors->all() as  $error)
                                    <p class="alert alert-danger alert-dismissable fade show">{{$error}}</p>
                                @endforeach
                            @endif
                            <form action="{{route('update.password')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Old Password</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" name="old_password" type="password"
                                            value="" id="old_password">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">New Password</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" name="new_password" type="password"
                                            value="" id="new_password">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Confirm New Password</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" name="confirm_password" type="password"
                                            value="" id="confirm_password">
                                    </div>
                                </div>


                                <!-- end row -->
                                <input type="submit" class="btn btn-info btn-rounded waves-effect waves-light"
                                    value="Change Password">
                            </form>

                        </div>
                    </div>
                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
    </div>


    
   
@endsection
