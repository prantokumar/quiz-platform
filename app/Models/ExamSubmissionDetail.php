<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSubmissionDetail extends Model
{
    use HasFactory;

    protected $table = 'exam_submission_details';

    public static function getAnswers($attempt_id)
    {
        $answers = ExamSubmissionDetail::select('exam_submission_id as attempt_id', 'question_id', 'answer_id')->where('exam_submission_id', $attempt_id)->get();
        return $answers;
    }
    public static function getOnlyFileAndBroadAnswers($attempt_id)
    {
        $answers = ExamSubmissionDetail::select('exam_submission_id as attempt_id', 'question_id', 'answer_id')->where('answer_id', 0)->where('exam_submission_id', $attempt_id)->get();
        return $answers;
    }
}
