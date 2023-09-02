@extends('frontend.global.master', ['menu' => 'user_leaderboard'])
@section('title', __('Quiz Leader Board'))

@section('custom_stylesheet')
@stop

@section('content')
<!--begin::Admin Dashbiard -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Leader board</h1>
    <!-- Page Heading -->
    <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th class="min-w-200px">User Name</th>
                    <th class="min-w-200px text-center">Obtained Marks</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($leaderboards[0]))
                @foreach ($leaderboards as $index => $leaderboard)
                <tr>
                    <td>
                        @php
                            $user_name = \App\Models\User::userName($leaderboard->user_id);
                        @endphp
                        {{ $user_name }} @if (\Illuminate\Support\Facades\Auth::user()->id == $leaderboard->user_id) <span class="badge badge-info badge-sm">You</span> @endif
                    </td>
                    <td class="text-center">
                        {{ $leaderboard->total_obtained_marks }}
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="4" class="text-center text-danger"><strong>No Data Found!</strong></td>
                </tr>
                @endif
            </tbody>
            {{-- default pagination --}}
            @if ($leaderboards instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {{ $leaderboards->withQueryString()->links() }}
            @endif
            {{-- default pagination --}}
        </table>
    </div>
</div>
<!--end::Admin Dashbiard -->
@stop

@section('custom_scripts')
{!! \App\UtilityFunction::getToastrMessage(Session::get('TOASTR_MESSAGE')) !!}
@stop
