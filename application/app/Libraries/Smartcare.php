<?php namespace App\Libraries;

class Smartcare
{
    /**
     * 入院の保証期間と保証回数を埋める
     */
    public static function addNyuinWarranty($nyuin)
    {
        $start = strtotime($nyuin['start']);
        $end = strtotime($nyuin['end']);

        $nyuin['warrantyStart'] = date('Y/m/d', $start - 60 * 60 * 24 * 60);
        $nyuin['warrantyEnd'] = date('Y/m/d', $end + 60 * 60 * 24 * 120);
        $nyuin['warrantyMax'] = 30;

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
        $shujutsu['warrantyMax'] = 30;

        return $shujutsu;
    }

    /**
     * 先行する入院の補償期間と後続する入院の補償期間が重複しているとき後続する入院を同一初回とする
     *
     * @param array $nyuinList
     * @param bool  $remove
     *
     * @return array $nyuinList
     */
    public static function conbineNyuin($nyuinList, $remove = false)
    {
        if (count($nyuinList) <= 1) {
            return $nyuinList;
        }

        $sort = [];
        foreach ($nyuinList as $key => $value) {
            $sort[$key] = $value['start'];
        }
        array_multisort($sort, SORT_ASC, $nyuinList);

        foreach ($nyuinList as $key => $nyuin) {
            if (!isset($nyuin['warranty'])) {
                $nyuinList[$key]['warranty'] = [];
            }
        }

        $removes = [];
        for ($i=0; isset($nyuinList[$i+1]); $i++) {
            $j = 0;
            while ($nyuinList[$i+$j]['warrantyMax'] == 0) {
                $j --;
            }

            if ($nyuinList[$i+1]['warrantyStart'] <= $nyuinList[$i+$j]['warrantyEnd'] &&
                $nyuinList[$i+$j]['warrantyStart'] <= $nyuinList[$i+1]['warrantyEnd']) {

                $nyuinList[$i+$j]['newWarrantyEnd'] = $nyuinList[$i+1]['warrantyEnd'];
                $nyuinList[$i+1]['warrantyMax'] = 0;

                if ($remove) {
                    $removes[] = $i+1;
                }
            }
        }

        foreach ($removes as $remove) {
            unset($nyuinList[$remove]);
        }

        foreach ($nyuinList as $key => $nyuin) {
            if (isset($nyuin['newWarrantyEnd'])) {
                $nyuinList[$key]['warrantyEnd'] = $nyuin['newWarrantyEnd'];
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

        $tsuinList = $otherList = $nyuinList = $shujutsuList = $banList = [];
        foreach ($shoken['ukeban'] as $key => $ukeban) {
            $otherList = array_merge($otherList, $ukeban['tsuin']);
            $nyuinList = array_merge($nyuinList, $ukeban['nyuin']);
            $conbinedNyuinList = self::conbineNyuin($nyuinList);
            $shujutsuList = array_merge($shujutsuList, $ukeban['shujutsu']);

            // 入院と手術を合体
            $warrantyList = $excludeList = [];
            foreach ($conbinedNyuinList as $nyuin) {
                $warrantyList[] = [
                    'type' => 'nyuin',
                    'date' => $nyuin['start'],
                    'already' => [],
                    'warranty' => [],
                    'warrantyStart' => $nyuin['warrantyStart'],
                    'warrantyEnd' => $nyuin['warrantyEnd'],
                    'warrantyMax' => $nyuin['warrantyMax'],
                ];

                while ($nyuin['start'] <= $nyuin['end']) {
                    $excludeList[$nyuin['start']] = true;
                    list($year, $month, $day) = explode('-', $nyuin['start']);
                    $nyuin['start'] = date('Y-m-d', mktime(0, 0, 0, $month, $day+1, $year));
                }
            }
            foreach ($shujutsuList as $shujutsu) {
                $warrantyList[] = [
                    'type' => 'shujutsu',
                    'date' => $shujutsu['date'],
                    'already' => [],
                    'warranty' => [],
                    'warrantyStart' => $shujutsu['warrantyStart'],
                    'warrantyEnd' => $shujutsu['warrantyEnd'],
                    'warrantyMax' => $shujutsu['warrantyMax'],
                ];

                $excludeList[$shujutsu['date']] = true;
            }

            if (!count($warrantyList)) {
                continue;
            }

            $sort = [];
            foreach ($warrantyList as $i => $value) {
                $sort[$i] = $value['warrantyStart'];
            }
            array_multisort($sort, SORT_ASC, $warrantyList);

            // 縦が通院で横が補償の表にする
            // 1000000000 = 補償範囲外の通院、又は入院中か手術日の通院
            // YYYYMMDD0 = 前受番で支払えなかった通院、又は新しい通院
            // YYYYMMDD = 支払済み通院
            $matrix = [];

            // 支払済み通院
            foreach ($tsuinList as $i => $tsuin) {
                foreach ($warrantyList as $warranty) {
                    if ($warranty['warrantyStart'] <= $tsuin['date'] &&
                        $tsuin['date'] <= $warranty['warrantyEnd']) {
                        while ($warranty['warrantyMax'] --) {
                            $matrix[$i][] = isset($excludeList[$tsuin['date']]) ?
                                          1000000000 :
                                          (int) str_replace('-', '', $tsuin['date']);
                        }
                    } else {
                        while ($warranty['warrantyMax'] --) {
                            $matrix[$i][] = 1000000000;
                        }
                    }
                }
            }

            // 新しい通院
            $base = count($matrix);
            foreach ($otherList as $i => $tsuin) {
                foreach ($warrantyList as $warranty) {
                    if ($warranty['warrantyStart'] <= $tsuin['date'] &&
                        $tsuin['date'] <= $warranty['warrantyEnd']) {
                        while ($warranty['warrantyMax'] --) {
                            $matrix[$i+$base][] = isset($excludeList[$tsuin['date']]) ?
                                                1000000000 :
                                                (int) (str_replace('-', '', $tsuin['date']) . '0');
                        }
                    } else {
                        while ($warranty['warrantyMax'] --) {
                            $matrix[$i+$base][] = 1000000000;
                        }
                    }
                }
            }

            if (!count($matrix)) {
                continue;
            }

            // Pythonで計算する
            $body = json_encode($matrix);
            $context = [
                'http' => [
                    'method'  => 'POST',
                    'header'  => "Content-type: application/json\r\nContent-Length: " . strlen($body),
                    'content' => $body,
                ]
            ];
            $result = file_get_contents(getenv('hungarian.url'), false, stream_context_create($context));
            $result = json_decode($result, true);
            foreach ($result as $data) {
                $allocation[$data[0]] = $data[1];
            }

            /* Show Matrix & Result
            echo "<table>";
            foreach ($matrix as $i => $row) {
                echo "<tr>";
                foreach ($row as $j => $col) {
                    if ($allocation[$i] == $j) {
                        echo "<td style='text-align:center; background-color:red;'>$col</td>";
                    } else {
                        echo "<td style='text-align:center;'>$col</td>";
                    }
                }
                echo "</tr>";
            }
            echo "</table>";
            echo "----- ----- ----- -----<br>";
            // */

            // 結果を取り出す
            $unsets = $tsuins = [];
            foreach ($allocation as $tsuin_key => $warranty_key) {
                // 100000000の結果を省く
                if ($matrix[$tsuin_key][$warranty_key] == 1000000000) {
                    if (isset($tsuinList[$tsuin_key])) {
                        $banList[] = $tsuinList[$tsuin_key];
                        unset($tsuinList[$tsuin_key]);
                    }
                    continue;
                }

                foreach ($warrantyList as $j => $warranty) {
                    $warranty_key -= $warranty['warrantyMax'];
                    if ($warranty_key >= 0) {
                        continue;
                    }

                    if (isset($tsuinList[$tsuin_key])) {
                        $warrantyList[$j]['already'][] = $tsuinList[$tsuin_key];
                    } elseif (isset($otherList[$tsuin_key-$base])) {
                        $warrantyList[$j]['warranty'][] = $otherList[$tsuin_key-$base];
                        $tsuins[] = $otherList[$tsuin_key-$base];
                        $unsets[] = $tsuin_key-$base;
                    }
                    break;
                }
            }

            $tsuinList = array_merge($tsuins, $tsuinList);

            // 1095日制限
            if (count($tsuinList) > 1095) {
                $sort = [];
                foreach ($tsuinList as $i => $value) {
                    $sort[$i] = $value['date'];
                }
                array_multisort($sort, SORT_ASC, $tsuinList);

                // 新しいの捨てる
                while (count($tsuinList) > 1095) {
                    $otherList[] = array_pop($tsuinList);
                }
            }

            foreach ($unsets as $unset) {
                unset($otherList[$unset]);
            }

            foreach ($warrantyList as $i => $warranty) {
                $sort = [];
                foreach ($warranty['warranty'] as $j => $value) {
                    $sort[$j] = $value['date'];
                }
                array_multisort($sort, SORT_ASC, $warrantyList[$i]['warranty']);
            }

            $shoken['ukeban'][$key]['warranty'] = array_merge(
                $warrantyList,
                [
                    'other' => [
                        'type'     => 'other',
                        'warranty' => $otherList,
                    ],
                    'ban' => [
                        'type'     => 'ban',
                        'warranty' => $banList,
                    ],
                ]);
        }

        if ($tsuinList) {
            $sort = [];
            foreach ($tsuinList as $key => $value) {
                $sort[$key] = $value['date'];
            }
            array_multisort($sort, SORT_ASC, $tsuinList);
        }
        $shoken['warranty'] = $tsuinList;

        $otherList = array_merge($otherList, $banList);
        if ($otherList) {
            $sort = [];
            foreach ($otherList as $key => $value) {
                $sort[$key] = $value['date'];
            }
            array_multisort($sort, SORT_ASC, $otherList);
        }
        $shoken['other'] = $otherList;

        $shoken['exclude'] = $excludeList;

        return $shoken;
    }

    /**
     * fullcalendarのevent jsonにする
     * Note: This value is exclusive. For example, if you have an all-day event that has an end of 2018-09-03, then it will span through 2018-09-02 and end before the start of 2018-09-03.
     */
    public static function toJsonEvents($data, $filter, $start, $end, $exclude=[])
    {
        $events = [];

        foreach ($data as $line) {
            foreach ($filter as $key => $value) {
                if (!empty($value) && $line[$key] != $value) {
                    continue 2;
                }
            }

            if (count($exclude)) {
                $event = [];
                $event['start'] = $line[$start];
                $event['end'] = $line[$end];
                $event['end'] = date('Y-m-d', strtotime($event['end']) + 60 * 60 * 24);
                foreach ($exclude as $date => $bool) {
                    if ($event['start'] <= $date && $date <= $event['end']) {
                        $buff = $event['end'];
                        $event['end'] = $date;
                        if ($event['start'] != $event['end']) {
                            $events[] = $event;
                        }

                        $event['start'] = $date;
                        $event['start'] = date('Y-m-d', strtotime($event['start']) + 60 * 60 * 24);
                        $event['end'] = $buff;
                    }
                }
                $events[] = $event;
            } else {
                $event = [];
                $event['event_id'] = $line['id'];
                $event['start'] = $line[$start];
                $event['end'] = $line[$end];
                $event['end'] = date('Y-m-d', strtotime($event['end']) + 60 * 60 * 24);
                if (isset($line['warrantyMax']) && $line['warrantyMax'] == 0) {
                    $event['color'] = '#aaaaff';
                    $event['description'] = '同一初回';
                }
                $event['title'] = $line['ukeban_id'];
                $events[] = $event;
            }
        }
        return json_encode($events);
    }
}
