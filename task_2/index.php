<?php

/**
 * Поиск одного дубликата в массиве (согласно условию задачи).
 * Возвращает число.
 * @param array $arr
 * @return int
 */
function findSingleDuplicate(Array $arr) : int
{
    $duplicatesArr = array_flip(array_count_values($arr));
    return $duplicatesArr[2] ?? -1;
}

/**
 * Поиск дубликатов в массиве.
 * Возвращает массив всех дубликатов с их ключами.
 * @param $arr
 * @return array
 */
function findAllDuplicates(Array $arr) : array
{
    return array_diff_assoc($arr, array_unique($arr));
}

// Создаём тестовый массив и добавляем в него дубликат числа;
$testArr = range(0,1000000);
$testArr[] = 375;

$start = microtime(true);
print_r(findSingleDuplicate($testArr));
//print_r(findAllDuplicates($testArr));
echo "<br>Time: " . (microtime(true) - $start);


