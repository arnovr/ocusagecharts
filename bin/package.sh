#!/bin/bash
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
cd ../
zip -r "$zipfile" "$version"

#cleanup after zipping
rm -rf "$version"
