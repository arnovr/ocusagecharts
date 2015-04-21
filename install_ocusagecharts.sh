#!/bin/bash
cd /var/www/owncloud/apps/
git clone https://github.com/arnovr/ocusagecharts.git
curl -sL https://deb.nodesource.com/setup | sudo bash -
apt-get install -y nodejs
npm install -g bower
cd ocusagecharts
bower install
cd /var/www/owncloud
./occ app:enable ocusagecharts
#command to update language tags.
#./occ l10n:createjs chat