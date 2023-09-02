<?php

namespace App\Http\Controllers\Backend\Exam;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Enum\ExamEnum;
use App\Models\ExamQuestion;
use App\Models\ExamSubmission;
use App\Models\ExamSubmissionDetail;
use App\Models\Question;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class ExamSubmissionController extends Controller
{
    public function examSubmissionDetails()
    {
        try {
            $exam_id = Crypt::decrypt(request()->get('exam_id'));
            $submissions = ExamSubmission::where('exam_id', $exam_id)->paginate(env('PAGINATION_SMALL'));
            return view('backend.pages.submissions.submissions')->with('submissions', $submissions);
        } catch (Exception $error) {
            Log::info('examSubmissionDetails => Backend Error');
            Log::info($error->getMessage());
            dd($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
        }
    }

    public function generateSubmissionDetails(Request $request)
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
                                        $data_generate .= ' <span class="badge badge-sm badge-secondary">user answer</span>';
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
            Log::info('generateSubmissionDetails => Backend Error');
            Log::info($error->getMessage());
            dd($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
        }
    }
}
