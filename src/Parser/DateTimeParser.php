<?php

namespace Defr\Parser;

use DateTimeInterface;

final class DateTimeParser
{
    /**
     * @var string[]
     */
    private static $czechToEnglishMonths = [
        'ledna' => 'January',
        'února' => 'February',
        'března' => 'March',
        'dubna' => 'April',
        'května' => 'May',
        'června' => 'June',
        'července' => 'July',
        'srpna' => 'August',
        'září' => 'September',
        'října' => 'October',
        'listopadu' => 'November',
        'prosince' => 'December',
    ];

    /**
     * @param string $czechDate
     *
     * @return DateTimeInterface
     */
    public static function parseFromCzechDateString($czechDate)
    {
        $czechDate = trim($czechDate);
        $englishDate = strtr($czechDate, self::$czechToEnglishMonths);

        $date = new \DateTime('@' . strtotime($englishDate));
        $date->setTimezone(new \DateTimeZone("Europe/Prague"));

        return $date;    }
}
