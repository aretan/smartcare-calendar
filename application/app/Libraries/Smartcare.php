<?php namespace App\Libraries;

class Smartcare
{
    public static function groupByUkebanId($array)
    {
        $result = [];
        foreach ($array as $line) {
            $result[$line['ukeban_id']][] = $line;
        }
        return $result;
    }

    /**
     * 入院の保証期間と保証回数を埋める
     */
    public static function addNyuinWarranty($nyuin)
    {
        $ref = strtotime($nyuin['start']);

        $nyuin['warrantyStart'] = date('Y/m/d', $ref - 60 * 60 * 24 * 120);
        $nyuin['warrantyStart'] = date('Y/m/d', $ref + 60 * 60 * 24 * 120);
        $nyuin['warrantyMax'] = '30';

        return $nyuin;
    }

    /**
     * 手術の保証期間と保証回数を埋める
     */
    public static function addShujutsuWarranty($shujutsu)
    {
        $ref = strtotime($shujutsu['date']);

        $shujutsu['warrantyStart'] = date('Y/m/d', $ref - 60 * 60 * 24 * 120);
        $shujutsu['warrantyStart'] = date('Y/m/d', $ref + 60 * 60 * 24 * 120);
        $shujutsu['warrantyMax'] = '30';

        return $shujutsu;
    }

    /**
     * 入院と入院が近いとき、一つの入院とみなされ、同一初回となる
     *
     * @param array $nyuinList
     *
     * @return array $nyuinList
     */
    public static function conbineNyuin($nyuinList)
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
    public static function separateTsuin($tsuinList, $nyuinList, $shujutsuList)
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

    public static function toJsonEvents($data)
    {
        $events = [];

        foreach ($data as $line) {
            $event = [];
            $event['start'] = isset($line['start']) ? $line['start'] : $line['date'];
            $event['end'] = isset($line['end']) ? $line['end'] : $line['date'];
            // Note: This value is exclusive. For example, if you have an all-day event that has an end of 2018-09-03, then it will span through 2018-09-02 and end before the start of 2018-09-03.
            $event['end'] = date('Y-m-d', strtotime($event['end']) + 60 * 60 * 24);
            $event['title'] = $line['ukeban_id'];
            $event['description'] = 'てすと';
            $events[] = $event;
        }
        return json_encode($events);
    }
}
