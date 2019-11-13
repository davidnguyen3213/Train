<?php

namespace App\Helpers;


class TransFormatApi
{

    const IS_RESPONDED = 1;
    const IS_NOT_RESPONDED = 0;

    /**
     * Get FCM token
     *
     * @param $collectionFcmTokens
     * @return array
     */
    private static function _getFcmTokens($collectionFcmTokens)
    {
        if ($collectionFcmTokens->isEmpty()) {
            return ['android' => [], 'ios' => []];
        }

        $platforms = \Config::get('constants.TYPE_PLATFORM');
        $deviceTokensAndroid = [];
        $deviceTokensIOS = [];
        foreach ($collectionFcmTokens as $keyFcm => $fcmToken) {
            $tmpPlatform = strtolower($fcmToken->platform);
            if ($tmpPlatform == $platforms[0]) {
                //Android
                $deviceTokensAndroid[] = $fcmToken->device_token;
            } elseif ($tmpPlatform == $platforms[1]) {
                //IOS
                $deviceTokensIOS[] = $fcmToken->device_token;
            }
        }

        return ['android' => $deviceTokensAndroid, 'ios' => $deviceTokensIOS];
    }

    /**
     * Get device token of company
     *
     * @param $collectionCompanies
     * @return array
     */
    public static function formatDataDeviceToken($collectionCompanies)
    {
        return self::_getFcmTokens($collectionCompanies);
    }

}