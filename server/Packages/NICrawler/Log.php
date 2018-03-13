<?php
/*
 * @author xuruiqi
 * @date   20150406
 * @copyright reetsee.com
 */     
class NICrawler_Log {
    const LOG_LEVEL_DEBUG   = 1;
    const LOG_LEVEL_NOTICE  = 2;
    const LOG_LEVEL_WARNING = 3;
    const LOG_LEVEL_FATAL   = 4;

    public function __construct() {

    }

    public static function debug($msg, $intTraceLevel = 0) {
        $msg = strval($msg);
        self::_print(self::LOG_LEVEL_DEBUG, $msg, $intTraceLevel);
    }

    public static function notice($msg, $intTraceLevel = 0) {
        $msg = strval($msg);
        self::_print(self::LOG_LEVEL_NOTICE, $msg, $intTraceLevel);
    }

    public static function warning($msg, $intTraceLevel = 0) {
        $msg = strval($msg);
        self::_print(self::LOG_LEVEL_WARNING, $msg, $intTraceLevel);
    }

    public static function fatal($msg, $intTraceLevel = 0) {
        $msg = strval($msg);
        self::_print(self::LOG_LEVEL_FATAL, $msg, $intTraceLevel);
    }

    protected static function _print($log_level, $msg, $intTraceLevel = 0) {
        switch($log_level) {
            case self::LOG_LEVEL_FATAL:
                $prepend = 'Fatal: ';
                $append = "\n";
                break;
            case self::LOG_LEVEL_WARNING:
                $prepend = 'Warning: ';
                $append = "\n";
                break;
            case self::LOG_LEVEL_NOTICE:
                $prepend = 'Notice: ';
                $append = "\n";
                break;
            case self::LOG_LEVEL_DEBUG:
                $prepend = 'Debug: ';
                $append = "\n";
                break;
        }

        //参考了其它地方的代码
        $trace = debug_backtrace();
        $depth = 2 + $intTraceLevel;
        $intTraceDepth = count($trace);
        if ($depth > $intTraceDepth) {
            $depth = $intTraceDepth;
        }
        $targetTrace = $trace[$depth];
        unset($trace);
        if (isset($targetTrace['file'])) {
            $targetTrace['file'] = basename($targetTrace['file']);
        }

        $prepend = strval(@date("Y-m-d H:i:s")) . " {$targetTrace['file']} {$targetTrace['class']} {$targetTrace['function']} {$targetTrace['line']} " . $prepend . ' ';

        $msg = $prepend . $msg . $append;

        echo $msg;
    }
}
?>
