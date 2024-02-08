@extends('admin/layout/app')
@section("contents")
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Category</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.categories') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <form action="{{route('admin.category.create')}}" id="categoryForm"  name="categoryForm" enctype="multipart/form-data" method="post">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="Name">
                            <p></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug') }}" placeholder="Slug" readonly >
                            <p></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Image</label>
                                <input type="hidden" id="image_id" name="image_id"  value="">
                                <div id="image" class="dropzone dz-clickable">
                                    <div class="dz-message needsclick">
                                        <br>Drop files here or click to upload.<br><br>
                                    </div>
                                </div>
                                <!-- <form action="/target" class="dropzone" id="my-great-dropzone"></form> -->
                            </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="1">Enabled</option>
                                <option value="0" >Disabled</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="pb-5 pt-3">
            <button class="btn btn-primary">Create</button>
            <a href="route('admin.categories')" class="btn btn-outline-dark ml-3">Cancel</a>
        </div>
    </div>
    </form>
</section>
@endsection

@section('customScript')
<script>
$("#name").change(function(event){
    $.ajax({
            url : "{{route('getSlug')}}",
            method : "post",
            data : {title: $(this).val()},
            dataType: "json",
            success:function(response){
                if(response['status']){
                    $("#slug").val(response['slug']);
                }
            }
        })
    });
    $("#categoryForm").submit(function(event){
        event.preventDefault();
        $.ajax({
            url: "{{route('admin.category.create')}}",
            method : "post",
            data: $(this).serializeArray(),
            dataType: "json",
            success:function(response){
                if(response['status']){
                    window.location.href= "{{ route('admin.categories')}}";
                } else{
                    var error = response['message'];
                    if(error['name']){
                        $("#name").addClass("is-invalid").siblings("p").addClass("invalid-feedback").html(error['name']);
                    }
                    if(error['slug']){
                        $("#slug").addClass("is-invalid").siblings("p").addClass("invalid-feedback").html(error['slug']);
                    }
                }
            }
        });
    });

    Dropzone.autoDiscover = false;
    const dropzone = $("#image").dropzone({
        init: function() {
            this.on('addedfile', function(file) {
                if (this.files.length > 1) {
                    this.removeFile(this.files[0]);
                }
            });
        },
        url:  "{{ route('temp-images.create') }}",
        maxFiles: 1,
        paramName: 'image',
        addRemoveLinks: true,
        acceptedFiles: "image/jpeg,image/png,image/gif",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, success: function(file, response){
            $("#image_id").val(response.image_id);
            //console.log(response)
        }
    });

</script>
@endsection
