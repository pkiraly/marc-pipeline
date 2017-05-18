<?php

$fileName = $argv[1];

# $file = file_get_contents('test2.json');
# printf("List paths for %s\n", $fileName);
$file = file_get_contents($fileName);

$lines = explode("\n", $file);

$statistics = (object)[
  'leader' => 0,
  'controlfield' => [],
  'datafield' => [],
];

$knownControllfields = ["001", "003", "005", "006", "007", "008"];
$knownDatafields = [
  '015$2', '015$a', '016$2', '016$a', '020$9', '020$a', '022$a', '022$y', '024$2', '024$a',
  '029$a', '029$c', '035$a', '040$a', '040$b', '040$c', '040$e', '041$a', '044$a', '046$2',
  '046$j', '082$a', '084$2', '084$a', '100$0', '100$a', '100$b', '100$c', '100$d', '110$0',
  '110$9', '110$a', '110$b', '240$a', '240$g', '243$a', '245$a', '245$b', '245$c', '245$n',
  '245$p', '246$a', '246$i', '250$a', '260$a', '260$b', '260$c', '260$e', '260$f', '300$a',
  '300$b', '300$c', '300$e', '362$a', '363$a', '363$i', '490$a', '490$v', '500$a', '501$a',
  '502$a', '505$t', '515$a', '533$b', '533$c', '533$n', '534$c', '583$a', '583$h', '591$a',
  '600$a', '648$2', '648$a', '650$0', '650$2', '650$9', '650$a', '650$x', '651$0', '651$2',
  '651$a', '655$2', '655$a', '689$0', '689$2', '689$5', '689$9', '689$A', '689$a', '689$b',
  '689$c', '689$D', '689$t', '689$x', '689$z', '700$0', '700$4', '700$a', '700$c', '700$d',
  '700$e', '710$0', '710$9', '710$a', '710$b', '711$0', '711$a', '711$c', '711$d', '711$n',
  '751$4', '751$a', '773$g', '773$q', '773$w', '775$a', '775$i', '775$t', '775$w', '776$9',
  '776$z', '780$a', '780$i', '780$t', '780$w', '785$a', '785$i', '785$t', '785$w', '787$a',
  '787$i', '787$t', '787$w', '800$a', '800$c', '800$d', '800$g', '800$q', '800$t', '800$v',
  '800$w', '810$a', '810$b', '810$q', '810$t', '810$v', '810$w', '830$a', '830$g', '830$q',
  '830$v', '830$w', '856$3', '856$m', '856$q', '856$u', '856$v', '856$y', '856$z', '866$a',
  '889$w', '924$b', '935$a', '935$b', '935$c', '935$d', '936$a', '936$b', '936$k',

  // new values
  '610$0', '610$2', '610$a', '600$x', '111$a', '111$d', '111$c', '111$0', '770$i', '770$t',
  '770$w', '245$h', '010$a', '100$e', '100$4', '264$a', '264$b', '264$c', '336$a', '336$b',
  '336$2', '337$a', '337$b', '337$2', '338$a', '338$b', '338$2', '775$d', '651$9', '020$z',
  '689$p', '041$h', '610$9', '600$0', '600$2', '600$c', '787$0', '830$p', '711$9', '810$c',
  '028$a', '030$a', '210$a', '800$p', '700$b', '689$d', '770$a', '689$f', '630$0', '630$2',
  '630$t', '689$g', '610$b', '020$c', '600$t', '810$g', '246$d', '936$0', '026$e', '240$o',
  '856$x', '630$p', '689$n', '935$e', '772$i', '772$t', '772$w', '111$n', '591$x', '610$x',
  '651$x', '711$e', '856$n', '363$b', '520$a', '111$e', '088$a', '034$a', '034$b', '255$a',
  '255$c', '937$a', '937$b', '937$c', '533$d', '533$7', '710$n', '772$a', '050$a', '555$a',
  '022$c', '533$f', '032$a', '611$0', '611$2', '611$a', '583$c', '363$u', '111$9', '363$k',
  '689$m', '710$4', '630$9', '630$n', '773$i', '773$a', '773$d', '936$j', '936$h', '937$e',
  '937$f', '937$d', '611$d', '611$c', '511$a', '130$a', '533$a', '533$e', '776$a', '776$b',
  '776$w', '240$m', '240$n', '240$0', '290$b', '787$d', '250$9', '363$j', '651$z', '800$b',
  '255$b', '538$a', '024$9', '110$n', '936$d', '130$g', '535$a', '535$g', '249$a', '249$b',
  '810$p', '936$e', '240$p', '912$a', '502$b', '502$c', '502$d', '655$0', '710$e', '630$a',
  '630$x', '240$f', '240$s', '611$b', '611$n', '776$q', '776$i', '776$t', '776$d', '776$h',
  '936$g', '856$2', '521$a', '776$c', '856$a', '936$y', '912$b', '655$x', '936$c', '340$a',
  '561$a', '562$b', '562$a', '563$a', '581$a', '581$8', '589$c', '600$b', '936$f', '024$z',
  '600$n', '611$9', '776$o', '856$p', '130$o', '130$n', '130$0', '130$p', '130$m', '591$2',
  '500$x', '300$x', '533$x', '730$t', '730$g', '730$0',

  // OCLC
  '019$a', '029$b', '035$z', '040$d', '041$b', '043$a', '049$a', '050$b', '066$c', '090$a',
  '090$b', '100$6', '110$6', '240$k', '245$6', '246$6', '250$6', '260$6', '260$g', '490$6',
  '500$6', '505$6', '505$a', '530$a', '590$a', '593$a', '600$6', '600$d', '600$v', '610$6',
  '610$v', '650$y', '650$z', '651$6', '651$v', '653$6', '653$a', '700$6', '710$6', '730$6',
  '730$a', '730$f', '740$6', '740$a', '776$s', '830$6', '880$6', '880$a', '880$b', '880$c',
  '880$d', '880$f', '880$g', '880$v', '880$x', '938$a', '938$b', '938$n', '994$a', '994$b',

  //
  '924$a', '924$c', '924$d', '924$9', '924$g', '924$h', '924$k', '924$j', '773$t', '924$m',
  '924$q', '924$v', '924$r', '924$z', '924$w', '246$b', '546$a', '811$a', '811$t', '811$v',
  '811$w', '700$t', '924$i', '924$l', '811$q', '811$c', '924$x', '610$t', '700$r', '811$n',
  '811$d', '811$g', '711$4', '811$p', '775$n', '689$r', '780$d', '611$t', '110$4', '110$e',
  '111$4', '111$j', '240$r', '247$a', '247$f', '518$a', '600$9', '600$p', '655$y', '655$z',
  '700$k', '711$j', '773$x', '776$n', '924$e', '924$n', '024$c', '040$h', '082$n', '090$v',
  '110$c', '130$7', '130$f', '130$k', '130$r', '130$s', '240$7', '240$9', '240$t', '240$x',
  '249$c', '249$v', '290$a', '336$3', '336$8', '337$3', '337$8', '338$3', '338$8', '380$0',
  '380$2', '380$a', '382$0', '382$2', '382$a', '382$d', '382$g', '382$n', '382$p', '382$s',
  '382$v', '383$a', '383$b', '383$c', '384$a', '384$b', '385$0', '385$a', '385$g', '502$g',
  '505$r', '508$a', '610$f', '610$n', '648$x', '655$g', '700$f', '700$g', '700$m', '700$n',
  '700$o', '700$p', '700$s', '700$T', '700$U', '710$c', '710$f', '710$g', '710$k', '710$m',
  '710$o', '710$r', '710$t', '711$t', '730$k', '730$m', '730$n', '730$o', '730$p', '730$r',
  '730$s', '730$T', '730$U', '770$d', '770$h', '770$n', '770$o', '772$n', '773$h', '773$n',
  '773$o', '775$0', '775$h', '775$o', '776$0', '776$k', '780$0', '780$n', '785$0', '785$d',
  '787$h', '787$n', '787$o', '811$e', '856$h', '856$s', '856$t', '880$T', '880$U', '924$s',
  '924$y', '935$m', '689$s', '785$n', '013$a', '013$c', '630$f', '027$a', '610$g', '600$s',
  '600$m', '600$r', '255$e', '936$q', '044$h', '100$h', '130$9', '130$t', '130$x', '300$2',
  '490$2', '490$x', '500$2', '535$q', '589$d', '600$f', '650$q', '700$q', '710$d', '710$p',
  '710$s', '776$x', '856$b', '856$d', '856$f', '856$i', '856$l', '880$0', '936$m',

  // GVK
  '026$2', '026$5', '044$c', '060$a', '084$9', '084$q', '110$d', '110$g', '210$b', '246$g',
  '250$b', '256$a', '260$w', '263$a', '264$6', '300$w', '363$x', '365$b', '385$2', '490$w',
  '500$w', '504$a', '510$a', '510$c', '534$a', '534$n', '547$a', '547$d', '547$v', '555$x',
  '555$y', '555$z', '583$k', '583$z', '600$3', '600$8', '600$e', '600$g', '600$k', '600$l',
  '600$q', '600$y', '600$z', '610$8', '610$c', '610$d', '610$e', '610$k', '610$l', '610$p',
  '610$s', '610$y', '610$z', '611$e', '611$q', '611$v', '611$x', '611$y', '611$z', '630$c',
  '630$d', '630$g', '630$h', '630$k', '630$l', '630$s', '630$v', '630$y', '630$z', '648$8',
  '650$8', '650$b', '650$c', '650$d', '650$f', '650$g', '650$l', '650$p', '650$s', '650$t',
  '650$v', '651$b', '651$d', '651$s', '651$y', '653$0', '653$2', '653$A', '653$c', '653$d',
  '653$f', '653$g', '653$h', '653$S', '653$s', '653$t', '653$v', '653$x', '653$y', '653$z',
  '655$5', '655$c', '655$v', '772$d', '773$z', '780$h', '785$h', '787$l', '787$x', '787$z',
  '800$n', '810$9', '810$n', '830$9', '830$b', '830$n', '880$4', '880$e', '880$h', '880$n',
  '880$p', '880$w', '901$a', '950$2', '950$a', '951$a', '952$b', '952$c', '952$d', '952$e',
  '952$f', '952$g', '952$h', '952$j', '952$y', '960$a', '980$1', '980$2', '980$a', '980$b',
  '980$d', '980$e', '980$f', '980$g', '980$k', '980$l', '980$x', '980$y', '980$z', '981$1',
  '981$2', '981$3', '981$q', '981$r', '981$w', '981$y', '982$0', '982$1', '982$2', '982$8',
  '982$a', '983$0', '983$1', '983$2', '983$8', '983$a', '983$b', '984$1', '984$2', '984$a',
  '984$b', '984$c', '984$x', '985$1', '985$2', '985$a',

  // LoC
  '010$z', '017$a', '017$b', '024$d', '025$a', '028$b', '036$a', '037$a', '037$b', '037$c',
  '037$f', '038$a', '041$d', '041$e', '041$f', '041$g', '041$k', '042$a', '043$b', '043$c',
  '045$a', '045$b', '046$a', '050$3', '051$a', '051$b', '051$c', '052$a', '052$b', '055$a',
  '055$b', '060$b', '066$a', '070$a', '070$b', '072$2', '072$a', '072$x', '074$a', '074$z',
  '080$2', '080$a', '082$2', '082$b', '086$2', '086$a', '086$z', '100$k', '100$q', '100$t',
  '110$f', '110$k', '110$l', '110$s', '110$t', '111$6', '111$b', '111$q', '130$6', '130$d',
  '130$l', '240$6', '240$d', '240$h', '240$l', '242$a', '242$b', '242$c', '242$y', '245$f',
  '245$g', '245$k', '245$s', '245$v', '246$5', '246$f', '246$h', '246$n', '246$p', '260$3',
  '260$d', '265$a', '270$a', '270$b', '270$c', '270$d', '270$e', '270$h', '270$k', '270$m',
  '300$3', '300$6', '300$g', '310$a', '350$a', '351$a', '400$a', '400$b', '400$c', '400$d',
  '400$t', '400$v', '400$x', '410$a', '410$b', '410$c', '410$d', '410$n', '410$p', '410$t',
  '410$v', '440$6', '440$a', '440$n', '440$p', '440$v', '440$x', '489$a', '489$v', '490$l',
  '500$3', '500$5', '501$5', '504$6', '505$g', '506$a', '506$c', '510$6', '510$b', '513$a',
  '513$b', '516$a', '520$b', '520$u', '522$a', '524$a', '525$a', '530$b', '530$u', '533$3',
  '533$m', '534$f', '534$p', '534$t', '536$a', '536$b', '536$c', '536$d', '536$e', '536$f',
  '540$a', '540$b', '541$3', '541$5', '541$a', '541$b', '541$c', '541$d', '541$e', '545$a',
  '546$b', '550$a', '561$5', '561$6', '580$a', '583$3', '583$5', '583$b', '583$f', '583$l',
  '583$x', '585$3', '585$a', '586$a', '588$a', '600$4', '611$6', '630$6', '648$v', '650$6',
  '650$e', '651$t', '654$2', '654$a', '654$b', '654$c', '657$2', '657$a', '657$y', '662$2',
  '662$a', '662$d', '700$5', '700$i', '700$l', '700$x', '710$5', '710$l', '710$x', '711$6',
  '711$b', '711$q', '730$5', '730$d', '730$l', '730$x', '740$5', '740$n', '740$p', '752$a',
  '752$b', '752$c', '752$d', '753$a', '753$c', '760$g', '760$t', '760$x', '773$3', '775$e',
  '775$z', '780$b', '780$z', '785$b', '785$s', '785$x', '785$z', '787$b', '800$6', '800$f',
  '800$k', '800$l', '800$s', '810$6', '810$d', '830$d', '830$f', '830$k', '830$l', '830$s',
  '830$t', '850$a', '852$a', '852$b', '852$c', '852$e', '852$h', '852$i', '852$k', '852$m',
  '852$n', '852$p', '852$t', '852$u', '852$x', '852$z', '880$1', '880$2', '880$3', '880$5',
  '880$i', '880$k', '880$l', '880$q', '880$r', '880$s', '880$t', '880$y', '880$z', '886$2',
  '886$a', '886$b', '886$x', '886$z', '987$a', '987$b', '987$c', '987$d', '987$e'
];


foreach ($lines as $line) {
  $obj = json_decode($line);
  if ($obj != null) {
    if ($obj->leader) {
      $statistics->leader++;
    }
    foreach ($obj->controlfield as $controlfield) {
      if (in_array($controlfield->tag, $knownControllfields))
        continue;

      if (!isset($statistics->controlfield[$controlfield->tag])) {
        $statistics->controlfield[$controlfield->tag] = 1;
      } else {
        $statistics->controlfield[$controlfield->tag]++;
      }
    }
    foreach ($obj->datafield as $datafield) {
      if (!is_array($datafield->subfield)) {
        $datafield->subfield = [$datafield->subfield];
      }
      foreach ($datafield->subfield as $subfield) {
        $key = $datafield->tag . '$' . $subfield->code;
        if (in_array($key, $knownDatafields))
          continue;
        if (!isset($statistics->datafield[$key])) {
          $statistics->datafield[$key] = 1;
        } else {
          $statistics->datafield[$key]++;
        }
      }
    }
  }
}
if ($statistics->leader != 10000
    || !empty($statistics->controlfield)
    || !empty($statistics->datafield)) 
{
  print_r($statistics);
}
# file_put_contents('marc.statistics.txt', json_encode($statistics));

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
  return json_encode($json);
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
      $normalized->subfield[] = extractSubfield($subfield);
    }
  } else {
    $normalized->subfield[] = extractSubfield($datafield->subfield);
  }
  return $normalized;
}

function extractSubfield($subfield) {
  return (object)[
    'code' => $subfield->code,
    'content' => $subfield->content,
  ];
}