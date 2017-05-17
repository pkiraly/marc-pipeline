#!/usr/bin/env bash

echo '----'
date +%Y-%m-%d:%H:%M:%S

FILES=marc/*.mrc
COUNT=${#FILES[@]}

echo "processing " $COUNT " files"

for mrc in $FILES
do
  xml=$(echo $mrc | sed 's,.mrc,.xml,')
  date +%Y-%m-%d:%H:%M:%S
  echo "Processing $mrc file to $xml...."
  # take action on each file. $f store current file name
  yaz-marcdump -t UTF-8 -o marcxml $mrc > $xml
  date +%Y-%m-%d:%H:%M:%S
  echo "Split $xml..."
  php splitXml.php $xml
done

date +%Y-%m-%d:%H:%M:%S
echo DONE
