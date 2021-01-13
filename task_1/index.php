<?php
/**
 * Путь к папке со скриптом
 */
$root = '/auslogics/task_1';

/**
 * Домен для сохранения cookies
 */
$domain = 'pm.test';

/**
 * Путь к фактическому файлу
 */
$file = __DIR__ . '/file.exe';

/**
 * Разбираем запрос, сохраняем Referrer
 */
$request = str_replace($root, '', $_SERVER['REQUEST_URI']);
$referrer = $_SERVER['HTTP_REFERER'] ?? null;
$referrer = parse_url($referrer, PHP_URL_HOST);

/**
 * Сохраняем Cookie
 */
if (!empty($referrer)) {
    setcookie('referrer', $referrer, time()+(3600*24*7), '/', $domain);
}

/**
 * Если URI запроса соответствует маске "/files/*.exe",
 * Отдаём на скачивание фактический файл.
 */
if (preg_match('/\/files\/([0-9a-zA-Z\-]+).exe/', $request)) {

    header("Content-Description: File Transfer");
    header("Content-Type: application/x-msdownload");
    header("Content-Disposition: attachment; filename=" . basename($file));
    header("Content-Transfer-Encoding: binary");

    readfile($file);
    exit();
}