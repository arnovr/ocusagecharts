<?php
$url = \OCP\Util::linkToRoute('ocusagecharts.chart_api.load_chart', array('id' => $_['chart']->getConfig()->getId(), 'requesttoken' => $_['requesttoken']));
?>
<div id="controls" style="margin-left: 3px;">
    <div id="new" class="button">
        <a href="javascript:loadGraph('<?php echo $url; ?>&size=kb', '<?php p($l->t('Kilobytes')); ?>', 'bar', '%Y-%m');"><?php p($l->t('Kilobytes')); ?></a>
    </div>
    <div id="new" class="button">
        <a href="javascript:loadGraph('<?php echo $url; ?>&size=mb', '<?php p($l->t('Megabytes')); ?>', 'bar', '%Y-%m');"><?php p($l->t('Megabytes')); ?></a>
    </div>
    <div id="new" class="button">
        <a href="javascript:loadGraph('<?php echo $url; ?>&size=gb', '<?php p($l->t('Gigabytes')); ?>', 'bar', '%Y-%m');"><?php p($l->t('Gigabytes')); ?></a>
    </div>
</div>
<div style="height: 50px;"></div>
<h1><?php p($l->t($_['chart']->getConfig()->getChartType())); ?></h1>
<?php
echo '<div class="chart" id="chart"><div class="icon-loading" style="height: 60px;"></div></div>';
?>
<script type="application/javascript">
    window.onload = loadGraph('<?php echo $url; ?>&size=gb', '<?php p($l->t('Gigabytes')); ?>', 'bar', '%Y-%m');
</script>