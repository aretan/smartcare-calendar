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
                'warrantyStart' => '2019-10-01',
                'warrantyEnd' => '2019-10-02',
                'warrantyMax' => 30,
            ],
            [
                'warrantyStart' => '2019-10-03',
                'warrantyEnd' => '2019-10-05',
                'warrantyMax' => 30,
            ],
            [
                'warrantyStart' => '2019-10-06',
                'warrantyEnd' => '2019-10-08',
                'warrantyMax' => 30,
            ],
        ];

        $smartcare = new Smartcare();
        $nyuinList = $smartcare->conbineNyuin($nyuinList);

        $this->assertEquals(30, $nyuinList[0]['warrantyMax']);
        $this->assertEquals(30, $nyuinList[1]['warrantyMax']);
        $this->assertEquals(30, $nyuinList[2]['warrantyMax']);
    }

    /**
     * function separateTsuin
     * othersに２つ入ってるケース
     */
    public function testSeparateTsuinCase1()
    {
        $smartcare = new Smartcare();
        $result = $smartcare->separateTsuin(null, null, null);

        $this->assertEquals(2, count($result['others']));
    }
}
