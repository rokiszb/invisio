<?php

namespace App\Response;

class ProductChoiceResponse
{

    public static function formChoicesToArray($formChoices): array
    {
        $arr = [];
        foreach ($formChoices as $choice) {
            $arr[] = $choice->value;
        }
        return $arr;
    }
}