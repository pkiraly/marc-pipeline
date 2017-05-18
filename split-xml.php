<?php
define('CHUNK_SIZE', 10000);

$head = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$collOpen = '<collection xmlns="http://www.loc.gov/MARC21/slim">' . "\n";
$collClose = '</collection>' . "\n";

$inputFile = $argv[1];

$fh = fopen($inputFile, 'r');

$recNum = 0;
$outputFileName = generateFileName($inputFile, $recNum);
printf("write to %s\n", $outputFileName);
$output = fopen($outputFileName, 'w');
writeHeader();

$recNumInCurrentFile = 0;
if ($fh) {
  while (($line = fgets($fh)) !== false) {
    if (preg_match('/^(<\?xml|<\/?collection)/', $line)) {
      continue;
    }
    if (preg_match('/<record/', $line)) {
      $recNum++;
      $recNumInCurrentFile++;
    }
    fwrite($output, $line);
    if (preg_match('/<\/record/', $line)) {
      if ($recNum % CHUNK_SIZE == 0) {
        writeFooter();
        fclose($output);
        rename($outputFileName, 'splitted/' . $outputFileName);
        $outputFileName = generateFileName($inputFile, $recNum);
        printf("write to %s\n", $outputFileName);
        $output = fopen($outputFileName, 'w');
        writeHeader();
        $recNumInCurrentFile = 0;
      }
    }
  }
}
writeFooter();
fclose($output);
if (file_exists($outputFileName)) {
  if ($recNumInCurrentFile > 0) {
    rename($outputFileName, 'splitted/' . $outputFileName);
  } else {
    printf("delete file without record %s\n", $outputFileName);
    unlink($outputFileName);
  }
}

function generateFileName($inputFile, $recNum) {
  $fileName = sprintf('%s.%07d.xml', str_replace('.xml', '', preg_replace('/^marc\//', '', $inputFile)), $recNum);
  return $fileName;
}

function writeHeader() {
  global $output, $head, $collOpen;
  fwrite($output, $head);
  fwrite($output, $collOpen);
}

function writeFooter() {
  global $output, $collClose;
  fwrite($output, $collClose);
}