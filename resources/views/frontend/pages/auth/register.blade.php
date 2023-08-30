@extends('frontend.global.auth_master')
@section('title', __('User Register'))

@section('custom_stylesheet')
@stop

@section('auth_content')
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome to Quiz App! Register Here</h1>
                                    </div>
                                    <!--begin::error message-->
                                    @include('errors.custom_error_message')
                                    <!--begin::error message-->
                                    <form class="user" method="post" action="{{ url('/save-new-user') }}">
                                        @csrf
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" id="name" name="name"
                                                placeholder="Full name" value="{{ old("name") }}">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" id="email_or_mobile" name="email_or_mobile"
                                                placeholder="Email Address/Mobile Number" name="" value="{{ old("email_or_mobile") }}">
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-6 mb-3 mb-sm-0">
                                                <input type="password" class="form-control form-control-user"
                                                    id="exampleInputPassword" placeholder="Password" name="password">
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="password" class="form-control form-control-user"
                                                    id="password-confirm" placeholder="Repeat Password" name="password_confirmation">
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Register Account
                                        </button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="{{ route('userForgetPassword') }}">Forgot Password?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="{{ route('userlogin') }}">Already have an account? Login!</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 d-none d-lg-block bg-register-image"></div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
@stop

@section('auth_custom_scripts')
{!! \App\UtilityFunction::getToastrMessage(Session::get('TOASTR_MESSAGE')) !!}
@stop
