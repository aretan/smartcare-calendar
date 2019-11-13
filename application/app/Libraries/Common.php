<?php namespace App\Libraries;

class Common
{
    public static function groupByKey($key, $array)
    {
        $result = [];
        foreach ($array as $line) {
            $result[$line[$key]][] = $line;
        }
        return $result;
    }
}
