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
            new \App\Entities\Nyuin([
                'start' => '2019-09-30',
                'end' => '2019-10-03',
                'warrantyStart' => '2019-09-30',
                'warrantyEnd' => '2019-10-13',
                'warrantyMax' => 30,
            ]),
            new \App\Entities\Nyuin([
                'start' => '2019-09-30',
                'end' => '2019-10-03',
                'warrantyStart' => '2019-09-30',
                'warrantyEnd' => '2019-10-13',
                'warrantyMax' => 30,
            ]),
            new \App\Entities\Nyuin([
                'start' => '2019-09-30',
                'end' => '2019-10-03',
                'warrantyStart' => '2019-09-30',
                'warrantyEnd' => '2019-10-13',
                'warrantyMax' => 30,
            ]),
        ];

        $smartcare = new Smartcare();
        $nyuinList = $smartcare->conbineNyuin($nyuinList);

        $this->assertEquals(0, $nyuinList[1]->warrantyMax);
    }

    /**
     * function separateTsuin
     * 2つめのwarrantyMaxが0になるはずのケース
     */
    public function testSeparateTsuinCase1()
    {
        $smartcare = new Smartcare();
        $result = $smartcare->separateTsuin(null, null, null);

        $this->assertEquals(2, count(json_decode($result->data, true)['others']));
    }
}
