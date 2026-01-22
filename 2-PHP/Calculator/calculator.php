<?php
if (!isset($_POST['num1'], $_POST['num2'], $_POST['operation'])) {
    exit('Invalid request');
}

$num1 = (float) $_POST['num1'];
$num2 = (float) $_POST['num2'];
$operation = $_POST['operation'];
$result = 0;

switch ($operation) {
    case 'add':
        $result = $num1 + $num2;
        break;
    case 'subtract':
        $result = $num1 - $num2;
        break;
    case 'multiply':
        $result = $num1 * $num2;
        break;
    case 'divide':
        $result = $num2 != 0 ? $num1 / $num2 : 'Error: Division by zero';
        break;
    default:
        $result = 'Error: Invalid operation';
}

echo '<div class="result">Result: ' . $result . '</div>';
