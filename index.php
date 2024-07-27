<?php
declare(strict_types=1);

// Функция для генерации расписания
function generateSchedule(int $year, int $month, int $monthsToCalculate = 1): void
{
    // Установим настройки временной зоны
    date_default_timezone_set('Europe/Moscow');

    // Массив с названиями месяцев
    $monthsNames = [
        1 => 'Январь',
        2 => 'Февраль',
        3 => 'Март',
        4 => 'Апрель',
        5 => 'Май',
        6 => 'Июнь',
        7 => 'Июль',
        8 => 'Август',
        9 => 'Сентябрь',
        10 => 'Октябрь',
        11 => 'Ноябрь',
        12 => 'Декабрь'
    ];

    // Начальный рабочий день
    $nextWorkDay = 1;

    // Перебор месяцев, начиная с заданного месяца
    for ($i = 0; $i < $monthsToCalculate; $i++) {
        // Рассчитываем текущий месяц и год
        $currentMonth = ($month + $i - 1) % 12 + 1;
        $currentYear = $year + intdiv($month + $i - 1, 12);

        // Получаем количество дней в текущем месяце
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);

        // Выводим название месяца
        echo $monthsNames[$currentMonth] . " $currentYear\n";

        // Переменная для отслеживания количества дней на текущей строке
        $daysInWeek = 0;

        // Перебор всех дней месяца
        for ($day = 1; $day <= $daysInMonth; $day++) {
            // Получаем день недели (0 для воскресенья, 1 для понедельника и т.д.)
            $weekDay = (int) date('w', mktime(0, 0, 0, $currentMonth, $day, $currentYear));

            // Проверяем, является ли день рабочим
            if ($day == $nextWorkDay) {
                // Если рабочий день выпадает на субботу или воскресенье,
                // переносим его на ближайший понедельник
                if ($weekDay == 6) {
                    $nextWorkDay += 2;
                } elseif ($weekDay == 0) {
                    $nextWorkDay += 1;
                } else {
                    // Выводим рабочий день зелёным цветом 
                    echo "\033[32m$day+\033[0m ";
                    // Устанавливаем следующий рабочий день через два дня
                    $nextWorkDay += 3;
                }
            } else {
                // Ещё проверяем, является ли день субботой
                if ($weekDay == 6) {
                    // Выводим субботу красным цветом
                    echo "\033[31m$day\033[0m ";
                } else {
                    // Выводим обычный день
                    echo "$day ";
                }
            }
            // Если день воскресенье
            if ($weekDay == 0) {
                // Выводим воскресенье красным цветом
                echo "\033[31m$day\033[0m ";
            }

            // Увеличиваем количество дней на текущей строке
            $daysInWeek++;

            // Переходим на новую строку после каждого седьмого дня недели
            if ($daysInWeek == 7) {
                echo "\n";
                $daysInWeek = 0;
            }
        }
        // Переход на новую строку после завершения месяца, если последняя строка не завершена
        if ($daysInWeek != 0) {
            echo "\n";
        }

        // Корректируем следующий рабочий день для следующего месяца
        if ($nextWorkDay > $daysInMonth) {
            $nextWorkDay -= $daysInMonth;
        }
    }
}

// Вызов функции с параметрами (год, месяц, количество месяцев для расчета)
generateSchedule(2024, 7, 4);