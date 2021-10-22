<?php

namespace Supremel\Holidays\Config;

class ConfigHelper
{
    // 工作日-节假日调整公休
    const HOLIDAYS = [];

    // 周末-节假日调整补班
    const WEEKDAYS = [];

    const IS_UNKOWN = 0;
    const IS_WEEKDAYS = 1;
    const IS_HOLIDAYS = 2;

    /**
     * 获取一天的节假日情况
     * @param $time
     * @return 1：补班(工作日) 2：节假日
     */
    public static function dateHolidaySalt ($time)
    {
        $salt = self::IS_UNKOWN;

        $year = date('Y', $time);
        /** @var $config ConfigHelper */
        $config = 'Supremel\Holidays\Config\Config' . $year;
        if (class_exists($config)) {
            $date = date('Y-m-d', $time);
            if (in_array($date, $config::HOLIDAYS, true)) {
                $salt = self::IS_HOLIDAYS;
            } else if (in_array($date, $config::WEEKDAYS, true)) {
                $salt = self::IS_WEEKDAYS;
            }
        }

        return $salt;
    }

}
