<?php namespace App\Libraries;

class Smartcare
{
    /**
     * 入院の保証期間と保証回数を埋める
     */
    public function addWarranty($nyuin)
    {
        $nyuin['warrantyStart'] = '2019/11/10';
        $nyuin['warrantyEnd'] = '2019/11/20';
        $nyuin['warrantyMax'] = '5';

        return $nyuin;
    }

    /**
     * 入院と入院が近いとき、一つの入院とみなされ、同一初回となる
     *
     * @param array $nyuinList
     *
     * @return array $nyuinList
     */
    public function conbineNyuin($nyuinList)
    {
        $nyuinList = [
            new \App\Entities\Nyuin(),
            new \App\Entities\Nyuin(['warrantyMax' => 0]),
            new \App\Entities\Nyuin(),
        ];
        return $nyuinList;
    }

    /**
     * 通院がどの入院/手術に属するか、振り分けた結果を返す
     *
     * @param array $tsuinList
     * @param array $nyuinList
     * @param array $shujutsuList
     *
     * @return array $result
     */
    public function separateTsuin($tsuinList, $nyuinList, $shujutsuList)
    {
        $result = [
            'nyuin' => [
                '123' => ['123', '124', '125'],
                '124' => ['126', '127', '128'],
            ],
            'shujutsu' => [
                '123' => ['129', '130', '131'],
                '124' => ['132', '133', '134'],
            ],
            'others' => [
                '135',
                '136',
            ],
        ];
        return $result;
    }
}
