<?php namespace App\Libraries;

class SmartcareTest extends \CIUnitTestCase
{
    /**
     * function conbineNyuin
     * 2つめのwarrantyMaxが0になるはずのケース
     */
    public function testConbineNyuinCase1()
    {
        $nyuinList = [
            [
                'start' => 'start',
                'warrantyStart' => '2019-10-01',
                'warrantyEnd' => '2019-10-02',
                'warrantyMax' => 30,
            ],
            [
                'start' => 'start',
                'warrantyStart' => '2019-10-03',
                'warrantyEnd' => '2019-10-05',
                'warrantyMax' => 30,
            ],
            [
                'start' => 'start',
                'warrantyStart' => '2019-10-06',
                'warrantyEnd' => '2019-10-08',
                'warrantyMax' => 30,
            ],
        ];

        Smartcare::conbineNyuin($nyuinList);

        $this->assertEquals(30, $nyuinList[0]['warrantyMax']);
        $this->assertEquals(30, $nyuinList[1]['warrantyMax']);
        $this->assertEquals(30, $nyuinList[2]['warrantyMax']);
    }
}
