<?php namespace App\Libraries;

class Calendar
{
    public $table_attr = 'id="nenview" class="table table-bordered table-striped"';
    public $month_attr = 'rowspan="2" style="width:10%; text-align:center; vertical-align:middle;"';
    public $count_attr = 'rowspan="2" style="width:10%; text-align:center; vertical-align:middle;"';
    public $day_attr = 'style="text-align:center; padding:3px; cursor:pointer;" onclick="createmodal($(this).attr(\'id\').replace(\'day-\', \'\'))"';
    public $non_attr = '';

    public function render($start, $end) {
        $start = str_replace('-', '/', $start);
        list($year, $month) = explode('/', $start);
        list($end_year, $end_month) = explode('/', $end);

        echo $this->header();

        $prev = false;
        while ($year < $end_year or $month <= $end_month) {
            $year = date('Y', mktime(0, 0, 0, $month, 1, $year));
            $change_year = $prev != $year;
            if ($month % 12 == 1) $month = 1;
            echo $this->line($year, $month, $change_year);
            $prev = $year;
            $month ++;
        }

        echo $this->footer();
    }

    public function header() {
        return "<table {$this->table_attr}>";
    }

    public function line($year, $month, $print_year) {
        $year = (int) $year;
        $month = (int) $month;
        $data = "<tr id='nen-{$year}-{$month}-1'>";
        $data .= "<td {$this->month_attr}><a href=\"javascript:month('{$year}-".sprintf('%02d', $month)."')\">";
        if ($print_year) {
            $data .= date('Y', mktime(0, 0, 0, $month, 1, $year)) . '年<br />';
        }
        $data .= date('n', mktime(0, 0, 0, $month, 1, $year)) . '月';
        $data .= '</a></td>';

        for ($i=1; $i<=16; $i++) {
            $zeroi = sprintf('%02d', $i);
            $data .= "<td id='day-{$year}-{$month}-{$zeroi}' {$this->day_attr}>{$i}</td>";
        }

        $data .= "<td {$this->count_attr}><span id='sum-{$year}-{$month}' class='badge no-data'>0</span></td>";
        $data .= "</tr><tr id='nen-{$year}-{$month}-2'>";

        for (; date('d', mktime(0, 0, 0, $month, $i, $year)) > 16; $i++) {
            $data .= "<td id='day-{$year}-{$month}-{$i}' {$this->day_attr}>{$i}</td>";
        }

        for (; $i<=32; $i++) {
            $data .= "<td {$this->non_attr}></td>";
        }

        $data .= '</tr>';

        return $data;
    }

    public function footer() {
        return "</table>";
    }
}
