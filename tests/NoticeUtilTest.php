<?php


use Ximu\Xmutil\NoticeUtil;
use PHPUnit\Framework\TestCase;

class NoticeUtilTest extends TestCase
{

    public function testSendTextMessage()
    {
        // 请从安全的配置管理系统获取以下变量
        $ACCESS_TOKEN = '0675603f3f8abd2cdb7359b62a61b067153ddebf00305dbadbdb91fff7a91fb6';
        $secret = 'SEC72865b71c7e8cb607766a8b216d991817d1a18458fa34b31e37ff2778828640d';
        $webhookUrl = "https://oapi.dingtalk.com/robot/send?access_token={$ACCESS_TOKEN}";
        $time = time() * 1000;
        $sign = $time . "\n" . $secret;
        $sign = hash_hmac('sha256', $sign, $secret, true);
        $sign = utf8_encode(urlEncode(base64_encode($sign)));
        $webhookUrl = $webhookUrl . "&timestamp={$time}&sign={$sign}";
        $response = NoticeUtil::sendTextMessage($webhookUrl, 'Hello World');
        $this->assertEquals('{"errcode":0,"errmsg":"ok"}', $response);
    }

    public function testSendLinkMessage()
    {

    }
}
