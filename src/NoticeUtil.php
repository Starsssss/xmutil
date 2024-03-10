<?php

namespace Ximu\Xmutil;

class NoticeUtil
{
    private static function sendRequest($webhookUrl, $data)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $webhookUrl);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // Ensure SSL certificate verification is enabled
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            // Use a log system instead of throw a exception
            throw new \Exception('Curl error: ' . curl_error($curl));
        }
        curl_close($curl);

        return $response;
    }

    public static function sendTextMessage($webhookUrl, $content)
    {
        if (empty($content)) {
            throw new \InvalidArgumentException('Content cannot be empty.');
        }
        $data = [
            'msgtype' => 'text',
            'text' => ['content' => $content]
        ];

        return self::sendRequest($webhookUrl, $data);
    }

    public static function sendLinkMessage($webhookUrl, $title, $text, $messageUrl, $picUrl = null)
    {
        if (empty($title) || empty($text) || empty($messageUrl)) {
            throw new \InvalidArgumentException('Title, text, and messageUrl cannot be empty.');
        }
        $data = [
            'msgtype' => 'link',
            'link' => [
                'title' => $title,
                'text' => $text,
                'messageUrl' => $messageUrl,
                'picUrl' => $picUrl
            ]
        ];
        return self::sendRequest($webhookUrl, $data);
    }
}
