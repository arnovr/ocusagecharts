ocUsageCharts Adapters
======================
When chart data is retrieved by the DataProviders, it is passed through these adapters based on the ChartType supplied in the chart.
The reason for this, is that it will give the possibility to implement a different chart provider in the future.

When you add a different provider you will have to do 2 things:
1) add adapters and implement ChartTypeAdapterInterface at the adapter directory, with the provider as a seperate directory
2) Add new chart provider at the class ChartTypeAdapterFactory

Based on the Entity\ChartConfig you could use a different provider for each chart on the website.