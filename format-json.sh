#!/usr/bin/env bash

FILES=json/raw/*.json

for json in $FILES
do
  date +%Y-%m-%d:%H:%M:%S
  jsonFormatted=$(echo $json | sed 's,.json,.formatted.json,')
  echo "Processing $json file to $jsonFormatted ..."
  # take action on each file. $f store current file name
  php formatCatmanduOutput.php $json
  php listPaths.php $jsonFormatted
  mv $json json/processed/
  mv $jsonFormatted json/formatted/
done

exit 0
