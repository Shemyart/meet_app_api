<?php

namespace App\HelperService;

class Helpers
{
    public function response(bool $isSuccess, string $report, $details, $errors, $responseCode)
    {
        return response()->json([
            'success' => $isSuccess,
            'responseCode' => $responseCode,
            'errors' => $errors,
            'report' => $report,
            'details' => $details
        ], $responseCode, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
    }

    /**
     * Приведение номера телефона к виду 71231231212
     *
     * @param $phone
     * @return array|string|string[]
     */
    public static function normalizePhone($phone){
        $phone = trim(preg_replace('~[^\d]+~is', '', $phone));
        if (str_starts_with($phone, '8')){
            $phone = substr_replace($phone, '7', 0, 1);
        }
        return $phone;
    }



}
