<?php
use Cake\I18n\I18n;


function timeAgoInWords($timestring, $timezone = NULL) {
    $timeAgo = new TimeAgo($timezone);
    $locale = (I18n::locale() == "mn")?'mn':'en';
    return $timeAgo->inWords($timestring, "now", $locale);
}

/**
* This class can help you find out just how much time has passed between
* two dates.
*
* It has two functions you can call:
* inWords() which gives you the "time ago in words" between two dates.
* dateDifference() which returns an array of years,months,days,hours,minutes and
* seconds between the two dates.
*
* @author jimmiw
* @since 0.2.0 (2010/05/05)
* @site http://github.com/jimmiw/php-time-ago
*/
class TimeAgo {
    
    // defines the number of seconds per "unit"
    private $secondsPerMinute = 60;
    private $secondsPerHour = 3600;
    private $secondsPerDay = 86400;
    private $secondsPerMonth = 2592000;
    private $secondsPerYear = 31104000;
    private $timezone;
    
    private function isDST(){
        $month = date('n');
        if ($month > 3 && $month < 9) {
            return true;
        }
        if ($month < 3 || $month > 9) {
            return false;
        }
        $weekDay = date('w');
        $monthDay = date('j');
        $mDay;
        $timezone = new DateTimeZone('Asia/Ulaanbaatar');
        if ($month == 3) {
            $mDay = date_create(date('Y')."-03-31 02:00:00", $timezone);
        } else {
            $mDay = date_create(date('Y')."-09-30 00:00:00", $timezone);
        }
        
        $mWeekDay = date_format($mDay, 'w');
        if ($mWeekDay == 0) {
            date_sub($mDay,date_interval_create_from_date_string("2 days"));
        } else if ($mWeekDay == 6) {
            date_sub($mDay,date_interval_create_from_date_string("1 day"));
        } else if ($mWeekDay != 5) {
            date_sub($mDay,date_interval_create_from_date_string((1+$mWeekDay) ." days"));
        }
        
        if ($month == 3) {
            return date_create() > $mDay;
        } else {
            return date_create() < $mDay;
        }
    }
    
    public function __construct($timezone = NULL) {
        // if the $timezone is null, we take 'Europe/London' as the default
        // this was done, because the parent construct tossed an exception
        if ($timezone == NULL) {
            $offset = 8; // GMT offset
            $is_DST = $this->isDST(); // observing daylight savings?
            $timezone = timezone_name_from_abbr('', $offset * 3600, $is_DST); // e.g. 'Asia/Ulaanbaatar'
        }
        
        $this->timezone = $timezone;
    }
    
    public function inWords($past, $now = "now", $locale='mn') {
        $_now = $now;
        // sets the default timezone
        date_default_timezone_set($this->timezone);
        // finds the past in datetime
        $past_temp = $past;
        $past = strtotime($past);
        // finds the current datetime
        $now = strtotime($now);
        
        // creates the "time ago" string. This always starts with an "about..."
        $timeAgo = "";
        
        // finds the time difference
        $timeDifference = $now - $past;
        
        if ($timeDifference <= (($this->secondsPerHour * 23) + ($this->secondsPerMinute * 59) + 29)) {
            $nowDay = (int)date_format(date_create($_now), 'j');
            $pastDay = (int)date_format(date_create($past_temp), 'j');
            if($pastDay == $nowDay) {
              $timeAgo = $locale=='mn'?"өнөөдөр":"today";
            } else {
              $timeAgo = $locale=='mn'?"өчигдөр":"yesterday";
            }
        }
        // between 23hours59mins30secs and 47hours59mins29secs
        else if (
            $timeDifference > (
        ($this->secondsPerHour * 23) +
        ($this->secondsPerMinute * 59) +
        29
        ) &&
        $timeDifference <= (
        ($this->secondsPerHour * 47) +
        ($this->secondsPerMinute * 59) +
        29
        )
        ) 
          {
            $nowDay = (int)date_format(date_create($_now), 'j');
            $pastDay = (int)date_format(date_create($past_temp), 'j');
            if($pastDay + 1 == $nowDay){
                $timeAgo = $locale=='mn'?"өчигдөр":"1 day";
            }else{
                $timeAgo = $locale=='mn'?"2 өдрийн өмнө":"2 days";
            }
        }
        // between 47hours59mins30secs and 29days23hours59mins29secs
//        else if (
//            $timeDifference > (
//        ($this->secondsPerHour * 47) +
//        ($this->secondsPerMinute * 59) +
//        29
//        ) &&
//        $timeDifference <= (
//        ($this->secondsPerDay * 29) +
//        ($this->secondsPerHour * 23) +
//        ($this->secondsPerMinute * 59) +
//        29
//        )
//        ) {
//            $days = ceil($timeDifference / $this->secondsPerDay);
//            $timeAgo = $days . ($locale=='mn'?" өдрийн өмнө":" days");
//        }
        // between 29days23hours59mins30secs and 59days23hours59mins29secs
        //    else if(
        //      $timeDifference > (
        //        ($this->secondsPerDay * 29) +
        //        ($this->secondsPerHour * 23) +
        //        ($this->secondsPerMinute * 59) +
        //        29
        //      )
        //      &&
        //      $timeDifference <= (
        //        ($this->secondsPerDay * 59) +
        //        ($this->secondsPerHour * 23) +
        //        ($this->secondsPerMinute * 59) +
        //        29
        //      )
        //    ) {
        //      $timeAgo = "about 1 month";
        //    }
        //    // between 59days23hours59mins30secs and 1year (minus 1sec)
        //    else if(
        //      $timeDifference > (
        //        ($this->secondsPerDay * 59) +
        //        ($this->secondsPerHour * 23) +
        //        ($this->secondsPerMinute * 59) +
        //        29
        //      )
        //      &&
        //      $timeDifference < $this->secondsPerYear
        //    ) {
        //      $months = round($timeDifference / $this->secondsPerMonth);
        //      // if months is 1, then set it to 2, because we are "past" 1 month
        //      if($months == 1) {
        //        $months = 2;
        //      }
        //
        //      $timeAgo = $months." months";
        //    }
        //    // between 1year and 2years (minus 1sec)
        //    else if(
        //      $timeDifference >= $this->secondsPerYear
        //      &&
        //      $timeDifference < ($this->secondsPerYear * 2)
        //    ) {
        //      $timeAgo = "about 1 year";
        //    }
        // 2years or more
        else {
            //$years = floor($timeDifference / $this->secondsPerYear);
            //$timeAgo = "over ".$years." years";
            $date_past = date_create($past_temp);
            // $timeAgo = date_format($date_past, $locale=='mn'?"Y оны m сарын d":'F jS, Y');
            $timeAgo = date_format($date_past, "Y.m.d");
        }
        
        return $timeAgo;
    }
    
    //  public function dateDifference($past, $now = "now") {
    //    // initializes the placeholders for the different "times"
    //    $seconds = 0;
    //    $minutes = 0;
    //    $hours = 0;
    //    $days = 0;
    //    $months = 0;
    //    $years = 0;
    //
    //    // sets the default timezone
    //    date_default_timezone_set($this->timezone);
    //
    //    // finds the past in datetime
    //    $past = strtotime($past);
    //    // finds the current datetime
    //    $now = strtotime($now);
    //
    //    // calculates the difference
    //    $timeDifference = $now - $past;
    //
    //    // starts determining the time difference
    //    if($timeDifference >= 0) {
    //      switch($timeDifference) {
    //        // finds the number of years
    //        case ($timeDifference >= $this->secondsPerYear):
    //          // uses floor to remove decimals
    //          $years = floor($timeDifference / $this->secondsPerYear);
    //          // saves the amount of seconds left
    //          $timeDifference = $timeDifference-($years * $this->secondsPerYear);
    //
    //        // finds the number of months
    //        case ($timeDifference >= $this->secondsPerMonth && $timeDifference <= ($this->secondsPerYear-1)):
    //          // uses floor to remove decimals
    //          $months = floor($timeDifference / $this->secondsPerMonth);
    //          // saves the amount of seconds left
    //          $timeDifference = $timeDifference-($months * $this->secondsPerMonth);
    //
    //        // finds the number of days
    //        case ($timeDifference >= $this->secondsPerDay && $timeDifference <= ($this->secondsPerYear-1)):
    //          // uses floor to remove decimals
    //          $days = floor($timeDifference / $this->secondsPerDay);
    //          // saves the amount of seconds left
    //          $timeDifference = $timeDifference-($days * $this->secondsPerDay);
    //
    //        // finds the number of hours
    //        case ($timeDifference >= $this->secondsPerHour && $timeDifference <= ($this->secondsPerDay-1)):
    //          // uses floor to remove decimals
    //          $hours = floor($timeDifference / $this->secondsPerHour);
    //          // saves the amount of seconds left
    //          $timeDifference = $timeDifference-($hours * $this->secondsPerHour);
    //
    //        // finds the number of minutes
    //        case ($timeDifference >= $this->secondsPerMinute && $timeDifference <= ($this->secondsPerHour-1)):
    //          // uses floor to remove decimals
    //          $minutes = floor($timeDifference / $this->secondsPerMinute);
    //          // saves the amount of seconds left
    //          $timeDifference = $timeDifference-($minutes * $this->secondsPerMinute);
    //
    //        // finds the number of seconds
    //        case ($timeDifference <= ($this->secondsPerMinute-1)):
    //          // seconds is just what there is in the timeDifference variable
    //          $seconds = $timeDifference;
    //      }
    //    }
    //
    //    $difference = array(
    //      "years" => $years,
    //      "months" => $months,
    //      "days" => $days,
    //      "hours" => $hours,
    //      "minutes" => $minutes,
    //      "seconds" => $seconds
    //    );
    //
    //    return $difference;
    //  }
}

?>