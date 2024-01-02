<?php

namespace Ximu\Xmutil;

use PHPUnit\Framework\TestCase;

class StringUtilTest extends TestCase
{

    //多个测试用例
    public function testHasChinese()
    {
        $this->assertTrue(StringUtil::hasChinese("你好世界")); // 纯中文字符串，期望结果为 true
        $this->assertFalse(StringUtil::hasChinese("Hello")); // 纯英文字符串，期望结果为 false
        $this->assertTrue(StringUtil::hasChinese("Hello 你好 World")); // 包含中文和英文的字符串，期望结果为 true
        $this->assertFalse(StringUtil::hasChinese("12345")); // 纯数字字符串，期望结果为 false
        $this->assertTrue(StringUtil::hasChinese("测试123")); // 包含中文和数字的字符串，期望结果为 true
        $this->assertFalse(StringUtil::hasChinese("!@#$%")); // 仅包含特殊字符的字符串，期望结果为 false
        $this->assertFalse(StringUtil::hasChinese("")); // 空字符串，期望结果为 false
    }
}
