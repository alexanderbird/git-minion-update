<?php

header('Content-type: text/plain');

$config_path = "../.git-minion-update.ini";

$config = array(
  'branch' => 'master',
  'secret' => 'notverysecret',
  'redirect_to' => '..',
  'get_latest_submodules' => true,
  'path' => '/'
);

if(file_exists($config_path)) {
  $user_config = parse_ini_file($config_path);
  if($user_config) {
    $config = array_merge($config, $user_config);
  } else {
    echo "Warning: parse error in .git-minion-update.ini\nFalling back on default configuration\n\n";
  }
}

$secret = false;
if(isset($_GET['secret'])) {
  $secret = $_GET['secret'];
} elseif(isset($_POST['secret'])) {
  $secret = $_POST['secret'];
} elseif($argc > 1) {
  $secret = $argv[1];
}

if($secret == $config['secret']) {
  // work from the project root
  $dir_ok = chdir('..' . $config['path']);
  if(!$dir_ok) {
    echo "Error: Could not access path '" . $config['path'] . "'\n";
    return;
  }
  // update the git repo
  echo "Updating from " . $config['branch'] . " branch\n";
  echo shell_exec("git fetch origin " . $config['branch'] . " 2>&1 1> /dev/null");
  echo "\n";
  echo shell_exec("git reset --hard FETCH_HEAD 2>&1 1> /dev/null");
  echo "\n";
  echo shell_exec("git clean -df 2>&1 1> /dev/null");
  echo "\n";
  if($config['get_latest_submodules']) {
    echo shell_exec("git submodule foreach git pull origin master 2>&1 1> /dev/null");
  } else {
    echo shell_exec("git submodule update 2>&1 1> /dev/null");
  }
  // get new submodules, if they've been added in the most recent commit
  echo shell_exec("git submodule update --init --recursive 2>&1 1> /dev/null");
  echo "\n";

  // post-deploy hook
  $hook = "./.post-deploy";
  if(file_exists($hook)) {
    echo shell_exec($hook);
  }
} else {
  // back out to the main page
  header('Location: ' . $config['redirect_to']);
}

?>
