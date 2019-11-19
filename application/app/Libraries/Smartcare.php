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
            if (strtotime($nyuinList[$i+1]['warrantyStart']) <= strtotime($nyuinList[$i]['warrantyEnd']) &&
                strtotime($nyuinList[$i]['warrantyStart']) <= strtotime($nyuinList[$i+1]['warrantyEnd'])) {
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
        $nyuinList = self::conbineNyuin($nyuinList);

        $warrantyList = [];
        foreach ($shujutsuList as $i => $shujutsu) {
            foreach ($nyuinList as $j => $nyuin) {
                if (strtotime($shujutsu['warrantyStart']) >= strtotime($nyuin['warrantyStart'])) {
                    $warrantyList[] = [
                        'key' => '<i class="fa fa-hotel margin-r-5"></i>入院：'. "{$nyuin['start']} ({$nyuin['warrantyMax']})",
                        'warrantyStart' => $nyuin['warrantyStart'],
                        'warrantyEnd' => $nyuin['warrantyEnd'],
                        'warrantyMax' => $nyuin['warrantyMax'],
                    ];
                    unset($nyuinList[$j]);
                }
            }
            $warrantyList[] = [
                'key' => '<i class="fa fa-calendar-times-o margin-r-5"></i>手術：'. "{$shujutsu['date']} ({$shujutsu['warrantyMax']})",
                'warrantyStart' => $shujutsu['warrantyStart'],
                'warrantyEnd' => $shujutsu['warrantyEnd'],
                'warrantyMax' => $shujutsu['warrantyMax'],
            ];
        }
        foreach ($nyuinList as $j => $nyuin) {
            $warrantyList[] = [
                'key' => '<i class="fa fa-hotel margin-r-5"></i>入院：'."{$nyuin['start']} ({$nyuin['warrantyMax']})",
                'warrantyStart' => $nyuin['warrantyStart'],
                'warrantyEnd' => $nyuin['warrantyEnd'],
                'warrantyMax' => $nyuin['warrantyMax'],
            ];
        }

        foreach ($warrantyList as $i => $warranty) {
            $result[$warranty['key']] = [];
            foreach ($tsuinList as $j => $tsuin) {
                if ($warrantyList[$i]['warrantyMax'] == 0) {
                    continue 2;
                }
                if (strtotime($warranty['warrantyStart']) <= strtotime($tsuin['date']) &&
                    strtotime($tsuin['date']) <= strtotime($warranty['warrantyEnd'])) {
                    $result[$warranty['key']][] = $tsuin['date'];
                    $warrantyList[$i]['warrantyMax'] --;
                    unset($tsuinList[$j]);
                }
            }
        }

        foreach ($tsuinList as $i => $tsuin) {
            $result['<i class="fa fa-times-circle margin-r-5"></i>保障外'][] = $tsuin['date'];
        }

        return $result;
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
