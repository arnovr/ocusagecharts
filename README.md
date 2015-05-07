[![Stories in Ready](https://badge.waffle.io/arnovr/ocusagecharts.png?label=ready&title=Ready)](https://waffle.io/arnovr/ocusagecharts)
[![Build Status](https://travis-ci.org/arnovr/ocusagecharts.svg?branch=master)](https://travis-ci.org/arnovr/ocusagecharts)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/arnovr/ocusagecharts/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/arnovr/ocusagecharts/?branch=master)

ocUsageCharts
=============
ocUsageCharts is an application for owncloud 7 and 8. 
This application gives you the ability to display various statistics about your users within owncloud. 
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

Requirements
============
- Cronjobs must run every day!
- For activity charts, the activity app needs to be enabled
- Owncloud 7.0.1 ( untested with versions before 7.0.1 )

Install
=======
- Download ocUsageCharts from: https://apps.owncloud.com/content/show.php/Storage+Usage+%2B+Activity+Charts?content=166746
- Extract all files to owncloud/apps/ocusagecharts
- Login as administrator on your owncloud instance
- Press Apps in the menu
- Select "Charts" app in the menu
- Enable app "Charts"
- App is installed, check menu for "Charts"

Product reference
=================
- ocUsageCharts official release channel: https://apps.owncloud.com/content/show.php/Storage+Usage+%2B+Activity+Charts?content=166746
- ocUsageCharts wiki: https://github.com/arnovr/ocusagecharts/wiki
- C3.js: http://c3js.org/ - https://github.com/masayuki0812/c3
- D3.js: https://github.com/mbostock/d3

Vagrant
=======
When you are using vagrant for development, the following information helps you to log into owncloud: 

Owncloud:
- url: http://ocusagecharts.box
- username: vagrant
- password: vagrant