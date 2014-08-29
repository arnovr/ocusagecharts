function loadGraph(url, yLabel, graphType, format, size)
{
    if ( format == undefined )
    {
        format = '%Y-%m-%d';
    }
    url += '&size=' + size;

    c3.generate({
        bindto: '#chart',
        data: {
            x: 'x',
            mimeType: 'json',
            url: url ,
            type: graphType
        },
        axis: {
            x: {
                type: 'timeseries',
                tick: {
                    format: format
                }
            },
            y: {
                label: yLabel
            }
        }
    });
}

function loadPie(url)
{
    c3.generate({
        bindto: '#chart',
        data: {
            mimeType: 'json',
            url: url,
            type: 'pie'
        }
    });
}
$( document ).ready(function() {
    $("#controls").find('a').click(function() {
        loadGraph(
            $(this).data("url"),
            $(this).data("label"),
            $(this).data("type"),
            $(this).data("format"),
            $(this).data("size")
        );
    });
    if ($("#defaultChart").length > 0 )
    {
        loadGraph(
            $("#defaultChart").data("url"),
            $("#defaultChart").data("label"),
            $("#defaultChart").data("type"),
            $("#defaultChart").data("format"),
            $("#defaultChart").data("size")
        );
    }

    if ($("#defaultBar").length > 0 )
    {
        loadPie(
            $("#defaultBar").data("url")
        );
    }
});