<?php

abstract class Measure_Abstract
{
    protected $value = 0.0;

    protected static function convertToBaseUnits($value = 0.0, $uom = null)
    {
        $value = self::validateConversion($value, $uom);
        $factor = static::$conversions[$uom];

        return $value * $factor;
    }

    protected static function convertFromBaseUnits($value = 0.0, $uom = null)
    {
        $value = self::validateConversion($value, $uom);
        $factor = static::$conversions[$uom];

        return $value / $factor;
    }

    protected static function validateConversion($value, $uom)
    {
        if (!is_numeric($value)) {
            throw new Exception(static::class . ' must be a numeric value');
        }
        $value = (float) $value;

        if (is_null($uom)) {
            throw new Exception('Unit of Measure must be specified');
        }

        if (!isset(static::$conversions[$uom])) {
            throw new Exception($uom . ' is not a recognised Unit of Measure for ' . static::class);
        }
        return $value;

    }

    public static function getUOMs()
    {
        return array_keys(static::$conversions);
    }
}


class Distance extends Measure_Abstract
{
    const METRES          = 'm';      //    metre (SI base unit)
    const KILOMETRES      = 'km';     //    1000 metres (SI unit)
    const MILES           = 'mi';     //    mile (International)
    const NAUTICAL_MILES  = 'nmi';    //    nautical mile (International)
    const YARDS           = 'yds';    //    yard (International)
    const FEET            = 'ft';     //    foot (International)
    const INCHES          = 'in';     //    inch (International)
    const AU              = 'AU';     //    Astronomical Unit

    protected static $conversions = [
        self::METRES          => 1.0,
        self::KILOMETRES      => 1000.0,
        self::MILES           => 1609.344,
        self::NAUTICAL_MILES  => 1852.0,
        self::YARDS           => 0.9144,
        self::FEET            => 0.3048,
        self::INCHES          => 0.0254,
        self::AU              => 149597871464
    ];

    const DEFAULT_UNIT = self::METRES;

    public function __construct($value = null, $uom = self::DEFAULT_UNIT)
    {
        if (!is_null($value)) {
            $this->setValue($value, $uom);
        }
    }

    public function setValue($distance = 0.0, $uom = self::DEFAULT_UNIT)
    {
        if (!is_numeric($distance)) {
            throw new Exception(static::class . ' must be a numeric value');
        }
        if (is_null($uom)) {
            $uom = static::DEFAULT_UNIT;
        } elseif (!in_array($uom, self::getUOMs())) {
            throw new Exception($uom . ' is not a recognised Unit of Measure for ' . static::class);
        }

        $this->value = self::convertToBaseUnits($distance, $uom);
    }

    public function getValue($uom = self::DEFAULT_UNIT)
    {
        if (is_null($uom)) {
            $uom = static::DEFAULT_UNIT;
        } elseif (!in_array($uom, self::getUOMs())) {
            throw new Exception($uom . ' is not a recognised Unit of Measure for ' . static::class);
        }

        return self::convertFromBaseUnits($this->value, $uom);
    }
}
