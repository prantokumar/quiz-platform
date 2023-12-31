<?php

namespace App\Http\Controllers\Enum;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExamEnum extends Controller
{
    const UNPUBLISHED = 0;
    const PUBLISHED = 1;

    const AUTOMATIC_EVALUATION = 1;
    const MANUAL_EVALUATION = 2;

    const INACTIVE = 0;
    const ACTIVE = 1;

    const CORRECT_ANSWER = 1;
    const WRONG_ANSWER = 0;
}
