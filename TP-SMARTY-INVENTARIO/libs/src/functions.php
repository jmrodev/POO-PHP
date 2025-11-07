<?php

use Smarty\Exception;

function smarty_ucfirst_ascii($string): string
{
    return smarty_strtoupper_ascii(substr($string, 0, 1)) . substr($string, 1);
} function smarty_strtolower_ascii($string): string
{
    return strtr($string, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz');
} function smarty_strtoupper_ascii($string): string
{
    return strtr($string, 'abcdefghijklmnopqrstuvwxyz', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ');
} function smarty_make_timestamp($string)
{
    if (empty($string)) {
        return time();
    } elseif ($string instanceof DateTime || (interface_exists('DateTimeInterface', false) && $string instanceof DateTimeInterface)) {
        return (int)$string->format('U');
    } elseif (strlen($string) === 14 && ctype_digit((string)$string)) {
        return mktime(substr($string, 8, 2), substr($string, 10, 2), substr($string, 12, 2), substr($string, 4, 2), substr($string, 6, 2), substr($string, 0, 4));
    } elseif (is_numeric($string)) {
        return (int)$string;
    } else {
        $time = strtotime($string);
        if ($time === -1 || $time === false) {
            return time();
        } return $time;
    }
} function smarty_mb_str_replace($search, $replace, $subject, &$count = 0)
{
    if (!is_array($search) && is_array($replace)) {
        return false;
    } if (is_array($subject)) {
        foreach ($subject as &$string) {
            $string = smarty_mb_str_replace($search, $replace, $string, $c);
            $count += $c;
        }
    } elseif (is_array($search)) {
        if (!is_array($replace)) {
            foreach ($search as &$string) {
                $subject = smarty_mb_str_replace($string, $replace, $subject, $c);
                $count += $c;
            }
        } else {
            $n = max(count($search), count($replace));
            while ($n--) {
                $subject = smarty_mb_str_replace(current($search), current($replace), $subject, $c);
                $count += $c;
                next($search);
                next($replace);
            }
        }
    } else {
        $mb_reg_charset = mb_regex_encoding();
        $reg_is_unicode = !strcasecmp($mb_reg_charset, "UTF-8");
        if (!$reg_is_unicode) {
            mb_regex_encoding("UTF-8");
        } $current_charset = mb_regex_encoding();
        $convert_result = (bool)strcasecmp(\Smarty\Smarty::$_CHARSET, $current_charset);
        if ($convert_result) {
            $subject = mb_convert_encoding($subject, $current_charset, \Smarty\Smarty::$_CHARSET);
            $search = mb_convert_encoding($search, $current_charset, \Smarty\Smarty::$_CHARSET);
            $replace = mb_convert_encoding($replace, $current_charset, \Smarty\Smarty::$_CHARSET);
        } $parts = mb_split(preg_quote($search), $subject ?? "") ?: array();
        if (!$reg_is_unicode) {
            mb_regex_encoding($mb_reg_charset);
        } if ($parts === false) {
            throw new Exception("Source string is not a valid $current_charset sequence (probably)");
        } $count = count($parts) - 1;
        $subject = implode($replace, $parts);
        if ($convert_result) {
            $subject = mb_convert_encoding($subject, \Smarty\Smarty::$_CHARSET, $current_charset);
        }
    } return $subject;
} function smarty_function_escape_special_chars($string)
{
    if (!is_array($string)) {
        $string = htmlspecialchars((string) $string, ENT_COMPAT, \Smarty\Smarty::$_CHARSET, false);
    } return $string;
} function smarty_mb_wordwrap($str, $width = 75, $break = "\n", $cut = false)
{
    $tokens = preg_split('!(\s)!S' . \Smarty\Smarty::$_UTF8_MODIFIER, $str, -1, PREG_SPLIT_NO_EMPTY + PREG_SPLIT_DELIM_CAPTURE);
    $length = 0;
    $t = '';
    $_previous = false;
    $_space = false;
    foreach ($tokens as $_token) {
        $token_length = mb_strlen($_token, \Smarty\Smarty::$_CHARSET);
        $_tokens = array($_token);
        if ($token_length > $width) {
            if ($cut) {
                $_tokens = preg_split('!(.{' . $width . '})!S' . \Smarty\Smarty::$_UTF8_MODIFIER, $_token, -1, PREG_SPLIT_NO_EMPTY + PREG_SPLIT_DELIM_CAPTURE);
            }
        } foreach ($_tokens as $token) {
            $_space = !!preg_match('!^\s$!S' . \Smarty\Smarty::$_UTF8_MODIFIER, $token);
            $token_length = mb_strlen($token, \Smarty\Smarty::$_CHARSET);
            $length += $token_length;
            if ($length > $width) {
                if ($_previous) {
                    $t = mb_substr($t, 0, -1, \Smarty\Smarty::$_CHARSET);
                } if (!$_space) {
                    if (!empty($t)) {
                        $t .= $break;
                    } $length = $token_length;
                }
            } elseif ($token === "\n") {
                $length = 0;
            } $_previous = $_space;
            $t .= $token;
        }
    } return $t;
}
