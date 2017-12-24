<?php
/**
 * @param $x
 * @param $n
 * @return integer
 */
function foo($x, $n) {
    if ($n == 0) {
        return 1;
    }
    $a = foo($x, $n - 1);
    $result = $x * $a;
    echo $x . " x  " . $a . " = " . $result . " (" . $n . ") <br />";

    return $result;
}

foo(5, 7);
