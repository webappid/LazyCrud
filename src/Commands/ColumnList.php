<?php


namespace WebAppId\LazyCrud\Commands;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
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
                if ($column->EXTRA != 'auto_increment' || $this->propertiesModel == 4) {
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
            case "double":
            case "float":
                $data['realType'] = "double";
                $data['fakerData'] = '$this->getFaker()->randomFloat()';
                break;
            case "date":
            case "timestamp":
            case "datetime":
            case "time":
                $data['fakerData'] = '$this->getFaker()->date("Y-m-d H:m:i")';
                $data['realType'] = "string";
                break;
            case "text":
            case "longtext":
            case "mediumtext":
            case "tinytext":
            case "char":
            case "enum":
            case "varchar":
                $data['realType'] = "string";
                $data['fakerData'] = '$this->getFaker()->text(' . $result->CHARACTER_MAXIMUM_LENGTH . ')';
                break;
            case "tinyint":
            case "smallint":
            case "mediumint":
            case "bigint":
            case "int":
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

            if ($this->propertiesModel == 1) {
                $property = '[
            ' . implode(',
            ', $propertyList) . '
         ]';
            } elseif ($this->propertiesModel == 4) {
                $columnList[] = "'" . $this->table . '.' . $autoincrementColumn . "'";
                $columnList = array_merge($columnList, $propertyList);
                $columnList[] = "'" . $this->table . ".created_at'";
                $columnList[] = "'" . $this->table . ".updated_at'";
                $columnList = array_merge($columnList, $this->getReferenced());
                $property = '
            [
                ' . implode(',
                ', $columnList) . '
                ';
                $property .= ']';

            }
        }
        return $property;
    }
}
