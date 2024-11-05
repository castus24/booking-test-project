<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Типы билетов
 *
 * @uses self::ADULT Взрослый
 * @uses self::KID Детский
 * @uses self::DISCOUNT Льготный
 * @uses self::GROUP Групповой
 */
final class TicketTypeEnum extends Enum
{
    const ADULT = 'adult';
    const KID = 'kid';
    const DISCOUNT  = 'discount';
    const GROUP = 'group';
}
