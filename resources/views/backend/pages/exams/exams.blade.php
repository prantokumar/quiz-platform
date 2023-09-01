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
<div class="modal fade add_exam_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="add_exam_modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Exam</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{ route('saveExam') }}">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-form-label">Exam name<sup class="text-danger font-weight-bold">*</sup></label>
                        <input type="text" class="form-control exam_name" name="exam_name" placeholder="Exam name">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Instructions</label>
                        <textarea class="form-control instruction" name="instruction" id="" cols="30" rows="5" placeholder="Instructions"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Duration(In minutes)<sup class="text-danger font-weight-bold">*</sup></label>
                        <input type="number" class="form-control exam_duration" name="exam_duration" placeholder="Duration(In minutes)">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Maximum no. of attempts<sup class="text-danger font-weight-bold">*</sup></label>
                        <input type="number" class="form-control no_of_attempts" name="no_of_attempts" placeholder="Maximum no. of attempts">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Start date</label>
                        <div class="input-group mb-3">
                            <input type="datetime-local" class="form-control exam_start_date" name="exam_start_date" placeholder="Start date" aria-label="Start date" aria-describedby="Start-date">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">End date</label>
                        <div class="input-group mb-3">
                            <input type="datetime-local" class="form-control exam_end_date" name="exam_end_date" placeholder="End date" aria-label="End date" aria-describedby="End-date">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Due date<sup class="text-danger font-weight-bold">*</sup></label>
                        <div class="input-group mb-3">
                            <input type="datetime-local" class="form-control exam_due_date" name="exam_due_date" placeholder="Due date" aria-label="Due date" aria-describedby="Due-date">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="result_display1" name="result_display" class="custom-control-input result_display" value="{{ \App\Http\Controllers\Enum\ExamEnum::AUTOMATIC_EVALUATION }}" checked>
                            <label class="custom-control-label" for="result_display1">Automatic Evaluation</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="result_display2" name="result_display" class="custom-control-input result_display" value="{{ \App\Http\Controllers\Enum\ExamEnum::MANUAL_EVALUATION }}">
                            <label class="custom-control-label" for="result_display2">Manual Evaluation</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="is_published1" name="is_published" class="custom-control-input is_published" value="{{ \App\Http\Controllers\Enum\ExamEnum::PUBLISHED }}" checked>
                            <label class="custom-control-label" for="is_published1">Publish</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="is_published2" name="is_published" class="custom-control-input is_published" value="{{ \App\Http\Controllers\Enum\ExamEnum::UNPUBLISHED }}">
                            <label class="custom-control-label" for="is_published2">Unpublish</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" class="form-control exam_input_id" id="exam_input_id" name="exam_input_id">
                    <button type="button" class="btn btn-secondary resetAddExamModal" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="save_exam_button">Save exam</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- Add Exam Modal --}}

{{-- Edit Exam Modal --}}
<div class="modal fade edit_exam_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="edit_exam_modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Exam</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{ route('updateExam') }}">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-form-label">Exam name<sup class="text-danger font-weight-bold">*</sup></label>
                        <input type="text" class="form-control" name="exam_name" id="exam_name" placeholder="Exam name">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Instructions</label>
                        <textarea class="form-control" name="instruction" id="instruction" cols="30" rows="5" placeholder="Instructions"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Duration(In minutes)<sup class="text-danger font-weight-bold">*</sup></label>
                        <input type="number" class="form-control" name="exam_duration" id="exam_duration" placeholder="Duration(In minutes)">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Maximum no. of attempts<sup class="text-danger font-weight-bold">*</sup></label>
                        <input type="number" class="form-control" name="no_of_attempts" id="no_of_attempts" placeholder="Maximum no. of attempts">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Start date</label>
                        <div class="input-group mb-3">
                            <input type="datetime-local" class="form-control" name="exam_start_date" id="exam_start_date" placeholder="Start date" aria-label="Start date" aria-describedby="Start-date">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">End date</label>
                        <div class="input-group mb-3">
                            <input type="datetime-local" class="form-control" name="exam_end_date" id="exam_end_date" placeholder="End date" aria-label="End date" aria-describedby="End-date">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Due date<sup class="text-danger font-weight-bold">*</sup></label>
                        <div class="input-group mb-3">
                            <input type="datetime-local" class="form-control" name="exam_due_date" id="exam_due_date" placeholder="Due date" aria-label="Due date" aria-describedby="Due-date">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="result_edit_display1" name="result_display_edit" class="custom-control-input" value="{{ \App\Http\Controllers\Enum\ExamEnum::AUTOMATIC_EVALUATION }}" checked>
                            <label class="custom-control-label" for="result_edit_display1">Automatic
                                Evaluation</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="result_edit_display2" name="result_display_edit" class="custom-control-input" value="{{ \App\Http\Controllers\Enum\ExamEnum::MANUAL_EVALUATION }}">
                            <label class="custom-control-label" for="result_edit_display2">Manual Evaluation</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="is_edit_published1" name="is_published_edit" class="custom-control-input" value="{{ \App\Http\Controllers\Enum\ExamEnum::PUBLISHED }}" checked>
                            <label class="custom-control-label" for="is_edit_published1">Publish</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="is_edit_published2" name="is_published_edit" class="custom-control-input" value="{{ \App\Http\Controllers\Enum\ExamEnum::UNPUBLISHED }}">
                            <label class="custom-control-label" for="is_edit_published2">Unpublish</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" class="form-control" id="exam_input_edit_id" name="exam_input_edit_id">
                    <button type="button" class="btn btn-secondary resetEditExamModal" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="edit_exam_button">Update exam</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- Edit Exam Modal --}}

{{-- delete exam modal --}}
<div class="modal fade delete_exam_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to delete?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="delete_exam_modal_form" class="form" action="{{ route('deleteExam') }}" method="post">
                <div class="modal-body">
                    Are you sure? You want to delete this exam!
                    <input type="hidden" id="delete_exam_id" class="form-control form-control-solid">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal" id="closeDeleteExamModal">Cancel</button>
                    <button class="btn btn-primary" id="delete_exam_button">Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- delete exam modal --}}

{{-- question add full screen modal --}}
<div class="modal fade" id="add_question_area_modal" tabindex="-1" role="dialog" aria-labelledby="fullscreenModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fullscreenModalLabel">Add Questions</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form>
                <input type="hidden" id="exam_id" class="form-control form-control-solid">
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <label class="col-form-label">Question title<sup class="text-danger font-weight-bold">*</sup></label>
                                <input type="text" class="form-control" name="title" id="title" placeholder="Question title">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label class="col-form-label">Mark</label>
                                <input type="number" class="form-control" name="marks" id="marks" placeholder="Mark">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <label class="col-form-label">Description</label>
                                <textarea class="form-control" name="description" id="description" cols="30" rows="5" placeholder="description"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group m-1">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <input type="radio" name="is_correct[]">
                                </div>
                            </div>
                            <input type="text" name="answer[]" class="form-control" placeholder="Answer 1">
                        </div>
                        <div class="input-group m-1">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <input type="radio" name="is_correct[]">
                                </div>
                            </div>
                            <input type="text" name="answer[]" class="form-control" placeholder="Answer 2">
                        </div>
                        <div class="input-group m-1">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <input type="radio" name="is_correct[]">
                                </div>
                            </div>
                            <input type="text" name="answer[]" class="form-control" placeholder="Answer 3">
                        </div>
                        <div class="input-group m-1">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <input type="radio" name="is_correct[]">
                                </div>
                            </div>
                            <input type="text" name="answer[]" class="form-control" placeholder="Answer 4">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal" id="closeAddExamModal">Cancel</button>
                    <button class="btn btn-primary" id="save_question_button">Add question</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- question add/edit full screen modal --}}

@stop

@section('custom_scripts')
{!! \App\UtilityFunction::getToastrMessage(Session::get('TOASTR_MESSAGE')) !!}
@include('backend.pages.exams.exam_scripts')
@stop
