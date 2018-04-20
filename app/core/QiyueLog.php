<?php

/**
 * 日志输出管理
 */
namespace app\core;


use atphp\Config;
use atphp\Request;

class QiyueLog
{

    const ERR = 'ERR';

    const WARN = 'WARN';

    const NOTICE = 'NOTIC';

    const INFO = 'INFO';

    const DEBUG = 'DEBUG';

    public static function error($message, $module = '')
    {
        return self::_write($message, $module, self::ERR, '');
    }

    public static function warn($message, $module = '')
    {
        return self::_write($message, $module, self::WARN, '');
    }

    public static function notice($message, $module = '')
    {
        return self::_write($message, $module, self::NOTICE, '');
    }

    public static function info($message, $module = '')
    {
        return self::_write($message, $module, self::INFO, '');
    }

    public static function debug($message, $module = '')
    {
        return self::_write($message, $module, self::DEBUG, '');
    }

    private static function _write($message, $module = '', $level = self::NOTICE, $file = '')
    {
        if (is_array($message) || is_object($message)) {
            $message = json_encode($message, JSON_UNESCAPED_UNICODE);
        }
        $logPath = self::_getLogPath($module);
        if (false === $logPath) {
            return false;
        }
        if ('' == $file) {
            $file = date('H') . '.log';
        }
        $destination = $logPath . date('Ymd') . '/' . $file;
        $path = dirname($destination);
        //为避免文件读写出现问题而造成整个接口挂掉，因此在这里捕获异常
        try {
            !is_dir($path) && mkdir($path, 0755, true);
            $ip = Request::getIP();
            $result = error_log('[' . date("Y-m-d H:i:s") . '][' . $ip . '][' . $level . ']' . $message . "\n", 3, $destination);
        } catch (\Exception $e) {
            $result = false;
        }
        return $result;
    }

    private static function _getLogPath($module)
    {
        $config = Config::get('qiyue_log');
        $logPath = '';
        if ('' != $module && isset($config[$module])) {
            $logPath = $config[$module];
        }
        if ('' == $logPath) {
            if (empty($config['default'])) {
                return false;
            }
            $logPath = $config['default'];
        }
        return $logPath;
    }
}