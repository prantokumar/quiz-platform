<?php

namespace App;

use App\Http\Controllers\Enum\MessageTypeEnum;
use DateTime;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Laravel\Sanctum\PersonalAccessToken;

class UtilityFunction
{
    public static function getToastrMessage($message)
    {
        $message_array = explode("::", $message);
        if (sizeof($message_array) < 2 && $message_array[0] != "") {
            $message_array[1] = $message_array[0];
            $message_array[0] = MessageTypeEnum::SUCCESS;
        }
        $toastr = "";
        $message_array[0] = $message_array[0] . MessageTypeEnum::SEPARATOR;

        $toastr_configuration = '
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": true,
                "onclick": null,
                "showDuration": "200",
                "hideDuration": "1000",
                "timeOut": "3000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
        ';
        if ($message_array[0] == MessageTypeEnum::SUCCESS)
            $toastr = 'toastr.success("' . $message_array[1] . '", "Success")';
        elseif ($message_array[0] == MessageTypeEnum::ERROR)
            $toastr = 'toastr.danger("' . $message_array[1] . '", "Error")';
        elseif ($message_array[0] == MessageTypeEnum::WARNING)
            $toastr = 'toastr.warning("' . $message_array[1] . '", "Warning")';
        elseif ($message_array[0] == MessageTypeEnum::INFO)
            $toastr = 'toastr.info("' . $message_array[1] . '", "Info")';

        return "<script>" . $toastr_configuration . $toastr . "</script>";
    }
}
