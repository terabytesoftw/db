<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yiiunit\framework\db;


use yii\db\ColumnSchemaBuilder;
use yii\db\Exception;
use yii\db\Expression;
use yii\db\Schema;
use yiiunit\TestCase;

/**
 * ColumnSchemaBuilderTest tests ColumnSchemaBuilder
 */
class ColumnSchemaBuilderTest extends TestCase
{
    /**
     * @param string $type
     * @param integer $length
     * @return ColumnSchemaBuilder
     */
    public function getColumnSchemaBuilder($type, $length = null)
    {
        return new ColumnSchemaBuilder($type, $length);
    }

    /**
     * @return array
     */
    public function typesProvider()
    {
        return [
            ['integer', Schema::TYPE_INTEGER, null, [
                ['unsigned'],
            ]],
            ['integer(10)', Schema::TYPE_INTEGER, 10, [
                ['unsigned'],
            ]],
            ['timestamp() WITH TIME ZONE NOT NULL', 'timestamp() WITH TIME ZONE', null, [
                ['notNull']
            ]],
            ['timestamp() WITH TIME ZONE DEFAULT NOW()', 'timestamp() WITH TIME ZONE', null, [
                ['defaultValue', new Expression('NOW()')]
            ]],
        ];
    }

    /**
     * @dataProvider typesProvider
     */
    public function testCustomTypes($expected, $type, $length, $calls)
    {
        $this->checkBuildString($expected, $type, $length, $calls);
    }

    /**
     * @param string $expected
     * @param string $type
     * @param integer $length
     * @param array $calls
     */
    public function checkBuildString($expected, $type, $length, $calls)
    {
        $builder = $this->getColumnSchemaBuilder($type, $length);
        foreach ($calls as $call) {
            $method = array_shift($call);
            call_user_func_array([$builder, $method], $call);
        }

        self::assertEquals($expected, $builder->__toString());
    }
}