<?php

$num = 200000;
$startTime = hrtime(true);
$result = getSS($num);
$endTime = hrtime(true);
var_dump(count($result));
// var_dump($startTime);
// var_dump($endTime);
var_dump(($endTime - $startTime) / 1000000000.0);


$startTime = hrtime(true);
$result = getSuShu($num);
$endTime = hrtime(true);
var_dump(count($result));
var_dump(($endTime - $startTime) / 1000000000.0);

/**
 * 求素数1
 */
function getSS($num)
{
    $susuArray = array(); // 存素数
    $mark = array(); // 标记下标所对应的数是否为素数
    for ($i=0; $i <= $num; $i++) { 
        $mark[$i] = false;
    }
    // $mark[2] = false;
    for ($i = 2; $i < $num; $i++) {
        if ($mark[$i] == false) {
            $susuArray[] = $i;
            for ($j = $i; $j <= $num; $j += $i) {
                $mark[$j] = true;
            }
        }
    }
    return $susuArray;
}

/**
 * 求素数2
 */
function getSuShu($num)
{
    $ssArray = array();
    $ssArray[] = 2;
    for ($i = 3; $i < $num; $i++) {
        for ($j = 2; $j <= $i / 2; $j++) {
            if ($i % $j == 0) break;
        }
        if ($j >= $i / 2) $ssArray[] = $i;
    }
    return $ssArray;
}
