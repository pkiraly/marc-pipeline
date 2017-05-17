#!/usr/bin/env bash

echo '----'
date +%Y-%m-%d:%H:%M:%S

FILES=*.xml
COUNT=${#FILES[@]}

for file in $FILES
do
  echo "Process $file..."
  uconv -x any-nfc $file > temp.json
  mv temp.json $file
done
echo DONE

