<h1>Free space / Used space</h1>
<?php
echo '<div class="chart" id="chart_' . $_['chart']->getConfig()->chartId . '"><div class="icon-loading" style="height: 60px;"></div></div>';
?>
<script type="application/javascript">
    var chart<?php echo $_['chart']->getConfig()->chartId;?> = c3.generate({
        bindto: '#chart_<?php echo $_['chart']->getConfig()->chartId;?>',
        data: {
            url: '<?php echo \OCP\Util::linkToRoute('ocusagecharts.chart_api.load_chart', array('id' => $_['chart']->getConfig()->chartId));?>',
            mimeType: 'json',
            type : 'pie'
        }
    });
</script>