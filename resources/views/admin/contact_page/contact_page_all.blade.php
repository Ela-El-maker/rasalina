@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Contact Messages All</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Contact Messages All</a></li>
                                <li class="breadcrumb-item active">Contact Messages All</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title">Contact Messages All</h4>
                            <p>
                                <br>
                            </p>

                            <table id="datatable" class="table table-bordered dt-responsive nowrap"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Ser. No</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Subject</th>
                                        <th>Message</th>

                                        <th>Phone</th>
                                        <th>Date Sent</th>

                                        <th>Action</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($i = 1)
                                    @foreach ($contactMessages as $item)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td style="white-space: normal; word-wrap: break-word; overflow: hidden; text-overflow: ellipsis;">
                                                {{ $item->subject }}
                                            </td>
                                            <td style="white-space: normal; word-wrap: break-word; overflow: hidden; text-overflow: ellipsis;">
                                                {{ $item->message }}
                                            </td>
                                            <td>{{ $item->phone }}</td>
                                            <td>{{Carbon\Carbon::parse($item->created_at)->diffForHumans()}}</td>

                                            <td>
                                                <a href="{{ route('delete.contact.message', $item->id) }}" class="btn btn-danger sm delete-btn" title="Delete Data"><i class="fas fa-trash-alt"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>


                               
                            </table>

                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->


        </div> <!-- container-fluid -->
    </div>
@endsection
