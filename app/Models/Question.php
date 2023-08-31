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
}
