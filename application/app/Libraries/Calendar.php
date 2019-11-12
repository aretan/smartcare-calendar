<?php namespace App\Libraries;

class Calendar
{
    public $table_attr = 'class="table table-bordered table-striped"';
    public $month_attr = 'rowspan="2" style="width:10%; text-align:center; vertical-align:middle;"';
    public $count_attr = 'rowspan="2" style="width:10%; text-align:center; vertical-align:middle;"';
    public $day_attr = 'style="text-align:center;"';
    public $non_attr = 'style="background-color:black;"';

    public function render($year, $month, $end_year, $end_month) {
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
        $data = '<tr>';
        $data .= "<td {$this->month_attr}>";
        if ($print_year) {
            $data .= date('Y', mktime(0, 0, 0, $month, 1, $year)) . '年<br />';
        }
        $data .= date('n', mktime(0, 0, 0, $month, 1, $year)) . '月';
        $data .= '</td>';

        for ($i=1; $i<=16; $i++) {
            $data .= "<td {$this->day_attr}>{$i}</td>";
        }

        $data .= "<td {$this->count_attr}>10</td>";
        $data .= '</tr><tr>';

        for (; date('d', mktime(0, 0, 0, $month, $i, $year)) > 16; $i++) {
            $data .= "<td {$this->day_attr}>{$i}</td>";
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
