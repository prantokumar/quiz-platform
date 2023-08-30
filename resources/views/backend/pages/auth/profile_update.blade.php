@extends('backend.global.master')
@section('title', __('Update Profile'))

@section('custom_stylesheet')
    <style>
        .holder {
            height: 300px;
            width: 300px;
            border: 2px solid black;
        }

        #imgPreview {
            max-width: 300px;
            max-height: 300px;
            min-width: 300px;
            min-height: 300px;
        }

        input[type="file"] {
            margin-top: 5px;
        }

        .heading {
            font-family: Montserrat;
            font-size: 45px;
            color: green;
        }
    </style>
@stop

@section('content')
    <!--begin::Page Name -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800">Update Profile</h1>
        <!-- Page Heading -->
        <div class="card">
            <div class="card-header">
                {{ Auth::user()->name }}'s profile
            </div>
            <div class="card-body">
                <!--begin::error message-->
                @include('errors.custom_error_message')
                <!--begin::error message-->
                <form action="{{ route('adminProfileUpdateSave') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Full name</label>
                        <input type="text" class="form-control form-control-user" id="name" aria-describedby="name"
                            placeholder="Enter name ..." name="name" value="{{ Auth::user()->name }}">
                    </div>
                    <div class="form-group">
                        <label for="name">Email</label>
                        <input type="email" class="form-control form-control-user" id="email" aria-describedby="email"
                            placeholder="Enter email ..." name="email" value="{{ Auth::user()->email }}">
                    </div>
                    <div class="form-group">
                        <label for="name">Mobile number</label>
                        <input type="mobile_number" class="form-control form-control-user" id="mobile_number"
                            aria-describedby="name" placeholder="Enter mobile number ..." name="mobile_number"
                            value="{{ Auth::user()->mobile_number }}">
                    </div>
                    <div class="form-group">
                        <div class="holder">
                            <img id="imgPreview"
                                @if (isset(Auth::user()->photo)) src="{{ asset('images/users/' . Auth::user()->photo) }}"
                            @else
                            src="{{ asset('backend/img/undraw_profile.svg') }}" @endif
                                alt="pic" />
                        </div>
                        <input type="file" name="photo" id="photo" />
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
    <!--end::Page Name -->
@stop

@section('custom_scripts')
    {!! \App\UtilityFunction::getToastrMessage(Session::get('TOASTR_MESSAGE')) !!}
    <script>
        $(document).ready(() => {
            $('#photo').change(function() {
                const file = this.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        console.log(event.target.result);
                        $('#imgPreview').attr('src', event.target.result);
                    }
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
@stop
