<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\UserController; // Corrected namespace
use App\Controllers\Home; // Corrected namespace

/**
 * @var RouteCollection $routes
 */
$routes->get('/', [Home::class, 'index'],['filter' => 'auth']);
$routes->get('/login', [UserController::class, 'login']); 
$routes->post('/Userlogin', [UserController::class, 'UserLogin']); 
$routes->get('/register', [UserController::class, 'register']); 
$routes->post('/registration', [UserController::class, 'UserRegister']);
$routes->get('/logout', [UserController::class, 'logout']);

