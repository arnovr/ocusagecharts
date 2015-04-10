ocUsageCharts
=============
ocUsageCharts is created due to the fact that the original usage_charts is no longer updated to work properly with owncloud 7.
This application is from the base up designed for Owncloud 7.
ocUsageCharts gives the ability to display various statistics about your users within owncloud.
These statistics include storage usage charts and activities charts.

Graphs
======
- User mode:
    - A pie chart showing space used / free space
    - A graph with data used over the course of the last month
    - A Bar chart with average data used in the last months
    - An Activity chart over the last month
    - An Activity chart with activities over the last months
- Admin mode:
    - A pie chart showing space used by all users
    - A graph with data used over the course of the last month for all users
    - A Bar chart with average data used in the last months for all users
    - An Activity chart over the last month for all users
    - An Activity chart with activities over the last months for all users

Future ideas
============
- Improve User Interface to handle a lot of users
- Graphs:
    - Files downloaded
    - Option to use alternative for C3.js

Requirements
============
- Cronjobs must run every day!
- For activity charts, the activity app needs to be enabled
- Owncloud 7.0.1 ( untested with versions before 7.0.1 )

Install
=======
- Download all files to owncloud/apps/ocusagecharts directory
- Login as admin on owncloud
- Press Apps in the menu
- Open app Charts
- Enable app Charts
- App is installed, check menu for Charts

FAQ
===
- The charts won't populate for some users?
By default a user had to been logged in and open the charts app to be tracked.
You could add all users to your charts by running the following command on your server
./occ ocusagecharts:createdefaultcharts
Pay attention, this adds ALL owncloud users to the charts application! 
The charts app is not designed for many users! ( will be in the future )

- The charts app won't populate at all
The chart app makes use of the owncloud cron, if that cron doesn't run, the chart will not fill up with data.
You could setup the cron accordingly:
https://doc.owncloud.org/server/8.0/admin_manual/configuration_server/background_jobs_configuration.html
I would suggest using the owncloud "cron" setup.

- 500 Error [issue 53](https://github.com/arnovr/ocusagecharts/issues/53)
External dependencies are not included in github repo. Please download the latest version of d3 and c3.
Create folder structure; Inside `/owncloud/apps/ocusagecharts/js` create two folders `c3` and `d3`. Place dependencies inside. 


Product reference
=================
- ocUsagechart official release channel: http://apps.owncloud.com/content/show.php/Usage+Charts+%28+owncloud+7+%29?content=166746
- C3.js: http://c3js.org/ - https://github.com/masayuki0812/c3
- usage_charts: http://apps.owncloud.com/content/show.php/Usage+Charts?content=164956 - https://github.com/alanv72/usage_charts ( a fork from StorageCharts/ocStorage )

Build Status
============
[![Build Status](https://travis-ci.org/arnovr/ocusagecharts.svg?branch=master)](https://travis-ci.org/arnovr/ocusagecharts)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/arnovr/ocusagecharts/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/arnovr/ocusagecharts/?branch=master)