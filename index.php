<?php

$branch = "master";

if(isset($_GET['secret']) && $_GET['secret'] == '<add secret string here>') {
  echo "Updating from " . $branch . " branch\n";
  echo shell_exec("git fetch origin " . $branch); 
  echo shell_exec("git reset --hard FETCH_HEAD");
  echo shell_exec("git clean -df");
} else {
 // TODO: redirect to root
}

?>
