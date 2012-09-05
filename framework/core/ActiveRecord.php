<?php
/**
 * Created by JetBrains PhpStorm.
 * User: usbuild
 * Date: 12-9-5
 * Time: 下午8:53
 * To change this template use File | Settings | File Templates.
 */
abstract class ActiveRecord
{
    private $_db;

    public function __construct()
    {
        $this->_db = USP::app()->db;
    }

    abstract protected function tableInfo();

    private static function  filter_array($src, $filter)
    {
        $record = array();
        foreach ($filter as $s) {
            $record[$s] = $src[$s];
        }
        return $record;
    }


    public function findAllByAttributes(array $attrs)
    {
        $tableInfo = $this->tableInfo();
        $sql = "SELECT * FROM {$tableInfo['name']}";
        $where = array();
        foreach ($attrs as $key => $value) {
            if (is_string($value))
                $where[] = " '{$key}' = '{$value}' ";
            else
                $where[] = " '{$key}' = {$value} ";
        }
        if (count($where) > 0)
            $sql .= ' WHERE ' . join(' AND ', $where);

        $res = array();
        foreach ($this->_db->pdo->query($sql) as $row) {
            $res[] = self::filter_array($row, $tableInfo['attributes']);
        }
        return $res;
    }

    public function findByAttributes()
    {
    }

    public function findByPk()
    {
    }

    public function findAllByPk()
    {
    }
}
