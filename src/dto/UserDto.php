<?php

namespace Ximu\Xmutil\dto;

use Ximu\Xmutil\annoation\ExcelProperty;

class UserDto
{
    #[ExcelProperty(value: "用户名", index: 0)]
    public string $username;

    #[ExcelProperty(value: "密码", index: 3)]
    public string $password;

    #[ExcelProperty(value: "昵称", index: 1)]
    public string $nickname;

    #[ExcelProperty(value: "手机", index: 2)]
    public string $phone;

    #[ExcelProperty(value: "状态", index: 4, dictData: [1 => '类别1', 2 => '类别2', 3 => '类别3', 4 => '类别4'])]
    public string $status;
}
