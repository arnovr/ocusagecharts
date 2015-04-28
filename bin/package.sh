#!/bin/bash
# I needed something quick to build a release with
# The script is crap, but it works for me. :)
# So bleh bleh bleh
cd ../
version=`cat appinfo/version`
version="ocusagecharts_$version"
zipfile="$version.zip"

echo $version | xargs mkdir
cp -Rv * "$version"
cd "$version"
rm -rf "$version" #also copied in directory
cd js/d3
find ! -name 'd3.min.js' -type f -exec rm -f {} +
cd ../../
cd js/c3
find ! -name 'c3.min.js' -type f -exec rm -f {} +
cd ../../
rm -rf bower.json
rm -rf build.xml
rm -rf .git
rm -f .travis.yml
rm -f .scrutinizer.yml
rm -rf .idea
rm -rf tests/
rm -rf package.sh
rm -rf composer.json
rm -rf composer.lock
rm -rf behat.yml
rm -rf features
rm -rf bin/
rm -rf vendor
rm -rf Vagrantfile
rm -rf ansible
cd ../
zip -r "$zipfile" "$version"

#cleanup after zipping
rm -rf "$version"
