<?php namespace App\Libraries;

class Smartcare
{
    /**
     * 入院の保証期間と保証回数を埋める
     */
    public function addNyuinWarranty($nyuin)
    {
        $ref = strtotime($nyuin['start']);

        $nyuin['warrantyStart'] = date('Y/m/d' $ref - 60 * 60 * 24 * 120);
        $nyuin['warrantyStart'] = date('Y/m/d' $ref + 60 * 60 * 24 * 120);
        $nyuin['warrantyMax'] = '30';

        return $nyuin;
    }

    /**
     * 手術の保証期間と保証回数を埋める
     */
    public function addShujutsuWarranty($nyuin)
    {
        $ref = strtotime($nyuin['date']);

        $nyuin['warrantyStart'] = date('Y/m/d' $ref - 60 * 60 * 24 * 120);
        $nyuin['warrantyStart'] = date('Y/m/d' $ref + 60 * 60 * 24 * 120);
        $nyuin['warrantyMax'] = '30';

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
