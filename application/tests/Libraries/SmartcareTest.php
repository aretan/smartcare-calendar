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

        Smartcare::conbineNyuin($nyuinList);

        $this->assertEquals(30, $nyuinList[0]['warrantyMax']);
        $this->assertEquals(30, $nyuinList[1]['warrantyMax']);
        $this->assertEquals(30, $nyuinList[2]['warrantyMax']);
    }

    /**
     * function tsuinResult
     * othersに２つ入ってるケース
     */
    public function testTsuinResultCase1()
    {
        $nyuinList = [
            [
                'warrantyStart' => '2019-01-01',
                'warrantyEnd' => '2019-10-01',
                'warrantyMax' => 30,
                'start' => '2019-03-01',
            ],
        ];
        $shujutsuList = [
            [
                'warrantyStart' => '2019-03-01',
                'warrantyEnd' => '2019-10-01',
                'warrantyMax' => 30,
                'date' => '2019-02-28',
            ],
        ];
        for ($i=1; $i<=15; $i++) {
            $tsuinList[] = [
                'date' => "2019-01-{$i}",
            ];
        }
        for ($i=1; $i<=16; $i++) {
            $tsuinList[] = [
                'date' => "2019-02-{$i}",
            ];
        }
        $result = Smartcare::tsuinResult($tsuinList, $nyuinList, $shujutsuList);

        foreach ($result as $warranty) {
            if ($warranty['type'] == 'other') {
                $this->assertEquals(1, count($warranty['tsuin']));
            } elseif ($warranty['type'] == 'nyuin') {
                $this->assertEquals(30, count($warranty['tsuin']));
            } elseif ($warranty['type'] == 'shujutsu') {
                $this->assertEquals(0, count($warranty['tsuin']));
            }
        }
    }
}
