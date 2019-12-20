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
        foreach ($nyuinList as $key => $nyuin) {
            $nyuinList[$key]['date'] = $nyuinList[$key]['start'];
            if (!isset($nyuin['warranty'])) {
                $nyuinList[$key]['warranty'] = [];
            }
        }

        if (count($nyuinList) <= 1) {
            return $nyuinList;
        }

        $sort = [];
        foreach ($nyuinList as $key => $value) {
            $sort[$key] = $value['start'];
        }
        array_multisort($sort, SORT_ASC, $nyuinList);

        $removes = [];
        for ($i=0; isset($nyuinList[$i+1]); $i++) {
            $j = 0;
            while ($nyuinList[$i+$j]['warrantyMax'] == 0) {
                $j --;
            }

            if ($nyuinList[$i+1]['warrantyStart'] <= $nyuinList[$i+$j]['warrantyEnd'] &&
                $nyuinList[$i+$j]['warrantyStart'] <= $nyuinList[$i+1]['warrantyEnd']) {

                $nyuinList[$i+$j]['newWarrantyEnd'] = $nyuinList[$i+1]['warrantyEnd'];
                $nyuinList[$i+1]['date'] = $nyuinList[$i+$j]['start'];
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

        $tsuinList = $otherList = $bunshoList = $nyuinList = $shujutsuList = $banList = $excludeList = [];
        foreach ($shoken['ukeban'] as $key => $ukeban) {
            $otherList = array_merge($otherList, $ukeban['tsuin']);
            $nyuinList = array_merge($nyuinList, $ukeban['nyuin']);
            $bunshoList = array_merge($bunshoList, $ukeban['bunsho']);
            $conbinedNyuinList = self::conbineNyuin($nyuinList);
            $shujutsuList = array_merge($shujutsuList, $ukeban['shujutsu']);

            // 入院と手術を合体
            $warrantyList = $excludeList = [];
            foreach ($conbinedNyuinList as $nyuin) {
                $warrantyList[] = [
                    'id' => $nyuin['id'],
                    'ukeban_id' => $nyuin['ukeban_id'],
                    'type' => 'nyuin',
                    'date' => $nyuin['date'],
                    'start' => $nyuin['start'],
                    'end' => $nyuin['end'],
                    'already' => [],
                    'warranty' => [],
                    'warrantyStart' => $nyuin['warrantyStart'],
                    'warrantyEnd' => $nyuin['warrantyEnd'],
                    'warrantyMax' => $nyuin['warrantyMax'],
                ];

                // 入院中は絶対払わない
                while ($nyuin['start'] <= $nyuin['end']) {
                    $excludeList[$nyuin['start']] = true;
                    list($year, $month, $day) = explode('-', $nyuin['start']);
                    $nyuin['start'] = date('Y-m-d', mktime(0, 0, 0, $month, $day+1, $year));
                }
            }
            // 手術の補償は入院よりも優先度低い
            if ($shujutsuList) {
                $sort = [];
                foreach ($shujutsuList as $i => $value) {
                    $sort[$i] = $value['date'];
                }
                array_multisort($sort, SORT_ASC, $shujutsuList);
            }
            foreach ($shujutsuList as $shujutsu) {
                $warrantyList[] = [
                    'id' => $shujutsu['id'],
                    'ukeban_id' => $shujutsu['ukeban_id'],
                    'type' => 'shujutsu',
                    'date' => $shujutsu['date'],
                    'already' => [],
                    'warranty' => [],
                    'warrantyStart' => $shujutsu['warrantyStart'],
                    'warrantyEnd' => $shujutsu['warrantyEnd'],
                    'warrantyMax' => $shujutsu['warrantyMax'],
                ];

                // 手術日は絶対払わない
                $excludeList[$shujutsu['date']] = true;
            }

            if ($excludeList) ksort($excludeList);

            if ($tsuinList) {
                $sort = [];
                foreach ($tsuinList as $i => $value) {
                    $sort[$i] = $value['date'];
                }
                array_multisort($sort, SORT_ASC, $tsuinList);
            }

            if ($otherList) {
                $sort = [];
                foreach ($otherList as $i => $value) {
                    $sort[$i] = $value['date'];
                }
                array_multisort($sort, SORT_ASC, $otherList);
            }

            // 縦が通院で横が補償の表にして ハンガリアンで解く
            // 99999999 = 補償範囲外の通院、又は入院中か手術日の通院
            // XYYMMDD0 = 前受番で支払えなかった通院、又は新しい通院
            // XYYMMDD = 支払済み通院
            // YYMMDD = 支払済み通院、かつ前回の補償と同一な場合
            // 値の小さいものが割り当てられる

            // 優先順位
            // 1. 支払済みの通院 AND 補償した入院・手術の組み合わせ
            // 2. 支払済みの通院 AND 入院前後 X=1
            // 3. 支払済みの通院 AND 手術後 X=2
            // 4. 未払いの通院 AND 入院前後 X=1
            // 5. 未払いの通院 AND 手術後 X=2
            // 条件
            // + 常に入院中と手術日の通院は適用外 $excludeList
            // + 各順位において通院は過去のものから適用 YYMMDD
            $matrix = [];

            $number = [
                'disallow' => 99999999,
                'nyuin'    => 1,
                'shujutsu' => 2,
            ];

            // 支払済み通院
            foreach ($tsuinList as $i => $tsuin) {
                foreach ($warrantyList as $warranty) {
                    if ($warranty['warrantyStart'] <= $tsuin['date'] &&
                        $tsuin['date'] <= $warranty['warrantyEnd']) {
                        while ($warranty['warrantyMax'] --) {
                            if ($tsuin['warranty']['type'] == $warranty['type'] &&
                                $tsuin['warranty']['date'] == $warranty['date']) {
                                $score = substr(str_replace('-', '', $tsuinList[$i]['date']), 2);
                            } else {
                                $score = sprintf(
                                    '%01d%04d',
                                    $number[$warranty['type']],
                                    substr(str_replace('-', '', $tsuinList[$i]['date']), 2)
                                );
                            }
                            $matrix[$i][] = isset($excludeList[$tsuin['date']]) ?
                                          $number['disallow'] :
                                          (int) $score;
                        }
                    } else {
                        while ($warranty['warrantyMax'] --) {
                            $matrix[$i][] = $number['disallow'];
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
                            $score = sprintf(
                                '%01d%04d0',
                                $number[$warranty['type']],
                                substr(str_replace('-', '', $otherList[$i]['date']), 2)
                            );
                            $matrix[$i+$base][] = isset($excludeList[$tsuin['date']]) ?
                                                $number['disallow'] :
                                                (int) $score;
                        }
                    } else {
                        while ($warranty['warrantyMax'] --) {
                            $matrix[$i+$base][] = $number['disallow'];
                        }
                    }
                }
            }

            if ($matrix) {
                // 手元に計算結果置いておく
                $hashkey = hash('sha256', json_encode($matrix));
                $allocation = cache($hashkey);
                if (!$allocation) {
                    // PHPのライブラリだと１００秒以上かかるので、Pythonに投げる
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
                    cache()->save($hashkey, $allocation, -1);
                }
            } else {
                $allocation = [];
            }

            /* Show Matrix & Result
            echo "<style>body{overflow-x:scroll !important}</style>";
            echo "<table>";
            echo "<tr>";
            echo "<th></th>";
            foreach ($warrantyList as $warranty) {
                while ($warranty['warrantyMax'] --) {
                    $date = str_replace('-', '', $warranty['date']);
                    $type = $warranty['type'] == 'nyuin' ? '入' : '手';
                    echo "<th style='text-align:center'>{$type}:{$date}</th>";
                }
            }
            echo "</tr>";
            $total = 0;
            foreach ($matrix as $i => $row) {
                echo "<tr>";
                $gap = $i - $base;
                $date = ($gap >= 0) ?
                      str_replace('-', '', $otherList[$gap]['date']) :
                      str_replace('-', '', $tsuinList[$i]['date']);
                echo "<th style='text-align:center'>{$date}</th>";
                foreach ($row as $j => $col) {
                    if ($col == $number['disallow']) {
                        echo "<td style='text-align:center; background-color:gray;'>$col</td>";
                    } else if (isset($allocation[$i]) && $allocation[$i] == $j) {
                        echo "<td style='text-align:center; background-color:red;'>$col</td>";
                        $total += $col;
                    } else {
                        echo "<td style='text-align:center;'>$col</td>";
                    }
                }
                echo "</tr>";
            }
            echo "</table>";
            echo "<b>Total Cost: {$total}</b><br>";
            echo "----- ----- ----- -----<br>";
            // */

            // 結果を取り出す
            $unsets = $tsuins = $changeList = [];
            foreach ($allocation as $tsuin_key => $warranty_key) {
                // 対象外の結果を省く
                if ($matrix[$tsuin_key][$warranty_key] == $number['disallow']) {
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
                        if ($tsuinList[$tsuin_key]['warranty']['date'] != $warranty['date'] ||
                            $tsuinList[$tsuin_key]['warranty']['type'] != $warranty['type']) {
                            $tsuinList[$tsuin_key]['before'] = $tsuinList[$tsuin_key]['warranty'];
                            $tsuinList[$tsuin_key]['warranty'] = [
                                'type' => $warranty['type'],
                                'date' => $warranty['date'],
                            ];
                            $changeList[] = $tsuinList[$tsuin_key];
                        }
                        $warrantyList[$j]['already'][] = $tsuinList[$tsuin_key];
                    } elseif (isset($otherList[$tsuin_key-$base])) {
                        // 1095日制限
                        if (count($tsuinList) + count($tsuins) - 1095 < 0) {
                            $warrantyList[$j]['warranty'][] = $otherList[$tsuin_key-$base];
                            $otherList[$tsuin_key-$base]['warranty'] = [
                                'type' => $warranty['type'],
                                'date' => $warranty['date'],
                            ];
                            $tsuins[] = $otherList[$tsuin_key-$base];
                            $unsets[] = $tsuin_key-$base;
                        }
                    }
                    break;
                }
            }

            $tsuinList = array_merge($tsuinList, $tsuins);

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

            $shoken['ukeban'][$key]['all']['nyuin'] = $nyuinList;
            $shoken['ukeban'][$key]['all']['shujutsu'] = $shujutsuList;
            $shoken['ukeban'][$key]['all']['bunsho'] = $bunshoList;
            $shoken['ukeban'][$key]['all']['exclude'] = $excludeList;
            $shoken['ukeban'][$key]['change'] = $changeList;
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

        return $shoken;
    }

    public static function toJsonEvents($shoken, $mode, $ukeban_id)
    {
        $exclude = $ukeban = $filter = [];
        if ($mode == 'until') {
            foreach ($shoken['ukeban'] as $ukeban) {
                if ($ukeban['id'] == $ukeban_id) {
                    break;
                }
            }
        } else {
            $ukeban = end($shoken['ukeban']);
            $filter = ['ukeban_id' => $ukeban_id];
        }

        if (!$ukeban) return json_encode([]);

        $exclude = isset($ukeban['all']['exclude']) ?
                 $ukeban['all']['exclude'] :
                 [];

        $events = [
            [
                'id' => 'warranty',
                'events' => self::toCalendarEvents($ukeban['all']['shujutsu'], $filter, 'warrantyStart', 'warrantyEnd', null, $exclude),
                'rendering' => 'background',
            ],
            [
                'id' => 'warranty',
                'events' => self::toCalendarEvents($ukeban['all']['nyuin'], $filter, 'warrantyStart', 'warrantyEnd', null, $exclude),
                'rendering' => 'background',
            ],
            [
                'id' => 'bunsho',
                'events' => self::toCalendarEvents($ukeban['all']['bunsho'], $filter, 'date', 'date', '非該当通院'),
                'color' => "#a0a0a0",
                'description' => '非該当通院',
            ],
        ];

        if (!isset($ukeban['warranty'])) return json_encode($events);

        // まず同一初回をマージする
        $unsets = [];
        foreach ($ukeban['warranty'] as $i => $child) {
            if ($child['type'] == 'nyuin' && !$child['warrantyMax']) {
                $unsets[] = $i;
                foreach ($ukeban['warranty'] as $j => $parent) {
                    if ($parent['type'] == 'nyuin' && $child['date'] == $parent['date']) {
                        $ukeban['warranty'][$j]['child'][] = $child;
                    }
                }
            }
        }
        foreach ($unsets as $unset) {
            unset($ukeban['warranty'][$unset]);
        }

        $colorset = [
            [
                'nyuin' => '#0000ff',
                'tsuin' => '#bbbbff',
            ],
            [
                'nyuin' => '#00cc33',
                'tsuin' => '#aaffaa',
            ],
        ];

        $color = 0;
        foreach ($ukeban['warranty'] as $warranty) {
            if ($warranty['type'] == 'nyuin') {
                $events[] = [
                    'id' => 'nyuin',
                    'events' => self::toCalendarEvents([$warranty], $filter, 'start', 'end', '入院'),
                    'color' => $colorset[$color%2]['nyuin'],
                    'description' => "同一初回[{$warranty['date']}]",
                ];
                if (isset($warranty['child'])) {
                    $events[] = [
                        'id' => 'nyuin',
                        'events' => self::toCalendarEvents($warranty['child'], $filter, 'start', 'end', '入院'),
                        'color' => $colorset[$color%2]['nyuin'],
                        'description' => "同一初回[{$warranty['date']}]",
                    ];
                }
                $events[] = [
                    'id' => 'tsuin',
                    'events' => self::toCalendarEvents($warranty['already'], $filter, 'date', 'date', '通院'),
                    'color' => $colorset[$color%2]['tsuin'],
                    'description' => "通院[入院:{$warranty['date']}]",
                ];
                $events[] = [
                    'id' => 'tsuin',
                    'events' => self::toCalendarEvents($warranty['warranty'], $filter, 'date', 'date', '通院'),
                    'color' => $colorset[$color%2]['tsuin'],
                    'description' => "通院[入院:{$warranty['date']}]",
                ];
                $color ++;
            } elseif ($warranty['type'] == 'shujutsu') {
                $events[] = [
                    'id' => 'shujutsu',
                    'events' => self::toCalendarEvents([$warranty], $filter, 'date', 'date', '手術'),
                    'color' => 'red',
                    'description' => '手術',
                ];
                $events[] = [
                    'id' => 'tsuin',
                    'events' => self::toCalendarEvents($warranty['already'], $filter, 'date', 'date', '通院'),
                    'color' => 'orange',
                    'description' => "通院[手術:{$warranty['date']}]",
                ];
                $events[] = [
                    'id' => 'tsuin',
                    'events' => self::toCalendarEvents($warranty['warranty'], $filter, 'date', 'date', '通院'),
                    'color' => 'orange',
                    'description' => "通院[手術:{$warranty['date']}]",
                ];
            } elseif ($warranty['type'] == 'other' || $warranty['type'] == 'ban') {
                $events[] = [
                    'id' => 'other',
                    'events' => self::toCalendarEvents($warranty['warranty'], $filter, 'date', 'date', '支払えない通院'),
                    'color' => "#ff64c8",
                    'description' => '支払えない通院',
                ];
            }
        }

        return json_encode($events, JSON_PRETTY_PRINT);
    }

    /**
     * fullcalendarのevent jsonにする
     * Note: This value is exclusive. For example, if you have an all-day event that has an end of 2018-09-03, then it will span through 2018-09-02 and end before the start of 2018-09-03.
     */
    public static function toCalendarEvents($data, $filter, $start, $end, $title, $exclude=[])
    {
        $events = [];

        if (!$data) return $events;

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
                $event['start'] = $line[$start];
                $event['end'] = $line[$end];
                $event['end'] = date('Y-m-d', strtotime($event['end']) + 60 * 60 * 24);
                $event['title'] = $title;
                $event['event_id'] = "{$line['ukeban_id']}/{$line['id']}";
                $events[] = $event;
            }
        }
        return $events;
    }
}
