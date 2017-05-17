#!/usr/bin/env bash

xml=$1
date +%Y-%m-%d:%H:%M:%S

json=$(echo $xml | sed 's,.xml,.json,')
echo "Processing $xml file to $json ..."
catmandu convert -v XML to JSON < $xml > $json
mv $xml converted/
mv $json json/raw/
echo DONE

exit 0