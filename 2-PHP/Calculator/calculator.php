<?php
    $num1 = $_POST['num1'];
    $num2 = $_POST['num2'];
    $operation = $_POST['operation'];
    $result = 0;

    if ($operation == 'add') {
        $result = $num1 + $num2;
    } elseif ($operation == 'subtract') {
        $result = $num1 - $num2;
    } elseif ($operation == 'multiply') {
        $result = $num1 * $num2;
    } elseif ($operation == 'divide') {
        if ($num2 != 0) {
            $result = $num1 / $num2;
        } else {
            $result = 'Error: Division by zero';
        }
    } else {
        $result = 'Error: Invalid operation';
    }

    echo 'Result: ' . $result;
?>