<!DOCTYPE html> <!-- -*- mode:html -*- -->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>スマケアカレンダー</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="/vendor/adminlte-2.4.18/bower_components/bootstrap/dist/css/bootstrap.min.css">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="/vendor/adminlte-2.4.18/bower_components/font-awesome/css/font-awesome.min.css">

  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="/vendor/adminlte-2.4.18/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="/vendor/adminlte-2.4.18/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="/vendor/adminlte-2.4.18/bower_components/bootstrap-monthrangepicker/daterangepicker.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="/vendor/adminlte-2.4.18/bower_components/Ionicons/css/ionicons.min.css">
  <!-- fullCalendar -->
  <link rel="stylesheet" href="/vendor/adminlte-2.4.18/bower_components/fullcalendar/dist/fullcalendar.min.css">
  <link rel="stylesheet" href="/vendor/adminlte-2.4.18/bower_components/fullcalendar/dist/fullcalendar.print.min.css" media="print">
  <!-- Theme style -->
  <link rel="stylesheet" href="/vendor/adminlte-2.4.18/dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="/vendor/adminlte-2.4.18/dist/css/skins/skin-green.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="/" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>通</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>通院</b>カレンダー</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- search form (Optional) -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="証券番号入力...">
          <span class="input-group-btn">
              <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
              </button>
            </span>
        </div>
      </form>
      <!-- /.search form -->

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">閲覧履歴</li>
        <!-- Optionally, you can add icons to the links -->
        <li class="active"><a href="#"><i class="fa fa-circle-o"></i><span>Hikaru Miyashita</span></a></li>
        <li><a href="#"><i class="fa fa-circle-o"></i><span>Yuji Nomura</span></a></li>
        <li><a href="#"><i class="fa fa-circle-o"></i><span>Hiroki Yoshida</span></a></li>

      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Hikaru Miyashita
        <small>046-2042043</small>
      </h1>

      <div class="breadcrumb">
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
  </div>
  <!-- /.content-wrapper -->

  <!-- Add the sidebar's background. This div must be placed
  immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->


<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 3 -->
<script src="/vendor/adminlte-2.4.18/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="/vendor/adminlte-2.4.18/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="/vendor/adminlte-2.4.18/bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Slimscroll -->
<script src="/vendor/adminlte-2.4.18/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="/vendor/adminlte-2.4.18/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="/vendor/adminlte-2.4.18/dist/js/adminlte.min.js"></script>
<!-- fullCalendar -->
<script src="/vendor/adminlte-2.4.18/bower_components/moment/moment.js"></script>
<script src="/vendor/adminlte-2.4.18/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>
<script src="/vendor/adminlte-2.4.18/bower_components/fullcalendar/dist/locale-all.js"></script>
<!-- bootstrap datepicker -->
<script src="/vendor/adminlte-2.4.18/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="/vendor/adminlte-2.4.18/bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.ja.min.js"></script>
<!-- bootstrap daterangepicker -->
<script src="/vendor/adminlte-2.4.18/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="/vendor/adminlte-2.4.18/bower_components/bootstrap-monthrangepicker/daterangepicker.js"></script>
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
      })
  })
</script>

</body>
</html>
