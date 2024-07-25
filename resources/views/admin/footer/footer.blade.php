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

                            <h4 class="card-title">Footer Section Page</h4><br><br>

                            <form action="{{route('footer.update')}}" method="post" enctype="multipart/form-data">
                                @csrf

                                <input type="hidden" name="id" value="{{$footer->id}}">
                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Number</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" name="number" type="text"
                                            value="{{ $footer->number }}" id="example-text-input">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" name="email" type="email"
                                            value="{{ $footer->email }}" id="example-text-input">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Address</label>
                                    <div class="col-sm-10">

                                        <div class="form-floating">
                                        
                                            <textarea class="form-control" name="address" type="text" placeholder="Leave a Sub title here" id="example-text-input" style="height: 100px">{{ $footer->address }}</textarea>
                                        </div>
                                      
                                    </div>
                                </div>

                                

                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Sub Description</label>
                                    <div class="col-sm-10">
                                        <textarea id="elm1"  name="sub_description">{{ $footer->sub_description }}</textarea>

                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Facebook</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" name="facebook" type="text"
                                            value="{{ $footer->facebook }}" id="example-text-input">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Twitter</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" name="twitter" type="text"
                                            value="{{ $footer->twitter }}" id="example-text-input">
                                    </div>
                                </div>


                                <div class="row mb-3">
                                    <label for="example-text-input" class="col-sm-2 col-form-label">Copyright</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" name="copyright" type="text"
                                            value="{{ $footer->copyright }}" id="example-text-input">
                                    </div>
                                </div>
                                <!-- end row -->
                                <input type="submit" class="btn btn-info btn-rounded waves-effect waves-light"
                                    value="Update Footer Page">
                            </form>

                        </div>
                    </div>
                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
    </div>

   

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
