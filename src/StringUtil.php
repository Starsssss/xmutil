<?php

namespace  Ximu\Xmutil;

class StringUtil
{
    public static function aa()
    {
        echo  'ximu';
    }

    /**
     * ximuStr
     * @Date 2023-09-09
     * @author chenruixin
     * @param string|null $str
     * @return void
     */
    public static function ximu(?string $str)
    {
        echo  'ximu' . $str;
    }

    /**
     * 判断字符串是否有中文
     * @param $str string 字符串
     * @return bool true:有中文 false:没有中文
     */
    public static function hasChinese(string $str) :bool
    {
        return (bool)preg_match("/[\x7f-\xff]/", $str);
    }
}
