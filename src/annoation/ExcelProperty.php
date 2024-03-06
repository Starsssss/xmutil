<?php

namespace Ximu\Xmutil\annoation;

use Attribute;

/**
 * excel导入导出元数据。
 * @Annotation
 * @Target("PROPERTY")
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class ExcelProperty
{
    public function __construct(
        public string $value,
        public int $index,
        public array $dictData = [],
        public int $width = 20,
    ) {
    }
}
