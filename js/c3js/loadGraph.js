function loadGraph(url, yLabel, graphType, format)
{
    if ( format == undefined )
    {
        format = '%Y-%m-%d';
    }

    c3.generate({
        bindto: '#chart',
        data: {
            x: 'x',
            mimeType: 'json',
            url: url,
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