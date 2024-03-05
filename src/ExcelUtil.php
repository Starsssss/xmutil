<?php

namespace Ximu\Xmutil;

use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use ReflectionClass;
use Ximu\Xmutil\annoation\ExcelProperty;

/**
 * Excel工具类
 */
class ExcelUtil
{
    private static array $annotationCache = [];

    /**
     * 导出excel
     * @author: crx
     * @time: 2024/3/5 9:19
     * @param array $data 数据源
     * @param string $filePath 文件路径
     * @param string $dtoClass 数据对象类
     * @return void
     * @throws \ReflectionException
     */
    public static function export(array $data, string $filePath, string $dtoClass): void
    {
        if (empty($data)) {
            throw new Exception('数据为空');
        }

        $reflectionClass = new ReflectionClass($dtoClass);
        $properties = $reflectionClass->getProperties();
        if (empty(self::$annotationCache)) {
            // 预先解析注解并放入缓存
            self::parseAnnotations($properties);
        }


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // 填充表头
        $headerRow = 1;
        $columnIndex = 0;
        foreach ($properties as $property) {
            $annotation = self::$annotationCache[$property->getName()] ?? null;
            if ($annotation !== null) {
                $sheet->setCellValue(self::getColumnIndex($columnIndex) . $headerRow, $annotation->value);
                $columnIndex++;
            }
        }

        // 填充数据
        $dataRow = 2;
        foreach ($data as $row) {
            $columnIndex = 0;
            foreach ($properties as $property) {
                $annotation = self::$annotationCache[$property->getName()] ?? null;
                if ($annotation !== null) {
                    $propertyName = $property->getName();
                    $value = $row[$propertyName] ?? '';
                    if (!empty($annotation->dictData)) {
                        $value = $annotation->dictData[$value] ?? '';
                    }
                    $sheet->setCellValue(self::getColumnIndex($columnIndex) . $dataRow, $value);
                    $columnIndex++;
                }
            }
            $dataRow++;
        }

        // 保存为 Excel 文件
        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);
    }

    /**
     * 导入excel文件
     * @author: crx
     * @time: 2024/3/5 9:18
     * @param string $filePath
     * @param string $dtoClass
     * @return array
     * @throws \ReflectionException
     */
    public static function import(string $filePath, string $dtoClass): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $dataRow = 2;

        $reflectionClass = new ReflectionClass($dtoClass);
        $properties = $reflectionClass->getProperties();
        if (empty(self::$annotationCache)) {
            // 预先解析注解并放入缓存
            self::parseAnnotations($properties);
        }
        $data = [];

        foreach ($sheet->getRowIterator($dataRow) as $row) {
            $rowData = [];
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            foreach ($properties as $property) {
                $annotation = self::$annotationCache[$property->getName()];
                if ($annotation !== null) {
                    $columnIndex = $annotation->index;
                    $cell = $sheet->getCell(self::getColumnIndex($columnIndex) . $dataRow);
                    $value = $cell->getValue();
                    $propertyName = $property->getName();
                    $rowData[$propertyName] = $value;
                }
            }

            $data[] = $rowData;
        }

        return $data;
    }

    private static function parseAnnotations(array $properties): void
    {
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $attributes = $property->getAttributes(ExcelProperty::class);
            if (empty($attributes)) {
                continue;
            }

            $annotation = $attributes[0]->newInstance();
            self::$annotationCache[$propertyName] = $annotation;
        }
    }

    /**
     * 获取 excel 列索引.
     */
    protected static function getColumnIndex(int $columnIndex = 0): string
    {
        if ($columnIndex < 26) {
            return chr(65 + $columnIndex);
        }
        if ($columnIndex < 702) {
            return chr(64 + intval($columnIndex / 26)) . chr(65 + $columnIndex % 26);
        }
        return chr(64 + intval(($columnIndex - 26) / 676)) . chr(65 + intval((($columnIndex - 26) % 676) / 26)) . chr(
                65 + $columnIndex % 26
            );
    }
}
