@extends('backend.global.master')
@section('title', __('Change Password'))

@section('custom_stylesheet')
@stop

@section('content')
    <!--begin::Page Name -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800">Change Password</h1>
        <!-- Page Heading -->
        <div class="card">
            <div class="card-header">
                {{ Auth::user()->name }}'s profile
            </div>
            <div class="card-body">
                <!--begin::error message-->
                @include('errors.custom_error_message')
                <!--begin::error message-->
                <form action="{{ route('adminUpdatePasswordSave') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="password">Current/Old Password</label>
                        <input type="password" class="form-control form-control-user" id="current_password" aria-describedby="current_password"
                            placeholder="Current password ..." name="current_password" >
                    </div>
                    <div class="form-group">
                        <label for="password">New Password</label>
                        <input type="password" class="form-control form-control-user" id="password" aria-describedby="password"
                            placeholder="New Password ..." name="password">
                    </div>
                    <div class="form-group">
                        <label for="password">Confirm New Password</label>
                        <input type="password" class="form-control form-control-user" id="confirm_password"
                            aria-describedby="confirm_password" placeholder="Confirm New Password ..." name="confirm_password">
                    </div>
                    <button type="submit" class="btn btn-primary">Update password</button>
                </form>
            </div>
        </div>
    </div>
    <!--end::Page Name -->
@stop

@section('custom_scripts')
    {!! \App\UtilityFunction::getToastrMessage(Session::get('TOASTR_MESSAGE')) !!}
@stop
