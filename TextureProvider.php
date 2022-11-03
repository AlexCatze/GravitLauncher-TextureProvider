<?php
#
# Скрипт выдачи скинов и плащей. GravitLauncher 5.2.0+
#
# https://github.com/microwin7/GravitLauncher-TextureProvider
#
start();
class Constants
{
    const SKIN_PATH = "./minecraft-auth/skins/"; // Сюда вписать путь до skins/
    const CAPE_PATH = "./minecraft-auth/capes/"; // Сюда вписать путь до capes/
    const SKIN_URL = "https://example.com/minecraft-auth/skins/%login%.png";
    const CAPE_URL = "https://example.com/minecraft-auth/capes/%login%.png";
    const REGEX_USERNAME = "\w{1,16}$";
    const REGEX_UUIDv1 = "\b[0-9a-f]{8}\b-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-\b[0-9a-f]{12}\b";
    const REGEX_UUIDv4 = "[a-f0-9]{8}\-[a-f0-9]{4}\-4[a-f0-9]{3}\-(8|9|a|b)[a-f0-9]{4}\-[a-f0-9]{12}";
    const GIVE_DEFAULT = false; // Выдавать ли этим скриптом default скины и плащи, если упомянутые не найдены в папках. SKIN_URL и CAPE_URL должны содержать внешний путь к этому скрипту и ?login=%login% в конце
    const SKIN_DEFAULT = "iVBORw0KGgoAAAANSUhEUgAAAEAAAAAgCAMAAACVQ462AAAAWlBMVEVHcEwsHg51Ri9qQC+HVTgjIyNOLyK7inGrfWaWb1udZkj///9SPYmAUjaWX0FWScwoKCgAzMwAXl4AqKgAaGgwKHImIVtGOqU6MYkAf38AmpoAr68/Pz9ra2t3xPtNAAAAAXRSTlMAQObYZgAAAZJJREFUeNrUzLUBwDAUA9EPMsmw/7jhNljl9Xdy0J3t5CndmcOBT4Mw8/8P4pfB6sNg9yA892wQvwzSIr8f5JRzSeS7AaiptpxazUq8GPQB5uSe2DH644GTsDFsNrqB9CcDgOCAmffegWWwAExnBrljqowsFBuGYShY5oakgOXs/39zF6voDG9r+wLvTCVUcL+uV4m6uXG/L3Ut691697tgnZgJavinQHOB7DD8awmaLWEmaNuu7YGf6XcIITRm19P1ahbARCRGEc8x/UZ4CroXAQTVIGL0YySrREBADFGicS8XtG8CTS+IGU2F6EgSE34VNKoNz8348mzoXGDxpxkQBpg2bWobjgZSm+uiKDYH2BAO8C4YBmbgAjpq5jUl4yGJC46HQ7HJBfkeTAImIEmgmtpINi44JsHx+CKA/BTuArISXeBTR4AI5gK4C2JqRfPs0HNBkQnG8S4Yxw8IGoIZfXEBOW1D4YJDAdNSXgRevP+ylK6fGBCwsWywmA19EtBkJr8K2t4N5pnAVwH0jptsBp+2gUFj4tL5ywAAAABJRU5ErkJggg==";
    const CAPE_DEFAULT = "iVBORw0KGgoAAAANSUhEUgAAAEAAAAAgAQMAAACYU+zHAAAAA1BMVEVHcEyC+tLSAAAAAXRSTlMAQObYZgAAAAxJREFUeAFjGAV4AQABIAABL3HDQQAAAABJRU5ErkJggg==";

    public static function getSkinURL()
    {
        return self::SKIN_URL;
    }
    public static function getCapeURL()
    {
        return self::CAPE_URL;
    }
    public static function getSkinPath()
    {
        return self::SKIN_PATH;
    }
    public static function getCapePath()
    {
        return self::CAPE_PATH;
    }
    public static function getSkinDefault()
    {
        return self::SKIN_DEFAULT;
    }
    public static function getCapeDefault()
    {
        return self::CAPE_DEFAULT;
    }
    public static function getURL($function, $login)
    {
        $url = self::{'get' . ucwords($function) . 'URL'}();
        return str_replace('%login%', $login, $url) . (contains($url, $_SERVER['PHP_SELF'] . '?login=%login%')
            ? '&type=' . $function
            : '');
    }
    public static function getData($function, $login)
    {
        $path = Check::ci_find_file(self::{'get' . ucwords($function) . 'Path'}() . $login . '.png');
        return $path
            ? file_get_contents($path)
            : (self::GIVE_DEFAULT && contains(self::{'get' . ucwords($function) . 'URL'}(), $_SERVER['PHP_SELF'])
                ? base64_decode(self::{'get' . ucwords($function) . 'Default'}())
                : null);
    }
}
class Check
{
    public static function skin($login)
    {
        return self::getData(__FUNCTION__, $login);
    }
    public static function cape($login)
    {
        return self::getData(__FUNCTION__, $login);
    }
    private static function slim($data)
    {
        $image = imagecreatefromstring($data);
        $fraction = imagesx($image) / 8;
        $x = $fraction * 6.75;
        $y = $fraction * 2.5;
        $rgba = imagecolorsforindex($image, imagecolorat($image, $x, $y));
        if ($rgba["alpha"] === 127)
            return true;
        else return false;
    }
    private static function getData($function, $login)
    {
        $msg = [];
        $data = Constants::getData($function, $login);
        if (isset($data)) {
            $msg = [
                'url' => Constants::getURL($function, $login),
                'digest' => self::digest($data)
            ];
        }
        if ($function == 'skin')
            if (self::slim($data))
                $msg['metadata'] = ['model' => 'slim'];
        return $msg;
    }
    private static function digest($string)
    {
        return strtr(base64_encode(md5($string, true)), '+/', '-_');
    }
    public static function ci_find_file($filename)
    {
        if (file_exists($filename))
            return $filename;
        $directoryName = dirname($filename);
        $fileArray = glob($directoryName . '/*', GLOB_NOSORT);
        $fileNameLowerCase = strtolower($filename);
        foreach ($fileArray as $file) {
            if (strtolower($file) == $fileNameLowerCase) {
                return $file;
            }
        }
        return false;
    }
}
function start()
{
    $login = isset($_GET['login']) ? $_GET['login'] : null;
    $type = isset($_GET['type']) ? $_GET['type'] : null;
    regex_valid($login) ?: response();
    if (!empty($type)) getTexture($login, $type);
    $msg = [];
    $skin = Check::skin($login);
    if (!empty($skin)) $msg['SKIN'] = $skin;
    $cape = Check::cape($login);
    if (!empty($cape)) $msg['CAPE'] = $cape;
    response($msg);
}
function getTexture($login, $type)
{
    header("Content-type: image/png");
    switch ($type) {
        case 'skin':
        case 'cape':
            die(Constants::getData($type, $login));
        default:
            die(Constants::getData('skin', $login));
    }
}
function response($msg = null)
{
    header("Content-Type: application/json; charset=UTF-8");
    die(json_encode((object) $msg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}
function regex_valid($var)
{
    if (!is_null($var) && (preg_match("/^" . Constants::REGEX_USERNAME . "/", $var, $varR) ||
        preg_match("/" . Constants::REGEX_UUIDv1 . "/", $var, $varR) ||
        preg_match("/" . Constants::REGEX_UUIDv4 . "/", $var, $varR)))
        return true;
}
function contains($haystack, $needle)
{
    return strpos($haystack, $needle) !== false;
}
