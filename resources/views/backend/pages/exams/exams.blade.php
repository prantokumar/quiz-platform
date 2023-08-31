@extends('backend.global.master', ['menu' => 'exams'])
@section('title', __('Exams'))

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
        <h1 class="h3 mb-4 text-gray-800">Exams</h1>
        <!-- Page Heading -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Exams</h4>
                <button class="btn btn-primary" data-toggle="modal" data-target=".add_exam_modal">Add Exam</button>
            </div>
            <div class="card-body">
                <!--begin::error message-->
                @include('errors.custom_error_message')
                <!--begin::error message-->
                <div id="show-exams">
                    <div class="loading_image" style="display: none;">
                        <div class="d-flex justify-content-center align-items-center w-100 h-100">
                            <img src="{{ asset('backend/loader/ring-alt.gif') }}" style="width:50px;" alt="Loading...">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Page Name -->

    {{-- Add Exam Modal --}}
    <div class="modal fade add_exam_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Exam</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="exam_name" class="col-form-label">Exam name</label>
                            <input type="text" class="form-control exam_name" name="exam_name" placeholder="Exam name">
                        </div>
                        <div class="form-group">
                            <label for="instruction" class="col-form-label">Instructions</label>
                            <textarea class="form-control instruction" name="instruction" id="" cols="30" rows="5"
                                placeholder="Instructions"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="exam_duration" class="col-form-label">Duration(In minutes)</label>
                            <input type="text" class="form-control exam_duration" name="exam_duration"
                                placeholder="Duration(In minutes)">
                        </div>
                        <div class="form-group">
                            <label for="no_of_attempts" class="col-form-label">Maximum no. of attempts</label>
                            <input type="text" class="form-control no_of_attempts" name="no_of_attempts"
                                placeholder="Maximum no. of attempts">
                        </div>
                        <div class="form-group">
                            <label for="exam_start_date" class="col-form-label">Start date</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control exam_start_date" name="exam_start_date" placeholder="Start date" aria-label="Start date"
                                    aria-describedby="Start-date">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="Start-date"><i class="fas fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exam_end_date" class="col-form-label">End date</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control exam_end_date" name="exam_end_date" placeholder="End date" aria-label="End date"
                                    aria-describedby="End-date">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="End-date"><i class="fas fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exam_due_date" class="col-form-label">Due date</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control exam_due_date" name="exam_due_date" placeholder="Due date" aria-label="Due date"
                                    aria-describedby="Due-date">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="Due-date"><i class="fas fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="result_display" class="col-form-label">Result display</label>
                            <select name="result_display" class="form-control result_display">
                                <option value="{{ \App\Http\Controllers\Enum\ExamEnum::AUTOMATIC_EVALUATION }}" selected>
                                    Automatic Evaluation</option>
                                <option value="{{ \App\Http\Controllers\Enum\ExamEnum::MANUAL_EVALUATION }}">Manual
                                    Evaluation</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="is_published" class="col-form-label">Publish/Unpublish</label>
                            <select name="is_published" class="form-control is_published">
                                <option value="{{ \App\Http\Controllers\Enum\ExamEnum::PUBLISHED }}" selected>
                                    Publish</option>
                                <option value="{{ \App\Http\Controllers\Enum\ExamEnum::UNPUBLISHED }}">Unpublish</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save exam</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Add Exam Modal --}}
@stop

@section('custom_scripts')
    {!! \App\UtilityFunction::getToastrMessage(Session::get('TOASTR_MESSAGE')) !!}
    @include('backend.pages.exams.exam_scripts')
@stop
