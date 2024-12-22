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

class Network {
    public static function getUserIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($ipList[0]);
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        }
        return "unknown";
    }

    public static function getGeoLocation($ip) {
        $geoData = @file_get_contents("http://ip-api.com/json/{$ip}");
        return json_decode($geoData, true);
    }

    public static function getNetworkDetails($ip) {
        $networkData = @file_get_contents("https://ipinfo.io/{$ip}/json");
        return json_decode($networkData, true);
    }
}