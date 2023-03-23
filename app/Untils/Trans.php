<?php
namespace App\Untils;
class Trans
{
    public static function Week($key)
    {
        switch ($key) {
            case "mon":
                return "Thứ hai";
            case "tue":
                return "Thứ ba";
            case "wed":
                return "Thứ tư";
            case "thu":
                return "Thứ năm";
            case "fri":
                return "Thứ sáu";
            case "sat":
                return "Thứ bảy";
            case "sun":
                return "Chủ nhật";
        }
    }
}
