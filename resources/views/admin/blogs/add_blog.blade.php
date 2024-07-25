@extends('admin.admin_master')

@section('admin')
    <style type="text/css">
        #image-preview, #image-preview-2,#image-preview-3 {
            width: 200px;
            height: 200px;
            position: relative;
            overflow: hidden;
            background-color: #ffffff;
            background-repeat: no-repeat;
            color: #ecf0f1;
        }

        #image-preview input, #image-preview-2 input,#image-preview-3 input {
            line-height: 200px;
            font-size: 200px;
            position: absolute;
            opacity: 0;
            z-index: 10;
        }

        #image-preview label, #image-preview-2 label ,#image-preview-3 label{
            position: absolute;
            z-index: 5;
            opacity: 0.8;
            cursor: pointer;
            background-color: #bdc3c7;
            width: 200px;
            height: 50px;
            font-size: 20px;
            line-height: 50px;
            text-transform: uppercase;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            margin: auto;
            text-align: center;
        }
        .bootstrap-tagsinput .tag{
            margin-right: 2px;
            color: #b70000;
            font-weight: 700px;
        }
    </style>

<div class="page-content">
    <div class="container-fluid">


        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title">Blog Section Page</h4><br><br>

                        <form action="{{route('store.blog')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            

                            <input type="hidden" name="id" value="">
         
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Blog Category Name</label>
                                <div class="col-sm-10">
                                    <select class="form-select" name="blog_category_id" aria-label="Default select example">
                                        <option selected="">Category Name</option>
                                        @foreach ($blogCategories as $category)
                                             <option value="{{$category->id}}">{{$category->blog_category}}</option>
                                        @endforeach
                                       
                                       
                                        </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Blog Title</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="blog_title" type="text" value=""
                                        id="example-text-input">
                                        @error('blog_title')
                                            <span class="text-danger">{{$message}}</span>
                                        @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Blog Tag</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="blog_tags" type="text" value=""
                                        data-role="tagsinput">
                                        @error('blog_title')
                                            <span class="text-danger">{{$message}}</span>
                                        @enderror
                                </div>
                            </div>



                            <div class="row mb-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Blog
                                    Description</label>

                                <div class="col-sm-10">
                                    <textarea id="elm1" name="blog_description"></textarea>
                                    @error('blog_description')
                                            <span class="text-danger">{{$message}}</span>
                                        @enderror

                                </div>
                            </div>


                                <div class="row mb-3">
                                    <label for="image-upload" class="col-sm-2 col-form-label">Blog Image</label>
                                    <div class="col-sm-10">
                                        <div id="image-preview">
                                            <img id="preview-img"
                                                 src="{{  url('uploads/no_image.jpg') }}"
                                                 alt="Slide Image"
                                                 style="max-width: 200px; max-height: 200px; display: block; margin-bottom: 10px;">
                                            <label for="image-upload" id="image-label" class="btn btn-primary">Choose
                                                File</label>
                                            <input type="file" name="blog_image" id="image-upload"
                                                   style="display: none;" onchange="previewImage(event, 'preview-img')" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="image-upload-2" class="col-sm-2 col-form-label">Blog Content Image</label>
                                    <div class="col-sm-10">
                                        <div id="image-preview-2">
                                            <img id="preview-img-2"
                                                 src="{{  url('uploads/no_image.jpg') }}"
                                                 alt="Slide Image"
                                                 style="max-width: 200px; max-height: 200px; display: block; margin-bottom: 10px;">
                                            <label for="image-upload-2" id="image-label-2" class="btn btn-primary">Choose
                                                File</label>
                                            <input type="file" name="blog_image_1" id="image-upload-2"
                                                   style="display: none;" onchange="previewImage(event, 'preview-img-2')" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="image-upload-3" class="col-sm-2 col-form-label">Blog Content Image</label>
                                    <div class="col-sm-10">
                                        <div id="image-preview-3">
                                            <img id="preview-img-3"
                                                 src="{{  url('uploads/no_image.jpg') }}"
                                                 alt="Slide Image"
                                                 style="max-width: 200px; max-height: 200px; display: block; margin-bottom: 10px;">
                                            <label for="image-upload-3" id="image-label-3" class="btn btn-primary">Choose
                                                File</label>
                                            <input type="file" name="blog_image_2" id="image-upload-3"
                                                   style="display: none;" onchange="previewImage(event, 'preview-img-3')" />
                                        </div>
                                    </div>
                                </div>

                                <input type="submit" class="btn btn-info btn-rounded waves-effect waves-light"
                                       value="Add Blog">
                            </form>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        function previewImage(event, previewId) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById(previewId);
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endsection
