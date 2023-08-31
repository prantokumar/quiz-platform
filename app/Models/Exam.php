<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $table = 'exams';

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'exam_questions', 'exam_id', 'question_id');
    }

    public function examSubmissions()
    {
        return $this->hasMany(ExamSubmission::class, 'exam_id');
    }

    public function attempts()
    {
        return $this->hasMany(ExamSubmission::class, 'exam_id');
    }

    public static function getStudentMark($exam_id, $user_id)
    {
        $currentDate = Carbon::now();
        $marks = [];
        $submissions = ExamSubmission::where('exam_id', $exam_id)->where('user_id', $user_id)->whereNotNull('obtained_marks')->get();
        if (isset($submissions[0])) {
            foreach ($submissions as $submission) {
                $assignment_collection = collect($submission->obtained_marks);
                array_push($marks, $assignment_collection);
            }
            $highest_mark = max($marks);
            return $highest_mark[0];
        } else {
            $assignment = Exam::select('exam_due_date')->where('id', $exam_id)->first();
            if (isset($assignment)) {
                $due_date = $assignment->exam_due_date;
                if ($due_date <= $currentDate) {
                    return '<span class="badge badge-danger badge-sm">Absent</span>';
                } else {
                    return '<span class="badge badge-info badge-sm">Not attempt yet</span>';
                }
            }
        }
    }
    public static function checkAssignmentHasSubmission($exam_id)
    {
        $submissions = ExamSubmission::where('exam_id', $exam_id)->exists();
        return $submissions;
    }
}
