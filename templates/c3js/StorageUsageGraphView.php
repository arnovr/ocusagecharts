<script type="application/javascript">
    var chart<?php p($_['chart']->getConfig()->chartId);?> = c3.generate({
        bindto: '#chart_<?php p($_['chart']->getConfig()->chartId);?>',
        data: {
            x: 'x',
            x_format: '%Y-%m-%d',
            mimeType: 'json',
            url: '<?php echo \OCP\Util::linkToRoute('ocUsageCharts.chart.load_chart', array('id' => $_['chart']->getConfig()->chartId));?>'
        }
</script>