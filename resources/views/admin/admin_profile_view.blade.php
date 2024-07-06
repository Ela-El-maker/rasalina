@extends('admin.admin_master')

@section('admin')
    <!-- Ensure you have Carbon imported -->
    @php
        use Carbon\Carbon;
    @endphp

    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-6">
                    <div class="card"><br><br>
                        <center>
                            <img class="rounded-circle avatar-xl img-fluid"
                                src="{{ !empty($adminData->profile_image) ? url('uploads/admin_images/' . $adminData->profile_image) : url('uploads/no_image.jpg') }}"
                                alt="Card image cap">
                        </center>

                        <div class="card-body">
                            <hr>
                            <h4 class="card-title">Username : {{ $adminData->username }}</h4>
                            <hr>
                            <h4 class="card-title">Name : {{ $adminData->name }}</h4>
                            <hr>
                            <h4 class="card-title">Email : {{ $adminData->email }}</h4>
                            <hr>

                            <a href="{{ route('edit.profile') }}" type="button"
                                class="btn btn-info btn-rounded waves-effect waves-light"> Edit Profile</a>
                            <hr>
                            <p class="card-text">
                                <small class="text-muted">Last updated :
                                    {{ Carbon::parse($adminData->updated_at)->setTimezone('Africa/Nairobi')->format('F j, Y, g:i A') }}</small>
                                <b><small class="text-muted">
                                        {{ Carbon::parse($adminData->updated_at)->setTimezone('Africa/Nairobi')->diffForHumans() }}</small></b>
                                {{-- <small class="text-muted">Last updated 3 mins ago</small> --}}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div>
    </div>
@endsection
