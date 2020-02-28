<?php

namespace IsTianXin\Apollo;

class Helper
{
    public static function arrayWrap($value): array
    {
        if ($value === null) {
            return [];
        }

        return is_array($value) ? $value : [$value];
    }
}
