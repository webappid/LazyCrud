<?php


namespace WebAppId\LazyCrud;

use Faker\Factory as Faker;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 12/04/20
 * Time: 01.21
 * Trait ColumnList
 * @package WebAppId\LazyCrud
 */
trait ColumnList
{
    /**
     * @var int
     */
    protected $propertiesModel;

    protected function getFaker()
    {
        return Faker::create('id_ID');
    }

    /**
     * @param string $arg
     * @return string
     */
    public function getColumnProperties(string $arg): string
    {

        $table = Str::snake(Str::pluralStudly(class_basename($arg)));

        $resultList = DB::select(sprintf("select EXTRA, `COLUMN_NAME`, `IS_NULLABLE`, `CHARACTER_MAXIMUM_LENGTH` from information_schema.columns where TABLE_SCHEMA = '%s' AND `TABLE_NAME` = '%s'", env('DB_DATABASE'), $table));

        $property = "";

        if(count($resultList)>0) {
            if ($resultList[0]->EXTRA == 'auto_increment') {
                $autoincrementColumn = $resultList[0]->COLUMN_NAME;
            } else {
                $autoincrementColumn = '';
            }

            $propertyList = [];

            foreach ($resultList as $result) {
                $columnName = $result->COLUMN_NAME;
                $ignore = false;
                foreach (Config::get('lazycrud.inject.controller') as $key => $value) {
                    if ($key == $columnName) {
                        $ignore = true;
                    }
                }
                if ($columnName != 'created_at' && $columnName != 'updated_at' && $autoincrementColumn != $columnName) {
                    $type = Schema::connection(null)->getColumnType($table, $columnName);

                    switch ($type) {
                        case "decimal":
                            $realType = "float";
                            $fakerData = '$this->getFaker()->randomFloat()';
                            break;
                        case "timestamp":
                        case "datetime":
                            $fakerData = '$this->getFaker()->dateTime()';
                            $realType = "string";
                            break;
                        case "text":
                            $realType = "string";
                            $fakerData = '$this->getFaker()->text(' . $result->CHARACTER_MAXIMUM_LENGTH . ');';
                            break;
                        case "tinyint":
                        case "bigint":
                        case "integer":
                            $realType = "int";
                            $fakerData = '$this->getFaker()->randomNumber()';
                            break;
                        default:
                            $fakerData = '$this->getFaker()->text(' . $result->CHARACTER_MAXIMUM_LENGTH . ')';
                            $realType = $type;
                            break;
                    }
                    switch ($this->propertiesModel) {
                        case 1:
                            if (!$ignore) {
                                $items = [];
                                $items[] = $realType;
                                if ($result->IS_NULLABLE == 'NO') {
                                    $items[] = 'required';
                                }
                                if ($type == 'string') {
                                    $items[] = 'max:' . $result->CHARACTER_MAXIMUM_LENGTH;
                                }
                                $propertyList[] = "'" . $columnName . "' => '" . implode('|', $items) . "'";
                            }
                            break;
                        case 2:
                            $property .= '
    /**
     * @var ' . $realType . '
     */
    public $' . $columnName . ';
                ';
                            break;
                        case 3:

                            $property .= '$dummy->' . $columnName . ' = ' . $fakerData . ';
            ';
                            break;
                    }
                }
            }
            $i = $this->propertiesModel;

            if ($i == 1) {
                $property = '[
            ' .
                    implode(',
            ', $propertyList) . '
         ]';
            }
        }

        return $property;
    }
}
