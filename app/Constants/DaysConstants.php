<?php

namespace App\Constants;

use Carbon\Carbon;

final class DaysConstants
{
    const LUNES = 1;
    const MARTES = 2;
    const MIERCOLES = 3;    
    const JUEVES = 4;
    const VIERNES = 5;
    const SABADO = 6;
    const DOMINGO = 7;

    private static  $collectionDaysMap = [
        'Lunes'     => Carbon::MONDAY,
        'Martes'    => Carbon::TUESDAY,
        'Miercoles' => Carbon::WEDNESDAY,
        'Jueves'    => Carbon::THURSDAY,
        'Viernes'   => Carbon::FRIDAY,
        'Sabado'    => Carbon::SATURDAY,
        'Domingo'   => Carbon::SUNDAY,
    ]; 

    /**
     * Convert a day of the week from Spanish to its corresponding Carbon constant.
     *
     * @param string $collection_day The day of the week in Spanish (e.g., 'Lunes', 'Martes').
     * @return int The corresponding Carbon constant for the day of the week.
     */
    public static function collectionDayToNumber($collection_day): ?int
    {
        return self::$collectionDaysMap[$collection_day] ?? null;
    }
}

