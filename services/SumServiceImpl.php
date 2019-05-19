<?php
/**
 * @author xialeistudio
 * @date 2019-05-19
 */

namespace app\services;

use app\thrift\SumService\SumServiceIf;
use yii\base\BaseObject;

/**
 * Class SumServiceImpl
 * @package app\services
 */
class SumServiceImpl extends BaseObject implements SumServiceIf
{
    /**
     * @param int $a
     * @param int $b
     * @return int
     * @throws \yii\db\Exception
     */
    public function sum($a, $b)
    {
        return \Yii::$app->db->createCommand('SELECT 1+1')->queryScalar();
    }
}