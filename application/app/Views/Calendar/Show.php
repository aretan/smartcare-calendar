<?= $this->extend('Layouts/Main') ?> <!-- -*- mode:mhtml -*- -->
<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?=$shoken['id'] ?>
    <small><?=$shoken['name'] ?></small>
  </h1>

  <div class="breadcrumb" style="padding:0; top:10px; right:15px;">
    <button type="submit" class="btn btn-primary">Submit</button>
  </div>
</section>

<!-- Main content -->
<section class="content container-fluid">
  <div class="row">
    <div class="col-md-8">
      <div class="box-group" id="accordion">
        <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
        <div class="panel box box-primary">
          <div class="box-header with-border">
            <h4 class="box-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                年間カレンダー
              </a>
            </h4>

            <div class="box-tools pull-right" style="width:250px;">
              <div class="input-group input-group-sm">
                <input type="text" class="form-control" id="monthrange" name="monthrange" value="2019/01 - 2020/01">
                <span class="input-group-btn">
                  <button type="submit" class="btn btn-info btn-flat">反映</button>
                </span>
              </div>
            </div>
          </div>
          <div id="collapseOne" class="panel-collapse collapse in">

            <div class="box-body no-padding">
              <?php (new \App\Libraries\Calendar)->render(2017, 10, 2020, 10); ?>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

        </div>
        <div class="panel box box-danger">
          <div class="box-header with-border">
            <h4 class="box-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                月間カレンダー
              </a>
            </h4>
          </div>
          <div id="collapseTwo" class="panel-collapse collapse">
            <!-- THE CALENDAR -->
            <div id="calendar"></div>
          </div>
        </div>
        <div class="panel box box-success">
          <div class="box-header with-border">
            <h4 class="box-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                コメント
              </a>
            </h4>
          </div>
          <div id="collapseThree" class="panel-collapse collapse">
            <div class="box-body">
              <div class="form-group">
                <textarea class="form-control" rows="3" placeholder="Enter ..."></textarea>
              </div>
              <button type="submit" class="btn btn-info pull-right">Save</button>
            </div>
          </div>
        </div>
      </div>

    </div>
    <!-- /.col -->

    <div class="col-md-4">
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#activity" data-toggle="tab">通院数</a></li>
          <li><a href="#timeline" data-toggle="tab">受付番号</a></li>
          <li><a href="#settings" data-toggle="tab">カレンダー</a></li>
        </ul>
        <div class="tab-content">
          <div class="active tab-pane" id="activity">

            <strong><i class="fa fa-hotel margin-r-5"></i>入院：2018/08/05</strong>
            <p>4日間 (8/12 8/15 8/20 9/10)</p>

            <strong><i class="fa fa-calendar-plus-o margin-r-5"></i>手術：2018/08/05</strong>
            <p>0日間</p>

          </div>
          <!-- /.tab-pane -->
          <div class="tab-pane" id="timeline">
            <!-- The timeline -->
            <ul class="timeline timeline-inverse">

              <!-- timeline time label -->
              <li class="time-label">
                <span class="bg-red">
                  契約日：2018/05/24
                </span>
              </li>
              <!-- /.timeline-label -->
              <!-- timeline item -->
              <li>
                <i class="fa fa-envelope bg-blue"></i>

                <div class="timeline-item">
                  <span class="time"><i class="fa fa-clock-o"></i> 2019/8/10</span>
                  <h3 class="timeline-header">E01-1908-8178901</h3>
                  <div class="timeline-body">
                    2019/08/12 通院<br />
                    2019/08/15 通院<br />
                    2019/08/20 通院<br />
                  </div>
                  <div class="timeline-footer">
                    <a class="btn btn-primary btn-xs">結果表示</a>
                    <a class="btn btn-danger btn-xs">これだけ</a>
                  </div>
                </div>
              </li>
              <!-- END timeline item -->
              <!-- timeline item -->
              <li>
                <i class="fa fa-envelope bg-blue"></i>

                <div class="timeline-item">
                  <span class="time"><i class="fa fa-clock-o"></i> 2019/10/05</span>
                  <h3 class="timeline-header">E01-1908-8178901</h3>
                  <div class="timeline-body">
                    2019/09/10 通院<br />
                    2019/08/05 手術<br />
                    2019/08/05 - 2019/08-10 入院<br />
                  </div>
                  <div class="timeline-footer">
                    <a class="btn btn-primary btn-xs">結果表示</a>
                    <a class="btn btn-danger btn-xs">これだけ</a>
                  </div>
                </div>
              </li>
              <!-- END timeline item -->

            </ul>
          </div>
          <!-- /.tab-pane -->

          <div class="tab-pane" id="settings">
            <div class="box-header with-border">
              <h3 class="box-title">E01-1908-8178901 (最新)</h3>
            </div>
            <div class="box-body">
              <form class="form-horizontal">
                <!-- Date -->
                <div class="form-group">
                  <label for="tsuin" class="col-sm-2 control-label">通院</label>

                  <div class="col-sm-10">
                    <div class="input-group input-group-sm">
                      <input type="text" class="form-control pull-right datepicker" id="tsuin" data-date-format="yyyy/mm/dd" name="date">
                      <span class="input-group-btn">
                        <button type="button" class="btn btn-info btn-flat">登録</button>
                      </span>
                    </div>
                  </div>
                  <!-- /.input group -->
                </div>
                <!-- /.form group -->
              </form>

              <form class="form-horizontal">
                <!-- Date -->
                <div class="form-group">
                  <label for="shujutsu" class="col-sm-2 control-label">手術</label>

                  <div class="col-sm-10">
                    <div class="input-group input-group-sm">
                      <input type="text" class="form-control pull-right datepicker" id="shujutsu" data-date-format="yyyy/mm/dd" name="date">
                      <span class="input-group-btn">
                        <button type="button" class="btn btn-info btn-flat">登録</button>
                      </span>
                    </div>
                  </div>
                  <!-- /.input group -->
                </div>
                <!-- /.form group -->
              </form>

              <form class="form-horizontal" action="api/v1/shoken/825-5678901/ukeban/E01-1908-8178903/nyuin" method="POST">
                <!-- Date range -->
                <div class="form-group">
                  <label for="nyuin" class="col-sm-2 control-label">入院</label>

                  <div class="col-sm-10">
                    <div class="input-group input-group-sm">
                      <input type="text" class="form-control pull-right" id="nyuin" name="daterange">
                      <span class="input-group-btn">
                        <button type="submit" class="btn btn-info btn-flat">登録</button>
                      </span>
                    </div>
                  </div>
                  <!-- /.input group -->
                </div>
                <!-- /.form group -->
              </form>

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
      </div>
      <!-- /.nav-tabs-custom -->
    </div>
    <!-- /.col -->

  </div>
  <!-- /.row -->
</section>
<!-- /.content -->
<?= $this->endSection() ?>

<?= $this->section('stylesheets') ?>
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="/vendor/adminlte-2.4.18/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<!-- bootstrap daterangepicker -->
<link rel="stylesheet" href="/vendor/adminlte-2.4.18/bower_components/bootstrap-daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="/vendor/adminlte-2.4.18/bower_components/bootstrap-monthrangepicker/daterangepicker.css">
<!-- fullCalendar -->
<link rel="stylesheet" href="/vendor/adminlte-2.4.18/bower_components/fullcalendar/dist/fullcalendar.min.css">
<link rel="stylesheet" href="/vendor/adminlte-2.4.18/bower_components/fullcalendar/dist/fullcalendar.print.min.css" media="print">
<?= $this->endSection() ?>

<?= $this->section('javascripts') ?>
<!-- moment.js -->
<script src="/vendor/adminlte-2.4.18/bower_components/moment/moment.js"></script>
<script src="/vendor/adminlte-2.4.18/bower_components/moment/locale/ja.js"></script>
<!-- bootstrap datepicker -->
<script src="/vendor/adminlte-2.4.18/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="/vendor/adminlte-2.4.18/bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.ja.min.js"></script>
<!-- bootstrap daterangepicker -->
<script src="/vendor/adminlte-2.4.18/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="/vendor/adminlte-2.4.18/bower_components/bootstrap-monthrangepicker/daterangepicker.js"></script>
<!-- fullCalendar -->
<script src="/vendor/adminlte-2.4.18/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>
<script src="/vendor/adminlte-2.4.18/bower_components/fullcalendar/dist/locale-all.js"></script>

<script>
  $(function () {
      $('#calendar').fullCalendar({
          views: {
              month: {
                  columnFormat: 'ddd',
                  titleFormat: 'YYYY年M月',
              },
          },
          buttonText: {
              today: '今日',
          },
          dayNames: ['日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日'],
          dayNamesShort: ['日', '月', '火', '水', '木', '金', '土'],
          events    : [
              {
                  title          : '通院',
                  start          : "2019-10-10",
                  end            : "2019-10-10",
                  backgroundColor: '#00a65a', //Success (green)
                  borderColor    : '#00a65a' //Success (green)
              },
              {
                  title          : '通院',
                  start          : "2019-10-17",
                  end            : "2019-10-17",
                  backgroundColor: '#00a65a', //Success (green)
                  borderColor    : '#00a65a' //Success (green)
              },
              {
                  title          : '通院',
                  start          : "2019-10-24",
                  end            : "2019-10-24",
                  backgroundColor: '#00a65a', //Success (green)
                  borderColor    : '#00a65a' //Success (green)
              },
              {
                  title          : '手術',
                  start          : "2019-10-15",
                  end            : "2019-10-15",
                  backgroundColor: '#f39c12', //yellow
                  borderColor    : '#f39c12' //yellow
              },
              {
                  title          : '入院',
                  start          : "2019-10-15",
                  end            : "2019-10-20",
                  backgroundColor: '#00c0ef', //Info (aqua)
                  borderColor    : '#00c0ef' //Info (aqua)
              },
              {
                  title          : '通院',
                  start          : "2019-10-30",
                  end            : "2019-10-30",
                  backgroundColor: '#00a65a', //Success (green)
                  borderColor    : '#00a65a' //Success (green)
              },
          ],
      });

      //Date range picker
      $('#nyuin').daterangepicker({
          autoApply: true,
          drops: 'down',
          locale: {
              format:'YYYY/MM/DD',
              applyLabel: '反映',
              cancelLabel: '取消',
              daysOfWeek: ['日', '月', '火', '水', '木', '金', '土'],
              monthNames: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月']
          },
      });

      //Date picker
      $('.datepicker').datepicker({
          autoclose: true,
          orientation: 'bottom',
          language: 'ja',
      })

      //Date picker
      $('#monthrange').monthrangepicker({
          autoApply: true,
          drops: 'down',
          locale: {
              format:'YYYY/MM',
              applyLabel: '反映',
              cancelLabel: '取消',
              monthNames: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月']
          }
      });

  });
</script>
<?= $this->endSection() ?>
