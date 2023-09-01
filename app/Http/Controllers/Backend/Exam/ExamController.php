<?php

namespace App\Http\Controllers\Backend\Exam;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Enum\ExamEnum;
use App\Http\Controllers\Enum\MessageTypeEnum;
use App\Http\Controllers\Enum\QuestionTypeEnum;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\ExamSubmission;
use App\Models\Question;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                    $exam_data .= '<span class="badge badge-pill badge-primary">Questions : ' . $question_count . '</span>';
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

    public function getQuestionListByExamId(Request $request)
    {
        try {
            $assignment_id = $request->assignment_id;
            $question_list = Question::select('questions.*')->join('exam_questions', 'questions.id', 'exam_questions.question_id')
                ->where('exam_questions.exam_id', $assignment_id)->orderBy('questions.id', 'desc')->get();
            //dd($question_list);
            $data_generate_for_quesion_list_dropdown = '';
            $question_list_grid = '';
            $data_generate_for_quesion_list_dropdown .= '<option>choose question</option>';
            $i = 1;
            foreach ($question_list as $key => $question_list_data) {
                $question_type_id = $question_list_data->question_type_id;
                //dd($question_type_id);
                if (isset($question_type_id) && $question_type_id > 0) {

                    if ($question_type_id == QuestionTypeEnum::Multiple_Choice) {
                        $qtype = 'MCQ';
                    } else {
                        $qtype = '';
                    }

                    //dd($qtype);
                    //print($question_list->id . "##" . $question_list->search_title);
                    $data_generate_for_quesion_list_dropdown .= '<option value="' . $question_list_data->id . '">' . $question_list_data->search_title . '</option>';
                    $question_list_grid .= '<button class="nav-link q_list_grid_button text-start" id="' . $question_list_data->id . '" data-bs-toggle="pill"';
                    $question_list_grid .= 'data-bs-target="#v-pills-settings" type="button" role="tab"';
                    $question_list_grid .= 'aria-controls="v-pills-settings" aria-selected="false">';
                    $question_list_grid .= '<span class="qCount" >' . $i . ') </span><span class=" qtype badge badge-info">' . $qtype . '    </span><span class="qans">' . $question_list_data->search_title . '</span></button>';
                    $i++;
                    //dd($question_list->id . "##" . $question_list->search_title);
                    //dd('qtype = ' . $qtype);
                    //dd($question_list_grid);

                }
            }

            $total_question_count = 0;
            $total_question_count = Question::join("exam_questions", "questions.id", "exam_questions.question_id")->where("exam_questions.exam_id", $assignment_id)->count();
            $total_marks = ExamQuestion::getExamMarks($assignment_id)->marks;
            //dd('qtype = ' . $qtype);
            //dd($total_question_count);
            if ($total_question_count > 0) {
                return response()->json(array('success' => true, 'total_question_count' => $total_question_count, 'total_marks' => $total_marks, 'question_list_grid' => $question_list_grid, 'data_generate_for_quesion_list_dropdown' => $data_generate_for_quesion_list_dropdown));
            } else {
                return response()->json(array('success' => true, 'total_question_count' => $total_question_count, 'total_marks' => $total_marks));
            }
        } catch (Exception $error) {
            Log::info('getQuestionListByExamId => Backend Error');
            Log::info($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
        }
    }

}
