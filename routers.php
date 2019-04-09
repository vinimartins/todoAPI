<?php
global $routes;
$routes = array();

$routes['/create'] = '/home/create';
$routes['/{token}/info'] = '/home/info/:token';
$routes['/{token}/{id}'] = '/home/index/:token/:id'; // GET/PUT/DELETE
$routes['/{token}'] = '/home/index/:token'; // GET/POST