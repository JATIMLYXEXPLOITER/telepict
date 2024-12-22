
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

use DateTime;
use DateTimeZone;

class Location {
    public static function getLocalTime($timezone) {
        if ($timezone) {
            $localTime = new DateTime("now", new DateTimeZone($timezone));
            return $localTime->format('Y-m-d H:i:s');
        }
        return null;
    }

    public static function getGeoLocation($latitude, $longitude) {
        if ($latitude && $longitude) {
            return [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'location' => "Lat: $latitude, Long: $longitude"
            ];
        }
        return null;
    }
}
