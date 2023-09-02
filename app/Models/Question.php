<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'questions';

    public function answers()
    {
        return $this->hasMany(Answer::class, 'question_id')->select(['id', 'question_id', 'answer_details', 'is_correct']);
    }
    public function options()
    {
        return $this->hasMany(Answer::class, 'question_id');
    }
    public function exam()
    {
        return $this->belongsToMany(Exam::class, 'exam_questions', 'question_id', 'exam_id');
    }
    public static function examTotalQuestionCount($exam_id)
    {
        $total_question = Question::join("exam_questions", "questions.id", "exam_questions.question_id")->where("exam_questions.exam_id", $exam_id)->count();
        return $total_question;
    }
    public static function getQuestions($question_id)
    {
        $questions = Question::with(['options' => function ($answer) {
            $answer->select('id', 'question_id', 'answer_details', 'is_correct');
        }])->where('id', $question_id)->get();
        return $questions;
    }
    public static function checkAnswerIsCorrectOld($exam_id, $attempt_id)
    {
        $array = [];
        $exam_questions = ExamQuestion::where('exam_id', $exam_id)->get();
        $answers = ExamSubmissionDetail::select('exam_submission_id as attempt_id', 'question_id', 'answer_id')->where('exam_submission_id', $attempt_id)->get();
        foreach ($exam_questions as $key => $exam_question) {
            $question_id = $exam_question->question_id;
            $question_id_collection = collect($question_id);
            $questions = Question::with(['options' => function ($answer) {
                $answer->select('id', 'question_id', 'answer_details', 'is_correct');
            }])->where('id', $question_id)->get();
            foreach ($questions as $question) {
                $intial_wrong_answer = 0;
                foreach ($question->options as $option) {
                    $option_id = $option->id;
                    $intial_right_answer = 0;
                    foreach ($answers as $answer) {
                        $question_id = $answer->question_id;
                        if (($answer->answer_id == $option_id) && ($answer->question_id == $option->question_id)) {
                            $intial_right_answer = 1;
                            if ($option->is_correct == 1) {
                                break;
                            } else {
                                $intial_wrong_answer = 1;
                                break;
                            }
                        }
                    }
                    if (($intial_right_answer == 0) && ($option->is_correct == 1)) {
                        $intial_wrong_answer = 1;
                    }
                }
                if ($intial_wrong_answer == 1) {
                    $question_id_collection->put('obtained_marks', 0);
                    array_push($array, $question_id_collection);
                } else {
                    $question_id_collection->put('obtained_marks', $question->marks);
                    array_push($array, $question_id_collection);
                }
            }
        }
        return $array;
    }

    public static function checkAnswerIsCorrect($exam_id, $attempt_id)
    {
        $total_obtained_marks = 0;
        $array = [];
        $exam_questions = ExamQuestion::where('exam_id', $exam_id)->get();
        $answers = ExamSubmissionDetail::select('exam_submission_id as attempt_id', 'question_id', 'answer_id')->where('exam_submission_id', $attempt_id)->get();

        foreach ($exam_questions as $key => $exam_question) {
            $question_id = $exam_question->question_id;
            $question_id_collection = collect($question_id);
            $questions = Question::with(['options' => function ($answer) {
                $answer->select('id', 'question_id', 'answer_details', 'is_correct');
            }])->where('id', $question_id)->get();

            foreach ($questions as $question) {
                $intial_wrong_answer = 0;

                foreach ($question->options as $option) {
                    $option_id = $option->id;
                    $intial_right_answer = 0;

                    foreach ($answers as $answer) {
                        $question_id = $answer->question_id;
                        if (($answer->answer_id == $option_id) && ($answer->question_id == $option->question_id)) {
                            $intial_right_answer = 1;
                            if ($option->is_correct == 1) {
                                break;
                            } else {
                                $intial_wrong_answer = 1;
                                break;
                            }
                        }
                    }

                    if (($intial_right_answer == 0) && ($option->is_correct == 1)) {
                        $intial_wrong_answer = 1;
                    }
                }

                if ($intial_wrong_answer == 1) {
                    $question_id_collection->put('obtained_marks', 0);
                    array_push($array, $question_id_collection);
                } else {
                    $question_id_collection->put('obtained_marks', $question->marks);
                    array_push($array, $question_id_collection);
                    $total_obtained_marks += $question->marks; // Add the marks to the total obtained marks
                }
            }
        }

        // Add the total obtained marks to the result
        $result = [
            'questions' => $array,
            'total_obtained_marks' => $total_obtained_marks,
        ];

        return $result;
    }
}
