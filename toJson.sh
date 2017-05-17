#!/usr/bin/env bash

echo '----'
date +%Y-%m-%d:%H:%M:%S

FILES=splitted/*.xml
COUNT=${#FILES[@]}

echo "processing " $COUNT " files"

for xml in $FILES
do
  json=$(echo $xml | sed 's,.xml,.json,')
  echo "Processing $xml file to $json ..."
  # take action on each file. $f store current file name
  catmandu convert -v XML to JSON < $xml > $json
  mv $xml converted/
  mv $json json/raw/
  # php formatCatmanduOutput.php $json
  # php listPaths.php $jsonFormatted
done
echo DONE
