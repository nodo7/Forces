<?php

require __DIR__ . '/vendor/autoload.php';

function getCurrentUri() {
  $basepath = implode('/',
      array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
  $uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));
  if (FALSE !== strpos($uri, '?')) {
    $uri = substr($uri, 0, strpos($uri, '?'));
  }
  $uri = '/' . trim($uri, '/');
  return $uri;
}

$base_url = getCurrentUri();

$routes = explode('/', $base_url);
$active_route = [];
foreach ($routes as $route) {
  if (trim($route) !== '') {
    $active_route[] = $route;
  }
}

$actions = new \CST\Actions();
$res = [];
if (isset($active_route[0])) {
  if (!isset($active_route[1])) {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $res = $actions->getAll($active_route[0]);
    }
    else {
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $res = $actions->create($active_route[0], $data);
      }
    }
  }
  else {
    $res = $actions->getOne($active_route[0], $active_route[1]);
  }
}

header('Content-Type: application/json');
echo json_encode($res);

