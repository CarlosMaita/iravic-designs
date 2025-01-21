<?php

namespace App\Constants;

final class FrequencyCollectionConstants
{
    const CADA_SEMANA =            "Cada semana";
    const CADA_DOS_SEMANAS =        "Cada dos semana";
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
}