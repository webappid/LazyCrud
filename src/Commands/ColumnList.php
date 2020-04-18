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

    /**
     * @var string
     */
    protected $secondColumn = null;

    /**
     * @var string
     */
    protected $table;

    /**
     * @var string
     */
    protected $joinTable = null;

    protected function getFaker()
    {
        return Faker::create('id_ID');
    }

    protected function getColumn(string $table = '')
    {
        return DB::select(sprintf("SELECT `EXTRA`, `COLUMN_COMMENT`, `DATA_TYPE`, `COLUMN_NAME`, `IS_NULLABLE`, `CHARACTER_MAXIMUM_LENGTH` FROM information_schema.columns WHERE TABLE_SCHEMA = '%s' AND `TABLE_NAME` = '%s'", env('DB_DATABASE'), $table == '' ? $this->table : $table));
    }

    protected function getReferenced()
    {
        $resultList = DB::select(sprintf("SELECT `REFERENCED_TABLE_NAME`, `REFERENCED_COLUMN_NAME`, `COLUMN_NAME` FROM information_schema.KEY_COLUMN_USAGE WHERE `CONSTRAINT_SCHEMA` = '%s' AND `TABLE_NAME` = '%s' AND REFERENCED_TABLE_NAME IS NOT NULL;", env('DB_DATABASE'), $this->table));
        $propertyList = [];
        $joinTables = [];
        $tables = [];
        foreach ($resultList as $result) {
            $columnList = $this->getColumn($result->REFERENCED_TABLE_NAME);
            if (!in_array($result->REFERENCED_TABLE_NAME, $tables)) {
                $tableName = $result->REFERENCED_TABLE_NAME;
                $alias = null;
            } else {
                $columnNames = explode('_', $result->COLUMN_NAME);
                $tableName = $columnNames[0] . '_' . $result->REFERENCED_TABLE_NAME;
                $alias = ' AS ' . $columnNames[0];
            }
            $tables[] = $tableName;

            foreach ($columnList as $column) {
                if ($column->EXTRA != 'auto_increment') {
                    $propertyList[] = "'" . $tableName . "." . $column->COLUMN_NAME . ($alias != null ? $alias . '_' . $column->COLUMN_NAME : '') . "'";
                }
            }
            $joinTables[] = "->join('" . $result->REFERENCED_TABLE_NAME . " as " . $tableName . "', '" . $this->table . "." . $result->COLUMN_NAME . "', '" . $tableName . "." . $result->REFERENCED_COLUMN_NAME . "')";
        }

        $this->joinTable = implode('
                ', $joinTables);

        return $propertyList;
    }

    protected function getPhpDataType($result)
    {
        $type = $result->DATA_TYPE;

        $data = [];

        switch ($type) {
            case "decimal":
                $data['realType'] = "float";
                $data['fakerData'] = '$this->getFaker()->randomFloat()';
                break;
            case "timestamp":
            case "datetime":
                $data['fakerData'] = '$this->getFaker()->dateTime()';
                $data['realType'] = "string";
                break;
            case "text":
                $data['realType'] = "string";
                $data['fakerData'] = '$this->getFaker()->text(' . $result->CHARACTER_MAXIMUM_LENGTH . ');';
                break;
            case "tinyint":
            case "bigint":
            case "integer":
                $data['realType'] = "int";
                $data['fakerData'] = '$this->getFaker()->randomNumber()';
                break;
            default:
                $data['fakerData'] = '$this->getFaker()->text(' . $result->CHARACTER_MAXIMUM_LENGTH . ')';
                $data['realType'] = $type;
                break;
        }
        return $data;
    }

    /**
     * @param string $arg
     * @return string
     */
    public function getColumnProperties(string $arg): string
    {

        $this->table = Str::snake(Str::pluralStudly(class_basename($arg)));

        $resultList = $this->getColumn();

        $property = "";

        if (count($resultList) > 0) {
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
                    if ($this->secondColumn == null) {
                        $this->secondColumn = $columnName;
                    }

                    //$type = Schema::connection(null)->getColumnType($table, $columnName);

                    $phpDataType = $this->getPhpDataType($result);

                    switch ($this->propertiesModel) {
                        case 1:
                            if (!$ignore) {
                                $items = [];
                                $items[] = $phpDataType['realType'];
                                if ($result->IS_NULLABLE == 'NO') {
                                    $items[] = 'required';
                                }
                                if ($phpDataType['realType'] == 'string') {
                                    $items[] = 'max:' . $result->CHARACTER_MAXIMUM_LENGTH;
                                }
                                $propertyList[] = "'" . $columnName . "' => '" . implode('|', $items) . "'";
                            }
                            break;
                        case 2:
                            $property .= '
    /**
     * @var ' . $phpDataType['realType'] . '
     */
    public $' . $columnName . ';
                ';
                            break;
                        case 3:
                            $property .= '$dummy->' . $columnName . ' = ' . $phpDataType['fakerData'] . ';
            ';
                            break;
                        default:
                            $propertyList[] = "'" . $this->table . '.' . $columnName . "'";
                            break;
                    }
                }
            }
            $i = $this->propertiesModel;

            if ($i == 1) {
                $property = '[
            ' . implode(',
            ', $propertyList) . '
         ]';
            } elseif ($i == 4) {
                $propertyList[] = "'" . $this->table . '.' . $autoincrementColumn . "'";
                $propertyList[] = "'" . $this->table . ".created_at'";
                $propertyList[] = "'" . $this->table . ".updated_at'";
                $propertyList = array_merge($propertyList, $this->getReferenced());
                $property = '
                (
                ' . implode(',
                ', $propertyList) . '
                ';
                $property .= ')
                ';
                if ($this->joinTable != null) {
                    $property .= $this->joinTable;
                }
            }
        }
        return $property;
    }
}
