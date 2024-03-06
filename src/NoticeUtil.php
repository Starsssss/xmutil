<?php

namespace Ximu\Xmutil;

class NoticeUtil
{
    private $webhookUrl;

    public function __construct($webhookUrl)
    {
        $this->webhookUrl = $webhookUrl;
    }

    public function sendTextMessage($content)
    {
        $data = array(
            'msgtype' => 'text',
            'text' => array(
                'content' => $content
            )
        );

        return $this->sendMessage($data);
    }

    public function sendLinkMessage($title, $text, $messageUrl, $picUrl = null)
    {
        $data = array(
            'msgtype' => 'link',
            'link' => array(
                'title' => $title,
                'text' => $text,
                'messageUrl' => $messageUrl,
                'picUrl' => $picUrl
            )
        );

        return $this->sendMessage($data);
    }

    private function sendMessage($data)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->webhookUrl);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //SSL certificate problem: unable to get local issuer certificate
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            echo 'Error:' . curl_error($curl);
        }
        curl_close($curl);

        return $response;
    }
}

$ACCESS_TOKEN = '0675603f3f8abd2cdb7359b62a61b067153ddebf00305dbadbdb91fff7a91fb6';
$secret = 'SEC72865b71c7e8cb607766a8b216d991817d1a18458fa34b31e37ff2778828640d';
// 示例用法  https://oapi.dingtalk.com/robot/send?access_token=0675603f3f8abd2cdb7359b62a61b067153ddebf00305dbadbdb91fff7a91fb6
$webhookUrl = "https://oapi.dingtalk.com/robot/send?access_token=$ACCESS_TOKEN";
$time = time() * 1000;
$sign = $time . "\n" . $secret;
$sign = hash_hmac('sha256', $sign, $secret, true);
$sign = utf8_encode(urlEncode(base64_encode($sign)));
$webhookUrl = $webhookUrl . "&timestamp={$time}&sign={$sign}";
$robot = new NoticeUtil($webhookUrl);

$response = $robot->sendTextMessage('123');
var_dump($response, $webhookUrl);