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

        $nyuin['warrantyStart'] = date('Y/m/d', $ref - 60 * 60 * 24 * 60);
        $nyuin['warrantyEnd'] = date('Y/m/d', $ref + 60 * 60 * 24 * 120);
        $nyuin['warrantyMax'] = '30';

        return $nyuin;
    }

    /**
     * 手術の保証期間と保証回数を埋める
     */
    public static function addShujutsuWarranty($shujutsu)
    {
        $ref = strtotime($shujutsu['date']);

        $shujutsu['warrantyStart'] = date('Y/m/d', $ref + 60 * 60 * 24 * 1);
        $shujutsu['warrantyEnd'] = date('Y/m/d', $ref + 60 * 60 * 24 * 120);
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
        if (count($nyuinList) < 2) {
            return $nyuinList;
        }

        for ($i=0; isset($nyuinList[$i+1]); $i++) {
            if ($nyuinList[$i+1]['warrantyStart'] <= $nyuinList[$i]['warrantyEnd'] &&
                $nyuinList[$i]['warrantyStart'] <= $nyuinList[$i+1]['warrantyEnd']) {
                $nyuinList[$i]['warrantyEnd'] = $nyuinList[$i+1]['warrantyEnd'];
                $nyuinList[$i+1]['warrantyMax'] = 0;
            }
        }

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
    public static function tsuinResult($tsuinList, $nyuinList, $shujutsuList)
    {
        $result = [];
        $nyuinList = self::conbineNyuin($nyuinList);

        $warrantyList = [];

        foreach ($nyuinList as $j => $nyuin) {
            $warrantyList[] = [
                'type' => 'nyuin',
                'date' => $nyuin['start'],
                'tsuin' => [],
                'warrantyStart' => $nyuin['warrantyStart'],
                'warrantyEnd' => $nyuin['warrantyEnd'],
                'warrantyMax' => $nyuin['warrantyMax'],
            ];
        }
        foreach ($shujutsuList as $i => $shujutsu) {
            $warrantyList[] = [
                'type' => 'shujutsu',
                'date' => $shujutsu['date'],
                'tsuin' => [],
                'warrantyStart' => $shujutsu['warrantyStart'],
                'warrantyEnd' => $shujutsu['warrantyEnd'],
                'warrantyMax' => $shujutsu['warrantyMax'],
            ];
        }

        if (empty($warrantyList)) return $warrantyList;

        // 開始日が早い順で並べる
        foreach ($warrantyList as $key => $value) {
            $sort[$key] = $value['warrantyStart'];
        }
        array_multisort($sort, SORT_ASC, $warrantyList);

        $other = [];
        foreach ($tsuinList as $tsuin) {
            // 適用可能な入院手術をリストアップ
            $activeWarranty = [];
            foreach ($warrantyList as $key => $warranty) {
                if ($warranty['warrantyStart'] <= $tsuin['date'] &&
                    $tsuin['date'] <= $warranty['warrantyEnd'] &&
                    $warranty['warrantyMax'] != 0) {
                    $activeWarranty[$key] = $warranty;
                }
            }

            // 適用不可能な場合
            if (empty($activeWarranty)) {
                $other[] = $tsuin['date'];
                continue;
            }

            // うち終了日が一番近いのを見つける
            $minEnd = null;
            foreach ($activeWarranty as $key => $warranty) {
                if (is_null($minEnd)) {
                    $minEnd = $key;
                    continue;
                }
                if ($activeWarranty[$minEnd] > $warranty['warrantyEnd']) {
                    $minEnd = $key;
                }
            }

            // カウント
            $warrantyList[$minEnd]['warrantyMax']--;
            $warrantyList[$minEnd]['tsuin'][] = $tsuin['date'];
        }

        $warrantyList[] = [
            'type'  => 'other',
            'tsuin' => $other,
        ];

        return $warrantyList;
    }

    public static function toJsonEvents($data, $filter, $start='start', $end='end')
    {
        $events = [];

        foreach ($data as $line) {
            foreach ($filter as $key => $value) {
                if (!empty($value) && $line[$key] != $value) {
                    continue 2;
                }
            }

            $event = [];
            $event['start'] = isset($line[$start]) ? $line[$start] : $line['date'];
            $event['end'] = isset($line[$end]) ? $line[$end] : $line['date'];
            // Note: This value is exclusive. For example, if you have an all-day event that has an end of 2018-09-03, then it will span through 2018-09-02 and end before the start of 2018-09-03.
            $event['end'] = date('Y-m-d', strtotime($event['end']) + 60 * 60 * 24);
            if (isset($line['warrantyMax']) && $line['warrantyMax'] == 0) {
                $event['color'] = '#aaaaff';
                $event['description'] = '同一初回';
            }
            $event['title'] = $line['ukeban_id'];

            $events[] = $event;
        }
        return json_encode($events);
    }
}
