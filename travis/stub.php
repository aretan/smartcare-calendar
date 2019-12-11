<?php

require __DIR__ . '/../application/vendor/autoload.php';

$matrix = json_decode(file_get_contents('php://input'), true);
$allocation = $matrix ? (new \Hungarian\Hungarian($matrix))->solve() : [];
foreach ($allocation as $i => $v) {
    $result[] = [$i, $v];
}
echo json_encode($result);
