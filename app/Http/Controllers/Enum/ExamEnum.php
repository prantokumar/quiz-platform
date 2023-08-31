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
}
