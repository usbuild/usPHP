<?php
/**
 * Created by JetBrains PhpStorm.
 * User: usbuild
 * Date: 12-9-5
 * Time: 下午8:59
 * To change this template use File | Settings | File Templates.
 */
class Test extends ActiveRecord
{

    protected function tableInfo()
    {
        return array(
            'name' => 'test',
            'pk' => 'id',
            'attributes' => array('id', 'name')
        );
    }
}
