@extends('admin.admin_master')

@section('admin')
    <style type="text/css">
        #image-preview {
            width: 200px;
            height: 200px;
            position: relative;
            overflow: hidden;
            background-color: #ffffff;
            background-repeat: no-repeat;
            /* Added to prevent image repetition */
            color: #ecf0f1;
        }

        #image-preview input {
            line-height: 200px;
            font-size: 200px;
            position: absolute;
            opacity: 0;
            z-index: 10;
        }

        #image-preview label {
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
    </style>


    <div class="page-content">
        <div class="container-fluid">


            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title">Home Slider Page</h4><br><br>

                            <form action="{{ route('store.profile') }}" method="post" enctype="multipart/form-data">
                                @csrf

                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Title</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" name="title" type="text"
                                            value="{{ $homeSlide->title }}" id="example-text-input">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Sub Title</label>
                                    <div class="col-sm-10">

                                        <div class="form-floating">
                                        
                                            <textarea class="form-control" name="sub_title" type="text" placeholder="Leave a Sub title here" id="example-text-input" style="height: 100px">{{ $homeSlide->sub_title }}</textarea>
                                        </div>
                                        {{-- <input class="form-control" 
                                            value="" > --}}
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Video URL</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" name="video_url" type="text"
                                            value="{{ $homeSlide->video_url }}" id="example-text-input">
                                    </div>
                                </div>

                                {{-- <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Profile Image</label>
                                    <div class="col-sm-10">
                                        <input name="profile_image" class="form-control" type="file"
                                            id="image">
                                    </div>
                                </div> --}}

                                {{-- <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Profile Image</label>

                                    <div id="image-preview">
                                        <label for="image-upload" id="image-label">Choose File</label>
                                        <input type="file" name="profile_image" id="image-upload" />
                                    </div>
                                </div> --}}

                                <div class="row mb-3">
                                    <label for="image-upload" class="col-sm-2 col-form-label">Profile Image</label>

                                    <div class="col-sm-10">
                                        <div id="image-preview">
                                            <img id="preview-img"
                                                src="{{ !empty($homeSlide->profile_image) ? url('uploads/home_slide/' . $homeSlide->profile_image) : url('uploads/no_image.jpg') }}"
                                                alt="Slide Image"
                                                style="max-width: 200px; max-height: 200px; display: block; margin-bottom: 10px;">
                                            <label for="image-upload" id="image-label" class="btn btn-primary">Choose
                                                File</label>
                                            <input type="file" name="profile_image" id="image-upload"
                                                style="display: none;" onchange="previewImage(event)" />
                                        </div>
                                    </div>
                                </div>



                                {{-- <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label"></label>
                                    <div class="col-sm-10">
                                        <img class="rounded avatar-lg img-fluid" id="showImage"
                                        src="{{(!empty($editData->profile_image)) ? url('uploads/admin_images/'.$editData->profile_image) : url('uploads/no_image.jpg')}}" alt="Card image cap">
                                    </div>
                                </div> --}}

                                <!-- end row -->
                                <input type="submit" class="btn btn-info btn-rounded waves-effect waves-light"
                                    value="Update Slide">
                            </form>

                        </div>
                    </div>
                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    {{-- <script type="text/javascript" src="">
    $(document).ready(function() {
        $('#image').on('change',function(e){
            var file = e.target.files[0];
            var reader = new FileReader();
            reader.onload = function(e){
                $('#showImage').html('<img src="' + e.target.result + '" alt="Image Preview"/>');
                
                // $('#showImage').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        });
    });
    </script> --}}

    <script type="text/javascript">
        $(document).ready(function() {
            $.uploadPreview({
                input_field: "#image-upload", // Default: .image-upload
                preview_box: "#image-preview", // Default: .image-preview
                label_field: "#image-label", // Default: .image-label
                label_default: "Choose File", // Default: Choose File
                label_selected: "Change File", // Default: Change File
                no_label: false // Default: false
            });
        });
    </script>


    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('preview-img');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endsection
