<?php
########################################
#
# SCRIPT BY TPNET CYBER
# BY LYXSEC
# COPYRIGHT TPNETCY
# SINCE 2023
# FOUND BUG JUST COMMENT IN MY CHANNEL
#
########################################
namespace Helpers;

class Device {
    public static function getDeviceInfo($userAgent) {
        $deviceName = "Unknown";
        $androidVersion = "Unknown";

        if (preg_match('/Android ([0-9.]+); ([^;]+)/', $userAgent, $matches)) {
            $androidVersion = $matches[1];
            $deviceName = $matches[2];
        }

        return [
            'device' => $deviceName,
            'android_version' => $androidVersion
        ];
    }

    public static function getBrowserInfo($userAgent) {
        if (strpos($userAgent, 'Chrome') !== false) {
            return 'Google Chrome';
        } elseif (strpos($userAgent, 'Firefox') !== false) {
            return 'Mozilla Firefox';
        } elseif (strpos($userAgent, 'Safari') !== false) {
            return 'Safari';
        } elseif (strpos($userAgent, 'Opera') !== false || strpos($userAgent, 'OPR') !== false) {
            return 'Opera';
        } elseif (strpos($userAgent, 'MSIE') !== false || strpos($userAgent, 'Trident') !== false) {
            return 'Internet Explorer';
        }
        return 'unknown browser';
    }
}
