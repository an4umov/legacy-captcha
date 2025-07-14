<?php
session_start();

$response = array('success' => false, 'message' => '');

if (isset($_POST['captcha'])) {
    $captchaInput = $_POST['captcha'];

    if (isset($_SESSION['captcha_code']) && $captchaInput === $_SESSION['captcha_code']) {
        $response['success'] = true;
    } else {
        $response['message'] = 'Неверная капча.';
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>