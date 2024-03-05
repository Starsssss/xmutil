<?php

namespace Ximu\Xmutil;

use PHPUnit\Framework\TestCase;
use Ximu\Xmutil\dto\UserDto;

class ExcelUtilTest extends TestCase
{


    /**
     * @throws \Exception
     */
    public function testExport()
    {
        // 测试数据
        $data = [
            ["username" => '是熬到三点', "password" => '121', "nickname" => '单身的', "phone" => 3, "status" => 4],
            ["username" => '瞎嘚瑟嘚瑟', "password" => '121', "nickname" => '实打实的', "phone" => 3, "status" => 4],
        ];
        // 导出 Excel
        $exportFilePath = 'exported_file' . mt_rand(111, 999) . '.xlsx'; // 替换为实际的文件路径
        ExcelUtil::export($data, $exportFilePath, UserDto::class);
    }

    public function testImport()
    {
        var_dump(__DIR__);
        $res = ExcelUtil::import('/opt/project/1.xlsx', UserDto::class);
        var_dump($res);
    }
}
