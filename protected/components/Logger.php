<?php
date_default_timezone_set('America/Bahia');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Logger
{
    public function my_error_handler($errno, $errstr, $errfile, $errline)
    {
        $errno = $errno & error_reporting();
        if ($errno == 0) return;
        if (!defined('E_STRICT')) define('E_STRICT', 2048);
        if (!defined('E_RECOVERABLE_ERROR')) define('E_RECOVERABLE_ERROR', 4096);
        $path = Yii::app()->runtimePath . '/smith-log';
        Logger::createFolder($path);
        $log = fopen($path . "/smithError" . date("Ymd") . ".log", "a");

        $msg = "[ERROR]\n";
        $msg .= date("d/m/Y H:i:s") . "\n";
        $msg .= "<b>";
        switch ($errno) {
            case E_ERROR:
                $msg .= "Error";
                break;
            case E_WARNING:
                $msg .= "Warning";
                break;
            case E_PARSE:
                $msg .= "Parse Error";
                break;
            case E_NOTICE:
                $msg .= "Notice";
                break;
            case E_CORE_ERROR:
                $msg .= "Core Error";
                break;
            case E_CORE_WARNING:
                $msg .= "Core Warning";
                break;
            case E_COMPILE_ERROR:
                $msg .= "Compile Error";
                break;
            case E_COMPILE_WARNING:
                $msg .= "Compile Warning";
                break;
            case E_USER_ERROR:
                $msg .= "User Error";
                break;
            case E_USER_WARNING:
                $msg .= "User Warning";
                break;
            case E_USER_NOTICE:
                $msg .= "User Notice";
                break;
            case E_STRICT:
                $msg .= "Strict Notice";
                break;
            case E_RECOVERABLE_ERROR:
                $msg .= "Recoverable Error";
                break;
            default:
                $msg .= "Unknown error ($errno)";
                break;
        }
        $msg .= ":</b> <i>$errstr</i> in <b>$errfile</b> on line <b>$errline</b>\n";
        if (function_exists('debug_backtrace')) {
            $backtrace = debug_backtrace();
            array_shift($backtrace);
            foreach ($backtrace as $i => $l) {
                $msg .= "[$i] in function <b>{$l['class']}{$l['type']}{$l['function']}</b>";
                if ($l['file']) $msg .= " in <b>{$l['file']}</b>";
                if ($l['line']) $msg .= " on line <b>{$l['line']}</b>";
                $msg .= "\n";
            }
        }
        $msg .= "=====================================================\n\n";
        fwrite($log, $msg);
        fclose($log);

        SendMail::send(
            'smith@vivainovacao.com',
            array('brunooliveira@vivainovacao.com', 'lucascardoso@vivainovacao.com'),
            'Erro Smith',
            '<pre>' . $msg . '</pre>'
        );

        if (isset($GLOBALS['error_fatal'])) {
            if ($GLOBALS['error_fatal'] & $errno) die('fatal');
        }
    }

    public function error_fatal($mask = NULL)
    {
        if (!is_null($mask)) {
            $GLOBALS['error_fatal'] = $mask;
        } elseif (!isset($GLOBALS['die_on'])) {
            $GLOBALS['error_fatal'] = 0;
        }
        return $GLOBALS['error_fatal'];
    }

    static function saveException($exception, $empresa, $relatorio = '')
    {
        $path = Yii::app()->runtimePath . '/smith-log';
        Logger::createFolder($path);
        $log = fopen($path . "/smithException" . date("Ymd") . ".log", "a");

        $txt = "[EXCEPTION]\n";
        (!is_null($empresa)) ? $txt .= 'Empresa: ' . Empresa::model()->findByPk($empresa)->nome . "\n" : '';
        (!is_null($relatorio)) ? $txt .= 'Relatório: ' . $relatorio . "\n" : '';
        $txt .= date("d/m/Y H:i:s") . "\n";
        $txt .= " " . $exception->getCode();
        $txt .= " " . $exception->getMessage() . "\n";
        $txt .= "Ocorrido no arquivo " . $exception->getFile() . " na linha " . $exception->getLine() . "\n";
        $txt .= "Stack trace:\n";
        $txt .= $exception->getTraceAsString() . "\n";
        $txt .= "=====================================================\n\n";
        fwrite($log, $txt);
        fclose($log);
        return $txt;
    }

    static function sendException($exception, $empresa = null, $relatorio = null)
    {
        $txt = Logger::saveException($exception, $empresa, $relatorio);
        SendMail::send(
            'smith@vivainovacao.com',
            array('brunooliveira@vivainovacao.com', 'lucascardoso@vivainovacao.com'),
            'Erro Smith',
            '<pre>' . $txt . '</pre>'
        );
    }

    static function createFolder($path)
    {
        if (!file_exists($path))
            mkdir($path, 0770, true);
    }


    static function saveError($error)
    {
        $path = Yii::app()->runtimePath . '/smith-log';
        Logger::createFolder($path);
        $data = date("Ymd");
        $log = fopen($path . "/smith" . $data . ".log", "a");

        $quando = date("Y/m/d H:i:s");
        $txt = "[ERROR] \n";
        $txt .= $quando;
        $txt .= " " . $error['code'];
        $txt .= " " . $error['type'];
        $txt .= " " . $error['message'] . "\n";
        $txt .= "Ocorrido no arquivo " . $error['file'] . " na linha " . $error['line'] . "\n";
        $txt .= "Stack trace:\n";
        $txt .= $error['trace'] . "\n";;
        $txt .= "====================\n\n";
        fwrite($log, $txt);
        fclose($log);
        return $txt;
    }
}

