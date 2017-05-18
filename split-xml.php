<?php
define('CHUNK_SIZE', 10000);

$head = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$collOpen = '<collection xmlns="http://www.loc.gov/MARC21/slim">' . "\n";
$collClose = '</collection>' . "\n";

$inputFile = $argv[1];

$fh = fopen($inputFile, 'r');

$recNum = 0;
printf("generateFileName(%s, %s)\n", $inputFile, $recNum);
$outputFileName = generateFileName($inputFile, $recNum);
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
        printf("generateFileName(%s, %s)\n", $inputFile, $recNum);
        $outputFileName = generateFileName($inputFile, $recNum);
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
    rename($outputFileName, '../splitted/' . $outputFileName);
  } else {
    unlink($outputFileName);
  }
}

function generateFileName($inputFile, $recNum) {
  $fileName = sprintf('%s.%07d.xml', str_replace('.xml', '', preg_replace('/^marc\//', '', $inputFile)), $recNum);
  print "print to: $fileName\n";
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