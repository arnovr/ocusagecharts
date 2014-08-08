<div id="chart_<?php p($_['chart']->getId()); ?>">Stub</div>
<script type="application/javascript">
    function generateChart(chartId, data)
    {
        var chart = c3.generate({
            bindto: chartId,
            data: data
        });
    }

    $.ajax({
        url: "<?php echo \OCP\Util::linkToRoute('ocUsageCharts.chart.load_chart', array('id' => $_['chart']->getId()));
        // @TODO, add chart ID to link, to let ajax know which chart it is.
        ?>",
        context: document.body
    }).done(
        function( data ) {
            generateChart("#chart_<?php p($_['chart']->getId()); ?>", data)
        }
    );
</script>