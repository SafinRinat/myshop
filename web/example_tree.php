<?php

$userPassword = "my_password";
$res = md5($userPassword);
echo $res;

die();


$data = [
    'A' => [
        "B" => [ "x", "D", "Y" ],
        "C" => [ "Z" ],
        "G" => [
            "H" => [ "J", "K", "L" ],
            "I" => [ "M" ]
        ]
    ]
];

function rep($level) {
    return "<br>" . str_repeat("-", $level * 5);
}

function tree($data, $level = 0) {
    if (!is_array($data)) {
        return "";
    }
    $res = "";
    foreach ($data as $key => $value) {
        // выводим рутовый элемент (ключ рутового элемента)
        $res .= "<br>" . str_repeat("-", $level * 10) . $key;
        // если есть дети то заходим в функцию еще раз
        if (is_array($value)) {
            $res .= tree($value, $level + 1);
        }
        // если детей нет, то выводим значение рутового элемента
        if (is_string($value)) {
            $res .= "<br>" . str_repeat("-", ($level + 1)) . $value;
        }
    }
    return $res;
}
//echo tree($data, 0);
function foo($item) {
//    print_r($item);
    var_dump($item);
}
array_map("foo", $data);