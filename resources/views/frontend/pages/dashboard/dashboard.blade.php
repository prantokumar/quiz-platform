@extends('frontend.global.master', ['menu' => 'user_dashboard'])
@section('title', __('Quiz Dashboard'))

@section('custom_stylesheet')
@stop

@section('content')
<!--begin::Admin Dashbiard -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Quiz Dashboard</h1>
    <!-- Page Heading -->
    <div id="show-exams">
        <div class="loading_image" style="display: none;">
            <div class="d-flex justify-content-center align-items-center w-100 h-100">
                <img src="{{ asset('backend/loader/ring-alt.gif') }}" style="width:50px;" alt="Loading...">
            </div>
        </div>
    </div>
</div>
<!--end::Admin Dashbiard -->

{{-- question view screen modal --}}
<div class="modal fade" id="view_questions_area_modal_for_user" tabindex="-1" role="dialog" aria-labelledby="fullscreenModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fullscreenModalLabel">Questions</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{ route('submitQuiz') }}">
                <input type="hidden" id="view_question_exam_id" class="form-control form-control-solid">
                <div class="modal-body" style="height: 600px; overflow-y:auto;">
                    <div id="view_questions"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal" id="closeViewQuestionsModal">Close</button>
                    <button type="button" class="btn btn-primary" id="submit_exam_button">Submit quiz</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- question view screen modal --}}

{{-- quiz start before confirmation modal --}}
<div class="modal fade quiz_start_confirmation_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to start quiz test?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="quiz_start_confirmation_modal_form" class="form" action="{{ route('deleteExamQuestion') }}" method="post">
                <div class="modal-body">
                    Ready to start quiz?
                    <input type="hidden" id="get_exam_id" class="form-control form-control-solid">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal" id="closeQuizStartConfirmationModal">Cancel quiz</button>
                    <button class="btn btn-primary" id="start_quiz_button">Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- quiz start before confirmation modal --}}
@stop

@section('custom_scripts')
{!! \App\UtilityFunction::getToastrMessage(Session::get('TOASTR_MESSAGE')) !!}
@include('frontend.pages.dashboard.dashboard_scripts')
@stop
