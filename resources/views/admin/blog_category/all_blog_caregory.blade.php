@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Blog Category All</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Create Image</a></li>
                                <li class="breadcrumb-item active">Blog Category All Tables</li>
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

                            <h4 class="card-title">Blog Category All</h4>
                            <p>
                                <br>
                            </p>

                            <table id="datatable" class="table table-bordered dt-responsive nowrap"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Ser. No</th>
                                        <th>Blog Category Name</th>
                                        <th>Action</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($i = 1)
                                    @foreach ($blogCategory as $item)
                                        <tr >
                                            <td>{{ $i++ }}</td>
                                                <td>{{$item->blog_category}}</td>
                                                <td>

                                               
                                                <a href="{{route('edit.blog.category', $item->id)}}" class="btn btn-info sm" title="Edit Data"><i class="fas fa-edit"></i></a>
                                                <a href="{{route('delete.blog.category', $item->id)}}" class="btn btn-danger sm delete-btn" title="Delete Data"><i class="fas fa-trash-alt"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>


                                {{-- <tbody>
                                    @php($i = 1)
                                    @foreach ($allMultiImage as $item)
                                        <tr>
                                        <td>{{$i++}}</td>
                                        <td><img src="{{asset($item->multi_image)}}" style="height: 70px; width:60px"></td>
                                        <td>{{$item->multi_image}}</td>
                                        <td>{{$item->created_at}}</td>
                                        <td>
                                            <a href="{{route('edit.multi.image', $item->id)}}" class="btn btn-info sm" title="Edit Data"> <i class="fas fa-edit"></i></a>
                                            <a href="{{route('delete.multi.image', $item->id)}}" class="btn btn-danger sm" id="delete" title="Delete Data"> <i class="fas fa-trash-alt"></i></a>
                                        </td>
                                        
                                        
                                    </tr>
                                    @endforeach
                                    </tr>
                                </tbody> --}}
                            </table>

                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->


        </div> <!-- container-fluid -->
    </div>
@endsection
