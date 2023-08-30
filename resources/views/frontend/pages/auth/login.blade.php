@extends('frontend.global.auth_master')
@section('title', __('Login'))

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
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome to Quiz App!</h1>
                                    </div>
                                    <!--begin::error message-->
                                    @include('errors.error_message')
                                    <!--begin::error message-->
                                    <form class="user" method="post" action="{{ url('/admin/post/login') }}">
                                        @csrf
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user"
                                                id="email_or_mobile" aria-describedby="email_or_mobile"
                                                placeholder="Enter Email/Mobile ..." name="email_or_mobile"  value="{{ old("email") }}">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                id="password" placeholder="Password" name="password">
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
    <!--end::Authentication - Sign-in Admin-->
@stop

@section('custom_scripts')
@stop
