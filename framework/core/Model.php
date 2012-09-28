<?php
/**
 * Created by JetBrains PhpStorm.
 * User: usbuild
 * Date: 12-9-5
 * Time: 下午8:53
 * To change this template use File | Settings | File Templates.
 */
abstract class Model
{
    private $_db;
    private static $_metaModels = array();

    public function __construct()
    {
        $this->_db = USP::app()->db;
    }

    public static function model($className) {
        if(array_key_exists($className, self::$_metaModels)) {
            return self::$_metaModels[$className];
        } else {
            $model = self::$_metaModels[$className] = new $className();
            return $model;
        }
    }

    private static function filter_array($src, $filter)
    {
        $record = array();
        foreach ($filter as $s) {
            $record[$s] = $src[$s];
        }
        return $record;
    }

    /*
     * config[projection, where]
     */
    private function select(array $config)
    {
        $tableInfo = $this->tableInfo();
        $projection = isset($config['projection']) ? $config['projection'] : array('*');
        $where = isset($config['where']) ? $config['where'] : array();
        $proj_str = implode(', ', $projection);
        $sql = "SELECT {$proj_str} FROM {$tableInfo['name']}";
        $where_str = array();
        foreach ($where as $key => $value) {
            if (is_string($value))
                $where_str[] = " {$key} = '{$value}' ";
            else
                $where_str[] = " {$key} = {$value} ";
        }
        if (count($where_str) > 0) {
            $sql .= ' WHERE ' . join(' AND ', $where_str);
        }
        if(isset($config['limit'])) {
            $sql .= " LIMIT {$config['limit']} ";
        }
        $res = array();
        $rows = $this->_db->pdo->query($sql);
        if ($rows) {
            foreach ($rows as $row) {
                $res[] = self::filter_array($row, $tableInfo['attributes']);
            }
        }
        return $res;

    }

    public function findAllByAttributes(array $attrs)
    {
        return $this->select(array('where' => $attrs));
    }

    public function findByAttributes(array $attrs)
    {
        return $this->select(array('where' => $attrs, 'limit' => 1));
    }

    public function findByPk($pk)
    {
        $info = $this->tableInfo();
        return $this->select(array('where' => array($info['pk'] => $pk), 'limit'=>1));
    }

    public function findAllByPk($pk)
    {
        $info = $this->tableInfo();
        return $this->select(array('where' => array($info['pk'] => $pk)));
    }
}
