<?php foreach ($shoken as $value) { ?>
<li><a href="<?=site_url("{$value['id']}/") ?>"><i class="fa fa-circle-o"></i><span><?=$value['id'] ?></span></a></li>
<?php } ?>
