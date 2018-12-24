<?php


namespace Telebot\Addons;


class Functions
{
    public static function declofnum($number, $titles){
        $cases = array (2, 0, 1, 1, 1, 2);
        return $number." ".$titles[ ($number%100 > 4 && $number %100 < 20) ? 2 : $cases[min($number%10, 5)] ];
    }

    public static function genLetters($length){
        $code = '';
        $letters = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890";
        while($length--)
            $code .= $letters{rand(0,strlen($letters)-1)};
        return $code;
    }

    public static function ctime($name){
        if(isset($GLOBALS['microtime'][$name])){
            $start = $GLOBALS['microtime'][$name];
            unset($GLOBALS['microtime'][$name]);
            return '('.round(microtime(true) - $start, 3)." sec)";
        }else{
            $GLOBALS['microtime'][$name] = microtime(true);
        }
    }

    public static function is_int2($var){ //Проверяет состоит ли текст только из чисел
        return (bool)!strcmp((int)$var,$var);
    }

    public static function time_difference($time, $params='ymdhis')
    {
        $YEAR   = 31536000;
        $MONTH  = 2592000;
        $WEEK   = 604800;
        $DAY    = 86400;
        $HOUR   = 3600;
        $MINUTE = 60;
        $result = [];

        if($time < 0)
        {
            $result['invert'] = false;
        }else{
            $result['invert'] = true;
        }

        $time = abs($time);

        if(strpos($params, 'y') !== false)
        {
            $result['y'] = floor( $time / $YEAR );
            $time = $time - ( $result['y'] * $YEAR );
        }

        if(strpos($params, 'm') !== false)
        {
            $result['m'] = floor( $time / $MONTH );
            $time = $time - ( $result['m'] * $MONTH );
        }

        if(strpos($params, 'w') !== false)
        {
            $result['w'] = floor( $time / $WEEK );
            $time = $time - ( $result['w'] * $WEEK );
        }

        if(strpos($params, 'd') !== false)
        {
            $result['d'] = floor( $time / $DAY );
            $time = $time - ( $result['d'] * $DAY );
        }

        if(strpos($params, 'h') !== false)
        {
            $result['h'] = floor( $time / $HOUR );
            $time = $time - ( $result['h'] * $HOUR );
        }

        if(strpos($params, 'i') !== false)
        {
            $result['i'] = floor( $time / $MINUTE );
            $time = $time - ( $result['i'] * $MINUTE );
        }

        if(strpos($params, 's') !== false)
        {
            $result['s'] = $time;
        }

        return $result;
    }

    public static function time_difference_string($time, $params='ymdhis'){
        $diff = self::time_difference($time, $params);

        foreach($diff AS $key => $value)
        {
            switch($key){
                case 's':
                    if(!$value) break;
                    $add[] = self::declofnum($value, ['секунда', 'секунды', 'секунд']);
                    break;
                case 'i':
                    if(!$value) break;
                    $add[] = self::declofnum($value, ['минута', 'минуты', 'минут']);
                    break;
                case 'h':
                    if(!$value) break;
                    $add[] = self::declofnum($value, ['час', 'часа', 'часов']);
                    break;
                case 'd':
                    if(!$value) break;
                    $add[] = self::declofnum($value, ['день', 'дня', 'дней']);
                    break;
                case 'w':
                    if(!$value) break;
                    $add[] = self::declofnum($value, ['неделя', 'недели', 'недель']);
                    break;
                case 'm':
                    if(!$value) break;
                    $add[] = self::declofnum($value, ['месяц', 'месяца', 'месяцев']);
                    break;
                case 'y':
                    if(!$value) break;
                    $add[] = self::declofnum($value, ['год', 'года', 'лет']);
                    break;
            }
        }
        $text = '';
        $count = count($add);
        for($i=0;$i<$count;$i++)
        {
            if($i+2==$count)
            {
                $text .= $add[$i].' и ';
            }elseif($i+1==$count)
            {
                $text .= $add[$i];
            }else{
                $text .= $add[$i].', ';
            }
        }
        return $text;
    }

    public static function strtosec($string){
        $YEAR   = 31536000;
        $MONTH  = 2592000;
        $WEEK   = 604800;
        $DAY    = 86400;
        $HOUR   = 3600;
        $MINUTE = 60;

        $result = 0;

        $number = 0;
        $symb = null;
        foreach(explode(' ', $string) AS $str){
            if(strpos($str, '+') === 0)
            {
                $symb = true;
                $number = substr($str, 1);
            }elseif(strpos('-', $str) === 0){
                $symb = false;
                $number = substr($str, 1);
            }elseif(strpos($str, 'second') === 0){
                if($symb)
                {
                    $result += $number;
                }else{
                    $result -= $number;
                }
            }elseif(strpos($str, 'minute') === 0){
                if($symb)
                {
                    $result += $MINUTE*$number;
                }else{
                    $result -= $MINUTE*$number;
                }
            }elseif(strpos($str, 'hour') === 0){
                if($symb)
                {
                    $result += $HOUR*$number;
                }else{
                    $result -= $HOUR*$number;
                }
            }elseif(strpos($str, 'day') === 0){
                if($symb)
                {
                    $result += $DAY*$number;
                }else{
                    $result -= $DAY*$number;
                }
            }elseif(strpos($str, 'week') === 0){
                if($symb)
                {
                    $result += $WEEK*$number;
                }else{
                    $result -= $WEEK*$number;
                }
            }elseif(strpos($str, 'month') === 0){
                if($symb)
                {
                    $result += $MONTH*$number;
                }else{
                    $result -= $MONTH*$number;
                }
            }elseif(strpos($str, 'year') === 0){
                if($symb)
                {
                    $result += $YEAR*$number;
                }else{
                    $result -= $YEAR*$number;
                }
            }
        }
        return $result;
    }

}