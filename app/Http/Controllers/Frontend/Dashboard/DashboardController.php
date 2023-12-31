<?php

namespace App\Http\Controllers\Frontend\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Enum\ExamEnum;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\ExamSubmission;
use App\Models\ExamSubmissionDetail;
use App\Models\Question;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Ui\Presets\React;

class DashboardController extends Controller
{
    //admin dashboard page view
    public function userDashboard()
    {
        try {
            $exams = Exam::where('status', ExamEnum::ACTIVE)->orderBy('id', 'desc')->orderBy('is_published', 'desc')->get();
            return view('frontend.pages.dashboard.dashboard')->with(['exams' => $exams]);
        } catch (Exception $error) {
            Log::info('userDashboard => frontend Error');
            Log::info($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
        }
    }
    //admin dashboard page view

    public function showExamsForUser()
    {
        try {
            $exams = Exam::where('status', ExamEnum::ACTIVE)->orderBy('id', 'desc')->orderBy('is_published', 'desc')->get();
            $data_generate_for_exams = $this->examHtmlGenerate($exams);
            return response()->json(array('success' => true, 'data_generate_for_exams' => $data_generate_for_exams));
        } catch (Exception $error) {
            Log::info('showExamsForUser => FrontEnd Error');
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
                $exam_data .= '</h5>';
                $exam_data .= '<div class="card-body">';

                $exam_data .= '<h5 class="card-title"><span class="badge badge-pill badge-light">Due Date : ' . Carbon::parse($exam->exam_due_date)->format("d F, Y") . '</span></h5>';

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

                $exam_data .= '</div>';

                $exam_data .= '<div class="modal-footer d-flex justify-content-between align-items-center">';
                $user_exam_submission_count = ExamSubmission::userExamSubmissionsCount($exam->id, Auth::user()->id);
                if ($exam->no_of_attempts == $user_exam_submission_count) {
                    $exam_data .= '<button type="button" class="btn btn-danger btn-sm" disabled>Attempts Over</button>';
                } else {
                    $exam_data .= '<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target=".quiz_start_confirmation_modal" onclick="showQuizStartConfirmation(' . $exam->id . ')";>Start Quiz</button>';
                }
                if ($user_exam_submission_count > 0) {
                    $exam_data .= '<a href="' . route('examSubmissionDetailsForUser', ['exam_id' => encrypt($exam->id), 'user_id' => encrypt(Auth::user()->id)]) . '" class="btn btn-primary btn-sm ml-2">View Submissions (' . $user_exam_submission_count . ')</a>';
                }
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

    public function viewExamQuestionsForUser(Request $request)
    {
        try {
            $exam_id = $request->exam_id;
            $exam_duration = Exam::getExamDuration($exam_id);
            $view_exam_questions = '';
            $exam_questions = ExamQuestion::where('exam_id', $exam_id)->get();
            $i = 1;
            if (isset($exam_questions[0])) {
                foreach ($exam_questions as $key => $exam_question) {
                    $question_id = $exam_question->question_id;
                    $questions = Question::with(['answers'])->where('id', $question_id)->where('status', 1)->get();
                    $view_exam_questions .= '<div class="card mt-2">';

                    $view_exam_questions .= '<div class="card-header d-flex justify-content-between align-items-center">';
                    $view_exam_questions .= '<h4 class="mb-0">
                                            <span class="badge badge-primary badge-sm">Question-' . $i . '</span>
                                            ' . $questions[0]->title . '
                                            </h4>';
                    $view_exam_questions .= '</div>';

                    $view_exam_questions .= '<div class="card-body">';
                    $view_exam_questions .= '<input type="hidden" name="exam_id" class="form-control exam_id" value="' . $exam_id . '">';
                    $view_exam_questions .= '<div class="form-group">';
                    $view_exam_questions .= '<input type="hidden" name="question_id" class="form-control question_id" value="' . $question_id . '">';

                    $question_radio_name = 'answer_id_' . $question_id;

                    foreach ($questions[0]->answers as $option_key => $option) {
                        $view_exam_questions .= '<div class="input-group m-1">';
                        $view_exam_questions .= '<div class="input-group-prepend">';
                        $view_exam_questions .= '<div class="input-group-text">';
                        $view_exam_questions .= '<input type="radio" name="' . $question_radio_name . '" data-option-key="' . $option->id . '" class="" value="' . $option->id . '">';
                        $view_exam_questions .= '</div>';
                        $view_exam_questions .= '</div>';
                        $view_exam_questions .= '<input type="text" class="form-control" disabled value="' . htmlspecialchars($option->answer_details, ENT_QUOTES, 'UTF-8') . '">';
                        $view_exam_questions .= '</div>';
                    }
                    $view_exam_questions .= '</div>';
                    $view_exam_questions .= '</div>';

                    $view_exam_questions .= '<div class="modal-footer d-flex justify-content-between align-items-center">';
                    $view_exam_questions .= '<h4>Mark : ' . $questions[0]->marks . '</h4>';
                    $view_exam_questions .= '</div>';

                    $view_exam_questions .= '</div>';

                    $i++;
                }
                return response()->json(array('exam_id' => $exam_id, 'exam_duration' => $exam_duration , 'view_exam_questions' => $view_exam_questions));
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

    public function submitQuiz(Request $request)
    {
        try {
            DB::beginTransaction();
            $examSubmission = new ExamSubmission();
            $examSubmission->exam_id = $request->exam_id;
            $examSubmission->user_id = Auth::user()->id;
            $examSubmission->submission_date =  Carbon::now();
            $examSubmission->status = 1;
            $examSubmission->is_running = 1;
            if ($examSubmission->save()) {
                $exam_submission_id = $examSubmission->id;
                $exam_submission_details = $request->question_answers;
                if (isset($exam_submission_details[0])) {
                    foreach ($exam_submission_details as $exam_submission_detail) {
                        $question_id = $exam_submission_detail['question_id'];
                        $answer_id = isset($exam_submission_detail['answer_id']) ? $exam_submission_detail['answer_id'] : null;
                        $answerAdd = new ExamSubmissionDetail();
                        $answerAdd->exam_submission_id = $exam_submission_id;
                        $answerAdd->question_id = $question_id;
                        $answerAdd->answer_id = $answer_id;
                        $answerAdd->save();
                    }
                    $this->updateObtainedMarksOfUser($request, $exam_submission_id);
                    DB::commit();
                    return response()->json(array('success' => true, 'exam_id' => $request->exam_id, 'exam_submission_id' => $exam_submission_id));
                } else {
                    DB::rollback();
                    return response()->json(array('error' => 'no_question_answer'));
                }
            }
        } catch (Exception $error) {
            Log::info('submitQuiz => Backend Error');
            Log::info($error->getMessage());
            dd($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
        }
    }

    private function updateObtainedMarksOfUser(Request $request, $exam_submission_id)
    {
        $check_answers = Question::checkAnswerIsCorrect($request->exam_id, $exam_submission_id);
        $totalObtainedMarks = $check_answers['total_obtained_marks'];
        $exam_submission = ExamSubmission::where('id', $exam_submission_id)->first();
        $exam_submission->obtained_marks = $totalObtainedMarks;
        $exam_submission->save();
        return $exam_submission;
    }

    public function examSubmissionDetailsForUser()
    {
        try {
            $exam_id = Crypt::decrypt(request()->get('exam_id'));
            $user_id = Crypt::decrypt(request()->get('user_id'));
            $submissions = ExamSubmission::where('exam_id', $exam_id)->where('user_id', $user_id)->paginate(env('PAGINATION_SMALL'));
            return view('frontend.pages.submissions.submissions')->with('submissions', $submissions);
        } catch (Exception $error) {
            Log::info('examSubmissionDetailsForUser => Frontend Error');
            Log::info($error->getMessage());
            dd($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
        }
    }

    public function generateSubmissionDetailsForUser(Request $request)
    {
        try {
            $exam_id = $request->exam_id;
            $user_id = $request->user_id;
            $submission_id = $request->submission_id;
            $data_generate = '';
            $submissions = ExamSubmission::examSubmissions($exam_id, $user_id);
            $exam_questions = ExamQuestion::getExamQuestions($exam_id);
            $answers = ExamSubmissionDetail::getAnswers($submission_id);
            $i = 1;
            if (isset($exam_questions[0])) {
                foreach ($exam_questions as $key => $exam_question) {
                    $question_id = $exam_question->question_id;
                    $questions = Question::getQuestions($question_id);

                    foreach ($questions as $key => $question) {

                        $data_generate .= '<div class="card mt-2">';

                        $data_generate .= '<div class="card-header d-flex justify-content-between align-items-center">';
                        $data_generate .= '<h4 class="mb-0">
                                                <span class="badge badge-primary badge-sm">MCQ-' . $i . '.</span>
                                                ' . $question->title . '
                                                </h4>';
                        $data_generate .= '</div>';

                        $data_generate .= '<div class="card-body">';
                        $data_generate .= '<div class="row">';

                        if (isset($question->options[0])) {
                            foreach ($question->options as $option) {
                                $option_id = $option->id;
                                $correct = 0;
                                //check if answer is correct
                                foreach ($answers as $ans_key => $answer) {
                                    if ($answer->answer_id == $option_id) {
                                        $correct = 1;
                                    }
                                }
                                //check if answer is correct

                                $data_generate .= '<div class="col-md-6 d-flex justify-content-left align-items-center">';
                                $data_generate .= '<span ';
                                $data_generate .= 'class="';
                                if ($option->is_correct == 1) {
                                    $data_generate .= 'correct-answer';
                                } else {
                                    $data_generate .= 'wrong-answer';
                                }
                                if ($correct == 1) {
                                    $data_generate .= ' user-given-answer';
                                }
                                $data_generate .= '"';

                                $data_generate .= '>';
                                $data_generate .= '</span>';
                                $data_generate .= '<span class="ml-2">' . $option->answer_details . '';
                                if ($option->is_correct == 1) {
                                    $data_generate .= ' <span class="badge badge-sm badge-success">correct answer</span>';
                                } else {
                                    if ($correct == 1) {
                                        $data_generate .= ' <span class="badge badge-sm badge-secondary">your answer</span>';
                                    }
                                }
                                $data_generate .= '</span>';
                                $data_generate .= '</div>';
                            }
                        }

                        $data_generate .= '</div>';
                        $data_generate .= '</div>';

                        $data_generate .= '<div class="modal-footer d-flex justify-content-between align-items-center">';
                        $data_generate .= '<h4>Mark : ' . $questions[0]->marks . '</h4>';
                        $check_answers = Question::checkAnswerIsCorrect($exam_id, $submission_id);
                        $totalObtainedMarks = $check_answers['total_obtained_marks'];
                        $data_generate .= '<h4>Obtained Mark : ';
                        if (isset($check_answers['questions'])) {
                            foreach ($check_answers['questions'] as $questionData) {
                                if ($questionData[0] == $question_id) {
                                    if (isset($questionData['obtained_marks'])) {
                                        $data_generate .= '' . $questionData['obtained_marks'] . '';
                                    }
                                }
                            }
                        }
                        $data_generate .= '</h4>';
                        $data_generate .= '</div>';

                        $data_generate .= '</div>';
                    }

                    $i++;
                }
                return response()->json(array('exam_id' => $exam_id, 'data_generate' => $data_generate, 'totalObtainedMarks' =>  $totalObtainedMarks));
            } else {
                $data_generate .= '
               <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Oops!</strong> No submission found!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
               ';
            }
        } catch (Exception $error) {
            Log::info('generateSubmissionDetailsForUser => Frontend Error');
            Log::info($error->getMessage());
            dd($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
        }
    }
}
