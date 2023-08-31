<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ExamQuestion extends Model
{
    use HasFactory;

    protected $table = 'exam_questions';

    public static function getExamDetail($exam_id)
    {
        $exam_details = Exam::select('exam_name', 'exam_duration', 'result_display')
            ->where('id', $exam_id)
            ->first();
        return $exam_details;
    }

    public static function getExamMarks($exam_id)
    {
        $exam_marks = ExamQuestion::join('questions', 'exam_questions.question_id', 'questions.id')
            ->select(DB::raw("SUM(questions.marks) AS marks"))
            ->where('exam_questions.exam_id', $exam_id)
            ->first();
        if (isset($exam_marks)) {
            return $exam_marks;
        } else {
            return 0;
        }
    }

    public static function getExamQuestions($exam_id)
    {
        $exam_questions = ExamQuestion::where('exam_id', $exam_id)->get();
        return $exam_questions;
    }
}
