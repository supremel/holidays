<?php

namespace Supremel\Holidays;

use Supremel\Holidays\Config\ConfigHelper;

class HolidaysTool
{
    const IS_WEEKDAYS = 1;
    const IS_WEEKEND_WORK = 2;
    const IS_WEEKEND = 3;
    const IS_WEEKDAYS_REST = 4;

    // 工作日
    const IS_WORK = [
        self::IS_WEEKDAYS,
        self::IS_WEEKEND_WORK,
    ];

    // 休息日
    const IS_REST = [
        self::IS_WEEKEND,
        self::IS_WEEKDAYS_REST,
    ];

    /**
     * 获取一天的节假日情况
     * @param $date
     * @return 1：工作日 2：补班(周末补班) 3：周未 4：节假日(工作日休息)
     */
    public static function getDateSalt ($date = '')
    {
        // 获取时间戳
        $time = !empty($date) ? strtotime($date) : time();

        // 获取是周几
        $week = date('N', $time);
        $salt = in_array($week, [6, 7], true) ? self::IS_WEEKEND : self::IS_WEEKDAYS;

        // 获取配置信息
        $configSalt = ConfigHelper::dateHolidaySalt($time);

        // 处理节假日调整
        if (ConfigHelper::IS_WEEKDAYS === $configSalt) {
            $salt = (self::IS_WEEKEND === $salt) ? self::IS_WEEKEND_WORK : $salt;
        } else if (ConfigHelper::IS_HOLIDAYS === $configSalt) {
            $salt = (self::IS_WEEKDAYS === $salt) ? self::IS_WEEKDAYS_REST : $salt;
        }

        return $salt;
    }

    /**
     * 获取一天是否工作日
     * @param $date
     * @return bool
     */
    public static function isWeekdays ($date = '')
    {
        $salt = self::getDateSalt($date);
        return in_array($salt, self::IS_WORK, true);
    }

    /**
     * 获取一天是否休息日
     * @param $date
     * @return bool
     */
    public static function isHolidays ($date = '')
    {
        $salt = self::getDateSalt($date);
        return in_array($salt, self::IS_REST, true);
    }

}
