<?php
$exam_type = $_GET['exam_type'] ?? 'waec';
$year = $_GET['year'] ?? 2023;
header("Location: exam.php?subject=geography&exam_type={$exam_type}&year={$year}");
exit;
