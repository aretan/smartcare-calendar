<?php namespace App\Libraries;

class Smartcare
{
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
     * @param bool  $remove
     *
     * @return array $nyuinList
     */
    public static function conbineNyuin($nyuinList, $remove = false)
    {
        if (count($nyuinList) < 2) {
            return $nyuinList;
        }

        foreach ($nyuinList as $key => $value) {
            $sort[$key] = $value['start'];
        }
        array_multisort($sort, SORT_ASC, $nyuinList);

        foreach ($nyuinList as $key => $nyuin) {
            if (!isset($nyuin['warranty'])) {
                $nyuinList[$key]['warranty'] = [];
            }
        }

        for ($i=0; isset($nyuinList[$i+1]); $i++) {
            if ($nyuinList[$i+1]['warrantyStart'] <= $nyuinList[$i]['warrantyEnd'] &&
                $nyuinList[$i]['warrantyStart'] <= $nyuinList[$i+1]['warrantyEnd']) {
                $nyuinList[$i]['warrantyEnd'] = $nyuinList[$i+1]['warrantyEnd'];
                $nyuinList[$i]['warrantyMax'] += $nyuinList[$i+1]['warrantyMax'] - 30;
                $nyuinList[$i+1]['warrantyMax'] = 0;
                $nyuinList[$i]['warranty'] = array_merge($nyuinList[$i]['warranty'], $nyuinList[$i+1]['warranty']);
                if ($remove) {
                    unset($nyuinList[$i+1]);
                }
            }
        }

        return $nyuinList;
    }

    /**
     * 通院がどの入院/手術に属するか、振り分けた結果を返す
     *
     * @param array $shoken
     *
     * @return array $result
     */
    public static function calcTsuin($shoken)
    {
        $ref = [];
        foreach ($shoken['nyuin'] as $key => $nyuin) {
            $ref[$nyuin['ukeban_id']]['nyuin'][] = $shoken['nyuin'][$key];
        }
        foreach ($shoken['shujutsu'] as $key => $shujutsu) {
            $ref[$shujutsu['ukeban_id']]['shujutsu'][] = $shoken['shujutsu'][$key];
        }
        foreach ($shoken['tsuin'] as $key => $tsuin) {
            $ref[$tsuin['ukeban_id']]['tsuin'][] = $shoken['tsuin'][$key];
        }
        foreach ($shoken['bunsho'] as $key => $bunsho) {
            $ref[$bunsho['ukeban_id']]['bunsho'][] = $shoken['bunsho'][$key];
        }

        // 参照で受番にMappingする
        foreach ($shoken['ukeban'] as $key => $ukeban) {
            $shoken['ukeban'][$key]['nyuin'] = isset($ref[$ukeban['id']]['nyuin']) ? $ref[$ukeban['id']]['nyuin'] : [];
            $shoken['ukeban'][$key]['shujutsu'] = isset($ref[$ukeban['id']]['shujutsu']) ? $ref[$ukeban['id']]['shujutsu'] : [];
            $shoken['ukeban'][$key]['tsuin'] = isset($ref[$ukeban['id']]['tsuin']) ? $ref[$ukeban['id']]['tsuin'] : [];
            $shoken['ukeban'][$key]['bunsho'] = isset($ref[$ukeban['id']]['bunsho']) ? $ref[$ukeban['id']]['bunsho'] : [];
        }

        $tsuinList = $otherList = $nyuinList = $shujutsuList = [];
        foreach ($shoken['ukeban'] as $key => $ukeban) {
            $otherList = array_merge($otherList, $ukeban['tsuin']);
            $nyuinList = array_merge($nyuinList, $ukeban['nyuin']);
            $nyuinList = self::conbineNyuin($nyuinList);
            $shujutsuList = array_merge($shujutsuList, $ukeban['shujutsu']);

            // 入院と手術を合体
            $warrantyList = [];
            foreach ($nyuinList as $nyuin) {
                $warrantyList[] = [
                    'type' => 'nyuin',
                    'date' => $nyuin['start'],
                    'warranty' => [],
                    'warrantyStart' => $nyuin['warrantyStart'],
                    'warrantyEnd' => $nyuin['warrantyEnd'],
                    'warrantyMax' => $nyuin['warrantyMax'],
                ];
            }
            foreach ($shujutsuList as $shujutsu) {
                $warrantyList[] = [
                    'type' => 'shujutsu',
                    'date' => $shujutsu['date'],
                    'warranty' => [],
                    'warrantyStart' => $shujutsu['warrantyStart'],
                    'warrantyEnd' => $shujutsu['warrantyEnd'],
                    'warrantyMax' => $shujutsu['warrantyMax'],
                ];
            }

            // 開始日が早い順で並べる
            if (!empty($warrantyList)) {
                foreach ($warrantyList as $i => $value) {
                    $sort[$i] = $value['warrantyStart'];
                }
                array_multisort($sort, SORT_ASC, $warrantyList);
            }

            // 既に支払済みの通院をつけかえる
            foreach ($tsuinList as $i => $tsuin) {
                $j = self::selectWarranty($warrantyList, $tsuin);

                $warrantyList[$j]['warrantyMax'] --;
            }

            // 新しい通院を分類
            foreach ($otherList as $i => $tsuin) {
                $j = self::selectWarranty($warrantyList, $tsuin);

                // 保障できない
                if ($j === false) {
                    continue;
                }

                $warrantyList[$j]['warrantyMax'] --;
                $warrantyList[$j]['warranty'][] = $tsuin;
                $tsuinList[] = $tsuin;
                unset($otherList[$i]);
            }

            $shoken['ukeban'][$key]['warranty'] = array_merge(
                $warrantyList,
                [
                    'other' => [
                        'type'     => 'other',
                        'warranty' => $otherList,
                    ]
                ]);
        }

        $shoken['warranty'] = $tsuinList;
        $shoken['other'] = $otherList;

        return $shoken;

    }

    public static function selectWarranty($warrantyList, $tsuin) {
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
            return false;
        }

        // うち終了日が一番近いのを見つける
        $min = null;
        foreach ($activeWarranty as $key => $warranty) {
            if (is_null($min)) {
                $min = $key;
                continue;
            }
            if ($warranty['warrantyEnd'] > $activeWarranty[$min]) {
                $min = $key;
            }
        }

        return $min;
    }

    /**
     * fullcalendarのevent jsonにする
     */
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
            $event['event_id'] = $line['id'];
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
