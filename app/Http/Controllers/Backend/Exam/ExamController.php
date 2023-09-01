<?php

namespace App\Http\Controllers\Backend\Exam;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Enum\ExamEnum;
use App\Http\Controllers\Enum\MessageTypeEnum;
use App\Http\Controllers\Enum\QuestionTypeEnum;
use App\Models\Answer;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\ExamSubmission;
use App\Models\Question;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExamController extends Controller
{
    public function getExams()
    {
        try {
            $exams = Exam::where('is_published', ExamEnum::PUBLISHED)->where('status', ExamEnum::ACTIVE)->paginate(env('PAGINATION_SMALL'));
            return view('backend.pages.exams.exams')->with(['exams' => $exams]);
        } catch (Exception $error) {
            Log::info('getExams => Backend Error');
            Log::info($error->getMessage());
            dd($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
        }
    }

    public function showExams()
    {
        try {
            $exams = Exam::where('exam_created_by', Auth::user()->id)->where('status', ExamEnum::ACTIVE)->orderBy('id', 'desc')->orderBy('is_published', 'desc')->get();
            $data_generate_for_exams = $this->examHtmlGenerate($exams);
            return response()->json(array('success' => true, 'data_generate_for_exams' => $data_generate_for_exams));
        } catch (Exception $error) {
            Log::info('showExams => Backend Error');
            Log::info($error->getMessage());
            dd($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
        }
    }

    private function examHtmlGenerate($exams)
    {
        $exam_data = '';
        if (isset($exams[0])) {
            $exam_data .= '';
            $exam_data .= '<div class="row g-5">';
            foreach ($exams as $key => $exam) {
                $exam_data .= '<div class="col-xl-6 col-lg-6 col-md-6 col-12 p-2">';
                $exam_data .= '<div class="card">';
                $exam_data .= '<h5 class="card-header font-weight-bold">' . ($key + 1) . '. ' . $exam->exam_name . ' ';
                if ($exam->is_published == ExamEnum::PUBLISHED) {
                    $exam_data .= '<span class="badge badge-success">published</span>';
                } else {
                    $exam_data .= '<span class="badge badge-danger">unpublished</span>';
                }
                if ($exam->result_display == ExamEnum::AUTOMATIC_EVALUATION) {
                    $exam_data .= '<span class="badge badge-info m-2">Automatic Evaluation</span>';
                } else {
                    $exam_data .= '<span class="badge badge-primary m-2">Manual Evaluation</span>';
                }
                $exam_data .= '</h5>';
                $exam_data .= '<div class="card-body">';

                $exam_data .= '<h5 class="card-title"><span class="badge badge-pill badge-light">Due Date : ' . Carbon::parse($exam->exam_due_date)->format("d F, Y") . '</span></h5>';

                $exam_data .= '<div class="badge badge-info border-success ms-2" data-toggle="tooltip" data-placement="top" title="Submissions" style="cursor:pointer;">
                                    <a href="#" style="color:#fff;text-decoration:none;">
                                        Submissions (0)
                                    </a>
                                </div>';

                $exam_data .= '<div class="exam_infos">';
                $question_count = Question::examTotalQuestionCount($exam->id);
                if ($question_count > 0) {
                    $exam_data .= '<span class="badge badge-pill badge-primary" style="cursor:pointer;" data-toggle="modal" data-target="#view_questions_area_modal" onclick="viewQuestions(' . $exam->id . ')";>Questions : ' . $question_count . '</span>';
                } else {
                    $exam_data .= '<span class="badge badge-pill badge-primary">Questions : 0</span>';
                }
                $exam_data .= '<span class="badge badge-pill badge-info m-2">Attempts : ' . $exam->no_of_attempts . '</span>';
                $exam_data .= '<span class="badge badge-pill badge-success">Time : ' . $exam->exam_duration . ' minutes.</span>';
                $mark = ExamQuestion::getExamMarks($exam->id)->marks;
                if ($mark > 0) {
                    $exam_data .= '<span class="badge badge-pill badge-secondary m-2">Marks : ' . $mark . '</span>';
                } else {
                    $exam_data .= '<span class="badge badge-pill badge-secondary m-2">Marks : 0</span>';
                }
                $exam_data .= '</div>';

                $exam_data .= '<p class="card-text">Instructions : ' . $exam->instruction . '</p>';
                $exam_data .= '<div class="input-group mb-3"><div class="input-group-prepend">
                                <button class="btn btn-outline-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                            <div class="dropdown-menu">';
                $subbmission_count = ExamSubmission::submissionCount($exam->id);
                if ($subbmission_count <= 0) {
                    $exam_data .= '<a class="dropdown-item" style="cursor:pointer;" data-toggle="modal" data-target="#add_question_area_modal" onclick="addQuestions(' . $exam->id . ')">Add Question</a>';
                }
                $exam_data .= '<a class="dropdown-item" style="cursor:pointer;" data-toggle="modal" data-target=".edit_exam_modal" onclick="editExam(' . $exam->id . ')">Edit Exam</a>';
                $exam_data .= ' <a class="dropdown-item" href="#" style="cursor:pointer;" data-toggle="modal" data-target=".delete_exam_modal" onclick="deleteExam(' . $exam->id . ')">Delete Exam</a>';

                $exam_data .= '</div></div></div>';

                $exam_data .= '</div>';
                $exam_data .= '</div>';
                $exam_data .= '</div>';
            }
            $exam_data .= '</div>';
        } else {
            $exam_data .= '
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Oops!</strong> No exam found!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            ';
        }
        return $exam_data;
    }

    public function saveExam(Request $request)
    {
        try {
            $exam = new Exam();
            $exam->exam_name = $request->exam_name;
            $exam->exam_duration = $request->exam_duration;
            $exam->instruction = $request->instruction;
            $exam->no_of_attempts = $request->no_of_attempts;
            $exam->exam_due_date = (!empty($request->exam_due_date)) ? date('Y-m-d H:i:s', strtotime($request->exam_due_date)) : null;
            $exam->exam_start_date = (!empty($request->exam_start_date)) ? date('Y-m-d H:i:s', strtotime($request->exam_start_date)) : null;
            $exam->exam_end_date = (!empty($request->exam_end_date)) ? date('Y-m-d H:i:s', strtotime($request->exam_end_date)) : null;
            $exam->exam_created_by = Auth::user()->id;
            $exam->is_published = $request->is_published;
            $exam->result_display = $request->result_display;
            $message_data = "Exam Saved Successfully!";
            $new_exam_id = $exam->id;
            if ($exam->save()) {
                $exams = Exam::where('exam_created_by', Auth::user()->id)->where('status', ExamEnum::ACTIVE)->orderBy('id', 'desc')->orderBy('is_published', 'desc')->get();
                $data_generate = $this->examHtmlGenerate($exams);
                return response()->json(array('success' => true, 'data_generate' => $data_generate, 'message' => $message_data, 'new_exam_id' => $new_exam_id));
            } else {
                return response()->json(array('success' => false, 'message' => 'Error! Exam Not Added.'));
            }
        } catch (Exception $error) {
            Log::info('saveExam => Backend Error');
            Log::info($error->getMessage());
            dd($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
        }
    }

    public function getExamDataWithExamId(Request $request)
    {
        try {
            $exam_id = $request->exam_id;
            $exam = Exam::findOrFail($exam_id);
            if (!empty($exam)) {
                return response()->json(array(
                    'success' => true,
                    'exam' => $exam,
                ));
            } else {
                return response()->json(array('success' => false, 'message' => 'Error! No Data Found.'));
            }
        } catch (Exception $error) {
            Log::info('getExamDataWithExamId => Backend Error');
            Log::info($error->getMessage());
            dd($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later');
        }
    }

    public function updateExam(Request $request)
    {
        try {
            $exam = Exam::findOrFail($request->exam_input_edit_id);
            $exam->exam_name = $request->exam_name;
            $exam->exam_duration = $request->exam_duration;
            $exam->instruction = $request->instruction;
            $exam->no_of_attempts = $request->no_of_attempts;
            $exam->exam_due_date = (!empty($request->exam_due_date)) ? date('Y-m-d H:i:s', strtotime($request->exam_due_date)) : null;
            $exam->exam_start_date = (!empty($request->exam_start_date)) ? date('Y-m-d H:i:s', strtotime($request->exam_start_date)) : null;
            $exam->exam_end_date = (!empty($request->exam_end_date)) ? date('Y-m-d H:i:s', strtotime($request->exam_end_date)) : null;
            $exam->exam_created_by = Auth::user()->id;
            $exam->is_published = $request->is_published;
            $exam->result_display = $request->result_display;
            $message_data = "Exam Updated Successfully!";
            if ($exam->save()) {
                $exams = Exam::where('exam_created_by', Auth::user()->id)->where('status', ExamEnum::ACTIVE)->orderBy('id', 'desc')->orderBy('is_published', 'desc')->get();
                $data_generate = $this->examHtmlGenerate($exams);
                return response()->json(array('success' => true, 'data_generate' => $data_generate, 'message' => $message_data));
            } else {
                return response()->json(array('success' => false, 'message' => 'Error! Exam Not Updated.'));
            }
        } catch (Exception $error) {
            Log::info('updateExam => Backend Error');
            Log::info($error->getMessage());
            dd($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
        }
    }

    public function deleteExam(Request $request)
    {
        try {
            $exam_data = Exam::findorfail($request->delete_exam_id);
            $exam_data->status = ExamEnum::INACTIVE;
            $exam_data->save();
            $exams = Exam::where('exam_created_by', Auth::user()->id)->where('status', ExamEnum::ACTIVE)->orderBy('id', 'desc')->orderBy('is_published', 'desc')->get();
            $data_generate = $this->examHtmlGenerate($exams);
            return response()->json(array('success' => true, 'data_generate' => $data_generate, 'message' => 'Exam Deleted successfully!'));
            //return redirect()->back()->with('TOASTR_MESSAGE', MessageTypeEnum::SUCCESS . trans('Deleted successfully!'));
        } catch (Exception $error) {
            Log::info('deleteExam => Backend Error');
            Log::info($error->getMessage());
            dd($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
        }
    }

    public function addQuestionToExam(Request $request)
    {
        try {
            DB::beginTransaction();
            $exam_id = $request->exam_id;
            $questionAdd = new Question();
            $questionAdd->title = $request->title;
            $questionAdd->description = $request->description;
            $questionAdd->marks = $request->marks;
            $questionAdd->status = 1;
            if ($questionAdd->save()) {
                $new_question_id = $questionAdd->id;
                $lastExam = Exam::find($exam_id);
                $lastExam->questions()->attach($new_question_id);
                if (!empty($new_question_id)) {
                    $answer_array = $request->myAnswers;
                    foreach ($answer_array as $key => $value) {
                        $answer_title = $value['ans_title'];
                        $is_correct = $value['ans_is_correct'];
                        $answerAdd = new Answer();
                        $answerAdd->question_id = $new_question_id;
                        $answerAdd->answer_details = $answer_title;
                        $answerAdd->is_correct = $is_correct;
                        $answerAdd->save();
                    }
                    DB::commit();
                    return response()->json(array('success' => true, 'quesion_id' => $new_question_id));
                }
            } else {
                DB::rollback();
                return response()->json(array('success' => false));
            }
        } catch (Exception $error) {
            DB::rollback();
            Log::info('addQuestionToExam => Backend Error');
            Log::info($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
        }
    }

    public function viewExamQuestions(Request $request)
    {
        try {
            $exam_id = $request->exam_id;
            $view_exam_questions = '';
            $exam_questions = ExamQuestion::where('exam_id', $exam_id)->get();
            $i = 1;
            if (isset($exam_questions[0])) {
                foreach ($exam_questions as $key => $exam_question) {
                    $question_id = $exam_question->question_id;
                    $questions = Question::with(['answers'])->where('id', $question_id)->get();
                    $view_exam_questions .= '<div class="card mt-2">';

                    $view_exam_questions .= '<div class="card-header d-flex justify-content-between align-items-center">';
                    $view_exam_questions .= '<h4 class="mb-0">
                                            <span class="badge badge-primary badge-sm">MCQ-' . $i . '.</span>
                                            ' . $questions[0]->title . '
                                            </h4>';
                    $view_exam_questions .= '</div>';

                    $view_exam_questions .= '<div class="card-body">';
                    $view_exam_questions .= '<div class="row">';
                    foreach ($questions[0]->answers as $option_key => $option) {
                        $view_exam_questions .= '<div class="col-md-6 d-flex justify-content-left align-items-center">';
                        $view_exam_questions .= '<span ';
                        if ($option->is_correct == ExamEnum::CORRECT_ANSWER) {
                            $view_exam_questions .= 'class="correct-answer"';
                        } else {
                            $view_exam_questions .= 'class="wrong-answer"';
                        }
                        $view_exam_questions .= '>';
                        $view_exam_questions .= '</span>';
                        $view_exam_questions .= '<span class="ml-2">' . $option->answer_details . '</span>';
                        $view_exam_questions .= '</div>';
                    }
                    $view_exam_questions .= '</div>';
                    $view_exam_questions .= '</div>';

                    $view_exam_questions .= '<div class="modal-footer d-flex justify-content-between align-items-center">';
                    $view_exam_questions .= '<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target=".edit_question_modal" onclick="updateQuestions(' . $exam_id . ', ' . $question_id . ')";>Edit</button>';
                    $view_exam_questions .= '<h4>Mark : ' . $questions[0]->marks . '</h4>';
                    $view_exam_questions .= '</div>';

                    $view_exam_questions .= '</div>';

                    $i++;
                }
                return response()->json(array('exam_id' => $exam_id, 'view_exam_questions' => $view_exam_questions));
            } else {
                $view_exam_questions .= '
               <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Oops!</strong> No questions found!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
               ';
            }
        } catch (Exception $error) {
            Log::info('viewExamQuestions => Backend Error');
            Log::info($error->getMessage());
            dd($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
        }
    }

    public function updateExamQuestionModalShow(Request $request)
    {
        try {
            $exam_id = $request->exam_id;
            $question_id = $request->question_id;
            $view_question_for_update = '';
            $exam_question = ExamQuestion::where('exam_id', $exam_id)->where('question_id', $question_id)->first();
            $i = 1;
            if (isset($exam_question)) {
                $question_id = $exam_question->question_id;
                $question = Question::with(['answers'])->where('id', $question_id)->first();
                $view_question_for_update .= '<div class="form-row">';

                $view_question_for_update .= '<div class="col">';
                $view_question_for_update .= '<div class="form-group">';
                $view_question_for_update .= '<label class="col-form-label">Question title<sup class="text-danger font-weight-bold">*</sup></label>';
                $view_question_for_update .= '<input type="text" class="form-control title" name="title" value="' . htmlspecialchars($question->title, ENT_QUOTES, 'UTF-8') . '">';
                $view_question_for_update .= '</div>';
                $view_question_for_update .= '</div>';
                $view_question_for_update .= '<div class="col">';
                $view_question_for_update .= '<div class="form-group">';
                $view_question_for_update .= '<label class="col-form-label">Mark<sup class="text-danger font-weight-bold">*</sup></label>';
                $view_question_for_update .= '<input type="text" class="form-control mark" name="mark" value="' . $question->marks . '">';
                $view_question_for_update .= '</div>';
                $view_question_for_update .= '</div>';

                $view_question_for_update .= '</div>';

                $view_question_for_update .= '<div class="form-row">';

                $view_question_for_update .= '<div class="col">';
                $view_question_for_update .= '<div class="form-group">';
                $view_question_for_update .= '<label class="col-form-label">Description</label>';
                $view_question_for_update .= '<textarea class="form-control description" name="description" cols="30" rows="5" placeholder="description">' . $question->description . '</textarea>';
                $view_question_for_update .= '</div>';
                $view_question_for_update .= '</div>';

                $view_question_for_update .= '</div>';

                $view_question_for_update .= '<div class="form-group">';
                foreach ($question->answers as $option_key => $option) {
                    $view_question_for_update .= '<div class="input-group m-1">';
                    $view_question_for_update .= '<div class="input-group-prepend">';
                    $view_question_for_update .= '<div class="input-group-text">';
                    $view_question_for_update .= '<input type="radio" name="is_correct_update[]"';
                    ($option->is_correct == ExamEnum::CORRECT_ANSWER) ? $view_question_for_update .= 'checked' : '';
                    $view_question_for_update .= ' >';
                    $view_question_for_update .= '</div>';
                    $view_question_for_update .= '</div>';
                    $view_question_for_update .= '<input type="text" name="answer_update[]" class="form-control" value="' . htmlspecialchars($option->answer_details, ENT_QUOTES, 'UTF-8') . '">';
                    $view_question_for_update .= '</div>';
                }
                $view_question_for_update .= '</div>';

                $view_question_for_update .= '</div>';
                return response()->json(array('exam_id' => $exam_id, 'question_id' => $question_id, 'view_question_for_update' => $view_question_for_update));
            } else {
                $view_question_for_update .= '
               <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Oops!</strong> No question found!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
               ';
            }
        } catch (Exception $error) {
            Log::info('updateExamQuestionModalShow => Backend Error');
            Log::info($error->getMessage());
            dd($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
        }
    }

    public function updateExamQuestion(Request $request)
    {
        try {
            DB::beginTransaction();
            $exam_id = $request->exam_id;
            $question_id = $request->question_id;
            $questionUpdate = Question::findOrFail($question_id);
            $questionUpdate->title = $request->title;
            $questionUpdate->description = $request->description;
            $questionUpdate->marks = $request->marks;
            $questionUpdate->status = 1;
            if ($questionUpdate->save()) {
                Answer::where('question_id', $question_id)->delete();
                $answer_array = $request->myAnswers;
                foreach ($answer_array as $key => $value) {
                    $answer_title = $value['ans_title'];
                    $is_correct = $value['ans_is_correct'];
                    $answerAdd = new Answer();
                    $answerAdd->question_id = $question_id;
                    $answerAdd->answer_details = $answer_title;
                    $answerAdd->is_correct = $is_correct;
                    $answerAdd->save();
                }
                DB::commit();
                return response()->json(array('success' => true, 'exam_id' => $exam_id, 'quesion_id' => $question_id));
            } else {
                DB::rollback();
                return response()->json(array('success' => false));
            }
        } catch (Exception $error) {
            DB::rollback();
            Log::info('updateExamQuestion => Backend Error');
            Log::info($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
        }
    }
}
