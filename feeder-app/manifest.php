<?php
  header('Content-Type: text/cache-manifest');
  echo "CACHE MANIFEST\n";

  $hashes = "";

  $dir = new RecursiveDirectoryIterator(".");
  foreach(new RecursiveIteratorIterator($dir) as $file) {
    if ($file->IsFile() &&
        $file != "./manifest.php" &&
		substr($file, 0 , 8) != "./demos/" && 
		substr($file, 0 , 13) != "./service.php" && 
		substr($file, 0 , strlen("./config.inc.php")) != "./config.inc.php" && 
		substr($file, 0 , strlen("./geoloc.log")) != "./geoloc.log" && 
        substr($file->getFilename(), 0, 1) != ".")
    {
      echo $file . "\n";
      $hashes .= md5_file($file);
    }
  }
  echo "# Hash: " . md5($hashes) . "\n";
?>
