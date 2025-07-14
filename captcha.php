<?php
session_start();

// Создание изображения
$image = imagecreatetruecolor(120, 40);
$background_color = imagecolorallocate($image, 255, 255, 255);
$text_color = imagecolorallocate($image, 0, 0, 0);

// Генерация случайного кода капчи
$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$captcha_code = substr(str_shuffle($chars), 0, 6);

// Сохранение кода капчи в сессии, если она существует
if (isset($_SESSION)) {
    $_SESSION['captcha_code'] = $captcha_code;
}

// Рендеринг капчи на изображении
imagefilledrectangle($image, 0, 0, 120, 40, $background_color);
$font = DIR . '/roboto.ttf';
if (file_exists($font)) {
    imagettftext($image, 20, 0, 10, 30, $text_color, $font, $captcha_code);
} else {
    $error_message = 'Файл шрифта не найден: ' . $font;
    imagettftext($image, 20, 0, 10, 30, $text_color, null, $error_message);
}

// Вывод изображения
header('Content-Type: image/png');
imagepng($image);
imagedestroy($image);
?>