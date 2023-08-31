<?php

namespace App\Http\Controllers\Backend\Exam;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Enum\ExamEnum;
use App\Models\Exam;
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
            $exams = Exam::where('is_published', ExamEnum::PUBLISHED)->paginate(env('PAGINATION_SMALL'));
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
            $exams = Exam::where('exam_created_by', Auth::user()->id)->orderBy('is_published', 'desc')->get();
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
                $exam_data .= '<div class="col-xl-6 col-lg-6 col-md-6 col-12">';
                $exam_data .= '<div class="card">';
                $exam_data .= '<h5 class="card-header font-weight-bold">'. ($key+1) .'. '. $exam->exam_name .' ';
                if ($exam->is_published == ExamEnum::PUBLISHED) {
                    $exam_data .= '<span class="badge badge-success">published</span>';
                }else{
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
                $exam_data .= '<span class="badge badge-pill badge-primary">Questions : 0</span>';
                $exam_data .= '<span class="badge badge-pill badge-info m-2">Attempts : '. $exam->no_of_attempts .'</span>';
                $exam_data .= '<span class="badge badge-pill badge-success">Time : ' . $exam->exam_duration . ' minutes.</span>';
                $exam_data .= '<span class="badge badge-pill badge-secondary m-2">Marks : 0</span>';
                $exam_data .= '</div>';

                $exam_data .= '<p class="card-text">Instructions : ' . $exam->instruction . '</p>';
                $exam_data .= '<div class="input-group mb-3"><div class="input-group-prepend">
                                <button class="btn btn-outline-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                            <div class="dropdown-menu">';

                    $exam_data .= '<a class="dropdown-item" href="#">Add/Edit Question</a>';
                    $exam_data .= '<a class="dropdown-item" href="#">Edit Exam</a>';
                    $exam_data .= ' <a class="dropdown-item" href="#">Delete Exam</a>';

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
}
