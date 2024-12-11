<?php

if (!function_exists('hasModuleAccess')) {
    function hasModuleAccess($moduleName)
    {
        $userModules = explode(",", json_decode(auth()->user()->role->module, true));
        return in_array($moduleName, $userModules);
    }
}

if(!function_exists('b2en')){
    function b2en($banglaNumber){
        if(auth()->user()->lang == 'en') return $banglaNumber;
        // Replace Bangla digits with English digits
        $conversionMap = [
            '০' => '0',
            '১' => '1',
            '২' => '2',
            '৩' => '3',
            '৪' => '4',
            '৫' => '5',
            '৬' => '6',
            '৭' => '7',
            '৮' => '8',
            '৯' => '9',
            '.' => '.'
        ];

        // Replace Bangla digits with English digits
        return strtr($banglaNumber, $conversionMap);
    }
}

if(!function_exists('e2bn')){
    function e2bn($englishNumber){
        if(auth()->user()->lang == 'en') return $englishNumber;
        // Replace Bangla digits with English digits
        $conversionMap = [
            '0' => '০',
            '1' => '১',
            '2' => '২',
            '3' => '৩',
            '4' => '৪',
            '5' => '৫',
            '6' => '৬',
            '7' => '৭',
            '8' => '৮',
            '9' => '৯',
            '.' => '.'
        ];

        // Replace Bangla digits with English digits
        return strtr($englishNumber, $conversionMap);
    }
}