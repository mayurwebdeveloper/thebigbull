<?php

namespace App\Helpers;

class Helper {

    public static function formatDate($date, $formatString) {
      
        // Check if $date is a string
        if (is_string($date)) {
            // Convert string to a DateTime object using strtotime() or Carbon
            return date($formatString, strtotime($date));
        } else {
            // $date is already a DateTime object
            return $date->format($formatString);
        }

    }

    public static function userAccessOr(...$permissions) {

        $access = false;
        foreach ($permissions as $permission) {
            if (auth()->user()->can(trim($permission, "'"))) {
                $access = true;
                break;
            }
        }
        return $access;
    }
     
}