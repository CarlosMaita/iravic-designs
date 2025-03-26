<?php

namespace App\Constants;

final class FrequencyCollectionConstants
{
    const CADA_SEMANA =             "Cada semana";
    const CADA_DOS_SEMANAS =        "Cada dos semanas";
    const CADA_MES_PRIMERA_SEMANA = "Cada mes - primera semana";
    const CADA_MES_SEGUNDA_SEMANA = "Cada mes - segunda semana";
    const CADA_MES_TERCERA_SEMANA = "Cada mes - tercera semana";
    const CADA_MES_CUARTA_SEMANA  = "Cada mes - cuarta semana";

    const FREQUENCY_COLLECTION_OPTIONS = [
        self::CADA_SEMANA,
        self::CADA_DOS_SEMANAS,
        self::CADA_MES_PRIMERA_SEMANA,
        self::CADA_MES_SEGUNDA_SEMANA,
        self::CADA_MES_TERCERA_SEMANA,
        self::CADA_MES_CUARTA_SEMANA
    ];

    private static $weekWithCollectionFrequencyMap = [
        self::CADA_MES_PRIMERA_SEMANA => 1,
        self::CADA_MES_SEGUNDA_SEMANA => 2,
        self::CADA_MES_TERCERA_SEMANA => 3,
        self::CADA_MES_CUARTA_SEMANA => 4,
    ];

     /**
     * Determines the week number corresponding to the given collection frequency.
     *
     * @param string $collection_frequency The frequency of collection, which should be one of the predefined constants
     *                                     representing specific weeks of the month.
     * @return int The week number (1 to 4) corresponding to the collection frequency.
     */
    public static function getWeekWithCollectionFrequency($collection_frequency): int
    {
        return self::$weekWithCollectionFrequencyMap[$collection_frequency] ?? 0;
    }
}