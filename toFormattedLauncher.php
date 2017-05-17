<?php
/**
 * before start:
 *   ls json/raw/* > to-formatted-setlist.txt
 *
 * launch:
 *   crontab -e
 *   *\/1 * * * * cd /roedel/pkiraly && php toJsonLauncher.php >> launch-report.log
 *
 * monitoring:
 *   watch './running-status.sh saturation'
 */

define('MAX_THREADS', 10);
define('SET_FILE_NAME', 'to-formatted-setlist.txt');

$script = 'one-json-to-formatted.sh';
// $endTime = time() + 60;
// $i = 1;
echo 'file size: ', filesize(SET_FILE_NAME), "\n";
while (filesize(SET_FILE_NAME) > 3) {
// while (time() < $endTime) {
  $psCmd = 'ps aux | grep "[/]' . $script . '" | wc -l';
  // echo $psCmd, "\n";
  $threads = exec($psCmd);
  # echo 'threads: ', $threads, "\n";
  if ($threads < MAX_THREADS) {
    if (filesize(SET_FILE_NAME) > 3) {
      launch_threads($threads);
    }
  }
  sleep(2);
  clearstatcache(true, SET_FILE_NAME);
}

function launch_threads($running_threads) {
  global $script;
  clearstatcache(true, SET_FILE_NAME);

  if (filesize(SET_FILE_NAME) > 3) {
    echo 'file size: ', filesize(SET_FILE_NAME), "\n";
    $contents = file_get_contents(SET_FILE_NAME);
    $lines = explode("\n", $contents);
    $files = [];
    $slots = MAX_THREADS - $running_threads;
    for ($i = 1; $i <= $slots; $i++) {
      if (count($lines) > 0) {
        $files[] = array_shift($lines);
      }
    }
    printf("Running threads: %d, slots: %d, new files: %d\n", $running_threads, $slots, count($files));
    $contents = join("\n", $lines);
    file_put_contents(SET_FILE_NAME, $contents);
    foreach ($files as $file) {
      printf("%s launching set: %s, remaining sets: %d\n", date("Y-m-d H:i:s"), $file, count($lines));
      exec('nohup ./' . $script . ' ' . $file . ' >>script-formatted-report.log 2>>script-formatted-report.log &');
    }
  }
}
