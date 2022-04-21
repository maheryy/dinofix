<?php

namespace App\Service;

class Helper
{

    public function __construct()
    {
    }

    public function buildArrayFromKeyCombination(array $array): array
    {
        $res = [];
        $keys = array_keys($array);
        $size = count($array[$keys[0]]);

        for ($i = 0; $i < $size; $i++) {
            $item = [];
            foreach ($keys as $key) {
                $item[] = $array[$key][$i];
            }
            $res[] = array_combine($keys, $item);
        }

        return $res;
    }
}