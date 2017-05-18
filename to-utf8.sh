#!/usr/bin/env bash

echo '----'
date +%Y-%m-%d:%H:%M:%S

FILES=marc/*.xml
COUNT=${#FILES[@]}

for file in $FILES
do
  temp=$file.temp
  echo "Process $file..."
  uconv -x any-nfc $file > $temp
  mv $temp $file
done
echo DONE

