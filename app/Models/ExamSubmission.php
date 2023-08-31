<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSubmission extends Model
{
    use HasFactory;

    protected $table = 'exam_submissions';

    public static function examSubmissions($exam_id, $user_id)
    {
        $exam_submissions = ExamSubmission::where('exam_id', $exam_id)->where('user_id', $user_id)->get();
        return $exam_submissions;
    }
    public static function submissionCount($exam_id)
    {
        $exam_submission = ExamSubmission::where('exam_id', $exam_id)->get();
        $exam_submission_count = $exam_submission->count();
        return $exam_submission_count;
    }
    public static function examSubmissionTime($exam_id, $user_id)
    {
        $exam = Exam::select('exam_due_date')->where('id', $exam_id)->first();
        $exam_due_date = $exam->exam_due_date;
        $exam_submissions = ExamSubmission::where('exam_id', $exam_id)->where('user_id', $user_id)->get();
        foreach ($exam_submissions as $key => $exam_submission) {
            $exam_submission_date = $exam_submission->submission_date;
            if ($exam_submission_date >= $exam_due_date) {
                $late_submission = true;
            } else {
                $late_submission = false;
            }
        }
        return $late_submission;
    }
    public static function getLastExamSubmissionDetails($exam_id, $user_id)
    {
        $last_submission = ExamSubmission::select('obtained_marks', 'submission_date')->where('exam_id', $exam_id)->where('exam_id', $exam_id)->get()->last();
        return $last_submission;
    }
}
