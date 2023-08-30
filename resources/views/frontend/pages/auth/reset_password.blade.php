@extends('frontend.global.auth_master')
@section('title', __('User - Reset Password'))

@section('custom_stylesheet')
@stop

@section('auth_content')
    <!--begin::Authentication - Sign-in Admin-->
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Reset Password</h1>
                                    </div>
                                    <!--begin::error message-->
                                    @include('errors.custom_error_message')
                                    <!--begin::error message-->
                                    <form action="{{ route('userResetPasswordSave') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="email_or_mobile" value="{{ Cookie::get('email_or_mobile_cookie') }}" />
                                        <div class="form-group">
                                            <label for="password">New Password</label>
                                            <input type="password" class="form-control form-control-user" id="password"
                                                aria-describedby="password" placeholder="New Password ..." name="password">
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Confirm New Password</label>
                                            <input type="password" class="form-control form-control-user"
                                                id="confirm_password" aria-describedby="confirm_password"
                                                placeholder="Confirm New Password ..." name="confirm_password">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Update password</button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="{{ route('userlogin') }}">Back to Login!</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="{{ route('userRegister') }}">Create an Account!</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 d-none d-lg-block bg-login-image"></div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
    <!--end::Authentication - Sign-in Admin-->
@stop

@section('auth_custom_scripts')
    {!! \App\UtilityFunction::getToastrMessage(Session::get('TOASTR_MESSAGE')) !!}
@stop
