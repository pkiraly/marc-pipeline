<?php

$fileName = $argv[1];
# $file = file_get_contents('test2.json');

$file = file_get_contents($fileName);
$jsonObj = json_decode($file);
$newLines = [];
foreach ($jsonObj[0]->record as $obj) {
  $newLines[] = format($obj);
}

# unlink($fileName);
$outputFileName = fileRename($fileName);
file_put_contents($outputFileName, join("\n", $newLines));
printf("Formatting saved to %s\n", $outputFileName);


function format($obj) {
  $json = (object)[
    'leader' => $obj->leader,
    'controlfield' => [],
    'datafield' => [],
  ];
  foreach ($obj->controlfield as $controlfield) {
    $json->controlfield[] = (object)[
      'tag' => $controlfield->tag,
      'content' => $controlfield->content,
    ];
  }
  foreach ($obj->datafield as $datafield) {
    $json->datafield[] = extractDatafield($datafield);
  }
  return json_encode($json, JSON_UNESCAPED_UNICODE);
}

function extractDatafield($datafield) {
  $normalized = (object)[
    'tag' => $datafield->tag,
    'ind1' => $datafield->ind1,
    'ind2' => $datafield->ind2,
    'subfield' => [],
  ];
  if (is_array($datafield->subfield)) {
    foreach ($datafield->subfield as $subfield) {
      $normalized->subfield[] = extractSubfield($datafield->tag, $subfield);
    }
  } else {
    $normalized->subfield[] = extractSubfield($datafield->tag, $datafield->subfield);
  }
  return $normalized;
}

function extractSubfield($tag, $subfield) {
  static $known_empty_subfields = [
    '924$w', '583$a',
    '020$z', '020$z', '100$a', '245$a', '245$p', '490$a', '700$a', '711$d', '773$t', '787$t', '800$a',
    '800$b', '800$t', '810$b', '810$t', '830$a', '830$b', '856$u', '880$a', '980$d', '980$l', '980$y',
  ];

  if (!isset($subfield->content)) {
    $code = sprintf("%s$%s", $tag, $subfield->code);
    if (!in_array($code, $known_empty_subfields)) {
      printf("Missing content in field '%s'\n", $code);
      print_r($subfield);
    }
  }
  return (object)[
    'code' => $subfield->code,
    'content' => isset($subfield->content) ? $subfield->content : '',
  ];
}

function fileRename($filename) {
  return str_replace('.json', '.formatted.json', $filename);
}