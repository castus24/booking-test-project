<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Язык
 *
 * @uses self::RU Русский
 * @uses self::EN Английский
 */
final class LangEnum extends Enum
{
    const RU = 'ru';
    const EN = 'en';
}
