@extends('backend.global.master', ['menu' => 'users'])
@section('title', __('Users'))

@section('custom_stylesheet')
<style>
    .custom_user_image {
        width: 40px !important;
        height: 40px !important;
    }

</style>
@stop

@section('content')
<!--begin::Page Name -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Users</h1>
    <!-- Page Heading -->
    <div class="card">
        <div class="card-header">
            Users
        </div>
        <div class="card-body">
            <!--begin::error message-->
            @include('errors.custom_error_message')
            <!--begin::error message-->
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile Number</th>
                            <th>Photo</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile Number</th>
                            <th>Photo</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @if (isset($users[0]))
                        @foreach ($users as $index => $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ isset($user->email) ? $user->email : 'N/A' }}</td>
                            <td>{{ isset($user->mobile) ? $user->mobile : 'N/A' }}</td>
                            <td>
                                <img class="img-profile rounded-circle custom_user_image" @if (isset($user->photo)) src="{{ asset('images/users/' . $user->photo) }}" @else src="{{ asset('backend/img/undraw_profile.svg') }}" @endif>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="4" class="text-center text-danger"><strong>No Users Found!</strong></td>
                        </tr>
                        @endif
                    </tbody>
                    {{-- default pagination --}}
                    @if ($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    {{ $users->withQueryString()->links() }}
                    @endif
                    {{-- default pagination --}}
                </table>
            </div>
        </div>
    </div>
</div>
<!--end::Page Name -->
@stop

@section('custom_scripts')
{!! \App\UtilityFunction::getToastrMessage(Session::get('TOASTR_MESSAGE')) !!}
@stop
