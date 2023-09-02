@extends('frontend.global.master', ['menu' => 'user_dashboard'])
@section('title', __('User Submissions'))

@section('custom_stylesheet')
<style>
      .wrong-answer {
        width: 16px;
        height: 16px;
        border: 1px solid black;
        border-radius: 50%;
    }

    .correct-answer {
        width: 16px;
        height: 16px;
        border: 1px solid green;
        background: green;
        border-radius: 50%;
    }
    .user-given-answer{
        background: #5A5A5A;
    }
</style>
@stop

@section('content')
<!--begin::Page Name -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Submissions</h1>
    <a class="btn btn-success btn-sm mb-2" href="{{ route('userDashboard') }}">Back</a>
    @php
        $exam_id = \Illuminate\Support\Facades\Crypt::decrypt(request()->get('exam_id'));
        $user_id = \Illuminate\Support\Facades\Crypt::decrypt(request()->get('user_id'));
    @endphp
    <!-- Page Heading -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <span class="card-label fw-bolder fs-3 mb-1">
                    @php
                        $exam_name = \App\Models\Exam::findOrFail($exam_id)->exam_name;
                    @endphp
                    {{ $exam_name }} Submissions
                    @php
                        $result_display = \App\Models\Exam::findOrFail($exam_id)->result_display;
                    @endphp
                    <input type="hidden" class="exam_id" value="{{ $exam_id }}">
                    <input type="hidden" class="user_id" value="{{ $user_id }}">
                    @if ($result_display == \App\Http\Controllers\Enum\ExamEnum::AUTOMATIC_EVALUATION)
                        <span class="badge badge-lg badge-info ml-3">Automatic Evaluation</span>
                    @else
                        <span class="badge badge-lg badge-primary ml-3">Manual Evaluation</span>
                    @endif
                </span>
            </h4>
            <span class="text-muted mt-1 fw-bold fs-7">Total {{ $submissions->count() }} submissions</span>
        </div>
        <div class="card-body">
            <!--begin::error message-->
            @include('errors.custom_error_message')
            <!--begin::error message-->
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="min-w-200px">User Name</th>
                            <th class="min-w-200px text-center">Marks</th>
                            <th class="min-w-200px text-center">Submission date</th>
                            <th class="min-w-100px text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($submissions[0]))
                        @foreach ($submissions as $index => $submission)
                        <tr>
                            <td>
                                @php
                                    $user_name = \App\Models\User::userName($submission->user_id);
                                @endphp
                                {{ $user_name }}
                            </td>
                            <td class="text-center">
                                @php
                                    $particular_user_submissions = \App\Models\ExamSubmission::examSubmissions($exam_id, $submission->user_id);
                                    $check_answers = \App\Models\Question::checkAnswerIsCorrect($exam_id, $submission->id);
                                @endphp
                                {{ $check_answers['total_obtained_marks'] }}
                            </td>
                            <td class="text-center">
                                <span
                                    class="badge badge-square badge-primary ps-2 pe-2 mb-1"
                                    data-toggle="tooltip"
                                    data-custom-class="tooltip-dark"
                                    data-placement="top" title="Submission Date">
                                    {{ date('d F, Y h:i A', strtotime($submission->submission_date)) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-primary btn-sm view_submission_button" data-toggle="modal" data-target="#submission_details_modal"
                                    data-userid="{{ $submission->user_id }}"
                                    data-username="{{ $user_name }}"
                                    data-examname="{{ $exam_name }}"
                                    data-submissionid="{{ $submission->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="4" class="text-center text-danger"><strong>No Submissions Found!</strong></td>
                        </tr>
                        @endif
                    </tbody>
                    {{-- default pagination --}}
                    @if ($submissions instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    {{ $submissions->withQueryString()->links() }}
                    @endif
                    {{-- default pagination --}}
                </table>
            </div>
        </div>
    </div>
</div>
<!--end::Page Name -->

{{-- question view screen modal --}}
<div class="modal fade" id="submission_details_modal" tabindex="-1" role="dialog" aria-labelledby="fullscreenModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header d-flex">
                <h5 class="modal-title" id="fullscreenModalLabel"><span class="dynamic_modal_title">Submission Details</span>&nbsp;<span class="badge badge-lg badge-info me-3">Total Marks : {{ \App\Models\ExamQuestion::getExamMarks($exam_id)->marks }}</span><span class="badge badge-lg badge-primary ml-3 total_obtained_mark"></span></h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="height: 600px; overflow-y:auto;">
                <div class="dynamic_modal_loading_image h-100" style="display:none;">
                    <div class="text-center d-flex justify-content-center align-items-center h-100">
                        <img src="{{ asset('backend/loader/ring-alt.gif') }}" style="width:50px;" alt="">
                    </div>
                </div>
                <div id="submission_detail_dynamic_data">
                    {{-- Dynamically load submission details --}}
                    {{-- Dynamically load submission details --}}
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal" id="closeSubmissionDetailsModal">Close</button>
            </div>
        </div>
    </div>
</div>
{{-- question view screen modal --}}
@stop

@section('custom_scripts')
{!! \App\UtilityFunction::getToastrMessage(Session::get('TOASTR_MESSAGE')) !!}
@include('frontend.pages.submissions.submissions_scripts')
@stop
