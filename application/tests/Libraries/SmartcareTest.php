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
                'warrantyStart' => '2019-10-02',
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

        $nyuinList = Smartcare::conbineNyuin($nyuinList);

        $this->assertEquals(30, $nyuinList[0]['warrantyMax']);
        $this->assertEquals('2019-10-05', $nyuinList[0]['warrantyEnd']);
        $this->assertEquals(0, $nyuinList[1]['warrantyMax']);
        $this->assertEquals(30, $nyuinList[2]['warrantyMax']);
    }

    public function testCalcTsuin()
    {
        $shoken = [
            'ukeban' => [
                ['id' => 'ukeban-1'],
                ['id' => 'ukeban-2'],
            ],
            'nyuin' => [
                [
                    'id' => '1',
                    'start' => 'nyuin-1',
                    'ukeban_id' => 'ukeban-2',
                    'start' => '2019-10-10',
                    'end' => '2019-10-11',
                    'warrantyStart' => '2019-10-01',
                    'warrantyEnd' => '2019-10-31',
                    'warrantyMax' => '5',
                ],
                [
                    'id' => '2',
                    'start' => 'nyuin-2',
                    'ukeban_id' => 'ukeban-1',
                    'start' => '2019-10-15',
                    'end' => '2019-10-16',
                    'warrantyStart' => '2019-10-15',
                    'warrantyEnd' => '2019-11-15',
                    'warrantyMax' => '5',
                ],
            ],
            'shujutsu' => [
                [
                    'id' => '1',
                    'date' => 'shujutsu-1',
                    'ukeban_id' => 'ukeban-2',
                    'warrantyStart' => '2019-10-15',
                    'warrantyEnd' => '2019-10-31',
                    'warrantyMax' => '5',
                ],
            ],
            'tsuin' => [
                ['ukeban_id' => 'ukeban-2', 'date' => '2019-10-01'],
                ['ukeban_id' => 'ukeban-2', 'date' => '2019-10-02'],
                ['ukeban_id' => 'ukeban-2', 'date' => '2019-10-03'],
                ['ukeban_id' => 'ukeban-2', 'date' => '2019-10-04'],
                ['ukeban_id' => 'ukeban-2', 'date' => '2019-10-05'],

                ['ukeban_id' => 'ukeban-1', 'date' => '2019-10-25'], // nyuin
                ['ukeban_id' => 'ukeban-1', 'date' => '2019-10-26'], // nyuin
                ['ukeban_id' => 'ukeban-1', 'date' => '2019-10-27'],
                ['ukeban_id' => 'ukeban-1', 'date' => '2019-10-28'],
                ['ukeban_id' => 'ukeban-1', 'date' => '2019-10-29'],
            ],
            'bunsho' => [],
        ];

        $result = Smartcare::calcTsuin($shoken);

        $this->assertEquals(0, count($result['other']));
    }
}
