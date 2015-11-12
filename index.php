<?php

header('Content-type: text/plain');

$config_path = "../.git-minion-update.ini";

$config = array(
  branch => 'master',
  secret => 'notverysecret',
  redirect_to => '..'
);

if(file_exists($config_path)) {
  $user_config = parse_ini_file($config_path);
  if($user_config) {
    $config = array_merge($config, $user_config);
  } else {
    echo "Warning: parse error in .git-minion-update.ini\nFalling back on default configuration\n\n";
  }
}

if(isset($_GET['secret']) && $_GET['secret'] == $config['secret']) {
  // work from the project root
  chdir("..");
  // update the git repo
  echo "Updating from " . $config['branch'] . " branch\n";
  echo shell_exec("git fetch origin " . $config['branch']); 
  echo "\n";
  echo shell_exec("git reset --hard FETCH_HEAD");
  echo "\n";
  echo shell_exec("git clean -df");
  echo "\n";
  echo shell_exec("git submodule update");
} else {
  // back out to the main page
  header('Location: ' . $config['redirect_to']);
}

?>
