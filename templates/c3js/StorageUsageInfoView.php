<div id="chart_<?php p($_['chart']->getConfig()->chartId); ?>"></div>
<script type="application/javascript">
    var chart<?php echo $_['chart']->getConfig()->chartId;?> = c3.generate({
        bindto: '#chart_<?php echo $_['chart']->getConfig()->chartId;?>',
        data: {
            url: '<?php echo \OCP\Util::linkToRoute('ocUsageCharts.chart.load_chart', array('id' => $_['chart']->getConfig()->chartId));?>',
            mimeType: 'json',
            type : 'pie'
        }
    });
</script>