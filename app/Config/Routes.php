<?php

namespace Config;

$routes = Services::routes();

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override('App\Controllers\Ymerror::index');
// $routes->setAutoRoute(true);  // Optional, enable if needed

// Basic routes
$routes->GET('/', 'Home::index');


$routes->group('administrator', ['filter' => 'adminauth'], function ($routes) {
    $routes->GET('/', 'administrator\Dashboard::index');
    $routes->GET('dashboard', 'administrator\Dashboard::index');

    $routes->GET('groups', 'administrator\Groups::index');
    $routes->ADD('groups/(:any)', 'administrator\Groups::$1');

    $routes->GET('users', 'administrator\Users::index');
    $routes->ADD('users/(:any)', 'administrator\Users::$1');

    $routes->GET('user', 'administrator\User::index');
    $routes->ADD('user/(:any)', 'administrator\User::$1');

    $routes->MATCH(['GET', 'POST'], 'presenters', 'administrator\Presenters::index');
    $routes->ADD('presenters/(:any)', 'administrator\Presenters::$1');

    $routes->MATCH(['GET', 'POST'], 'subscribers', 'administrator\Subscribers::index');
    $routes->ADD('subscribers/(:any)', 'administrator\Subscribers::$1');

    $routes->GET('media', 'administrator\Media::index');

    $routes->MATCH(['GET', 'POST'], 'menus', 'administrator\Menus::index');
    $routes->ADD('menus/(:any)', 'administrator\Menus::$1');


    $routes->MATCH(['GET', 'POST'], 'schedules', 'administrator\Schedules::index');
    $routes->ADD('schedules/(:any)', 'administrator\Schedules::$1');

    $routes->MATCH(['GET', 'POST'], 'teachers', 'administrator\Teachers::index');
    $routes->ADD('teachers/(:any)', 'administrator\Teachers::$1');

    $routes->MATCH(['GET', 'POST'], 'categories', 'administrator\Categories::index');
    $routes->ADD('categories/(:any)', 'administrator\Categories::$1');

    $routes->MATCH(['GET', 'POST'], 'tags', 'administrator\Tags::index');
    $routes->ADD('tags/(:any)', 'administrator\Tags::$1');

    $routes->MATCH(['GET', 'POST'], 'articles', 'administrator\Articles::index');
    $routes->ADD('articles/(:any)', 'administrator\Articles::$1');


    $routes->MATCH(['GET', 'POST'], 'contacts', 'administrator\Contacts::index');
    $routes->ADD('contacts/(:any)', 'administrator\Contacts::$1');

    $routes->MATCH(['GET', 'POST'], 'site_configurations/edit', 'administrator\SiteConfiguration::edit');

    $routes->MATCH(['GET', 'POST'], 'home_configurations/edit', 'administrator\HomeConfiguration::edit');
});


$routes->MATCH(['GET', 'POST'], 'administrator/user/login', 'administrator\User::login');

$routes->POST('clearcacheAjax', 'administrator\Dashboard::clearcacheAjax');

// ARTICLE MODEL LIST IN EDITOR IN BACKEND
$routes->MATCH(['GET', 'POST'], 'articlesModal/(:any)?', 'administrator\Articles::articles_modal/$1');

// CATEGORY MODEL LIST IN EDITOR IN BACKEND
$routes->MATCH(['GET', 'POST'], 'categoriesModal/(:any)?', 'administrator\Categories::categories_modal/$1');

// TAG MODEL LIST IN EDITOR IN BACKEND
$routes->MATCH(['GET', 'POST'], 'tagsModal/(:any)?', 'administrator\Tags::tags_modal/$1');

// GET USER AJAX CALL ROUTE 
$routes->GET('getUsersAjax', 'administrator\Users::getUsersAjax');

// ADD SUBSCRIBER AJAX CALL ROUTE 
$routes->POST('addSubscriber', 'Home::addSubscriber');

// REGISTER ROUTE 
$routes->GET(urlencode('register'), 'User::register');

// LOGIN ROUTE 
$routes->GET(urlencode('login'), 'User::login');

// PROFILE ROUTE 
$routes->GET(urlencode('profile'), 'User::profile');

// LOGOUT ROUTE 
$routes->GET(urlencode('logout'), 'User::logout');

// FORGOT PASSWORD ROUTE 
$routes->GET(urlencode('forgotpassword'), 'User::forgotpassword');

// SEARCH ROUTE 
$routes->GET(urlencode('search'), 'Search::index');
$routes->GET(urlencode('search') . '/(:segment)', 'Search::index/$1');
$routes->GET('getmoresearchedarticlesdata', 'Search::searchedarticlesAjax');
$routes->GET('getmoresearchedvideosdata', 'Search::searchedvideosAjax');

// FEATURED ARTICLES 
$routes->GET(urlencode('featuredarticles'), 'Articles::featuredarticles');
$routes->GET('getmorefeaturedarticlesdata', 'Articles::featuredarticlesAjax');

// MOSTREAD ARTICLES
$routes->GET(urlencode('mostreadarticles'), 'Articles::mostreadarticles');
$routes->GET('getmoremostreadarticlesdata', 'Articles::mostreadarticlesAjax');

// AUTHOR ARTICLES
$routes->GET(urlencode('authorarticles') . '/(:segment)', 'Articles::authorarticles/$1');
$routes->GET('getmoreauthorarticlesdata', 'Articles::authorarticlesAjax');
$routes->GET('getmorelatestvideosdata', 'Articles::latestvideosAjax');

// TAG ARTICLES
$routes->GET(urlencode('tagarticles') . '/(:segment)', 'Tags::tagarticles/$1');
$routes->GET('getmoretagarticlesdata', 'Tags::tagarticlesAjax');

// ARTICLE DETAIL PAGE
$routes->GET(urlencode('article') . '/(:segment)', 'Articles::index/$1');
$routes->GET(urlencode('article') . '/(:segment)/(:segment)', 'Articles::index/$1/$2');
$routes->GET(urlencode('article') . '/(:segment)/(:segment)/(:segment)', 'Articles::index/$1/$2/$3');

$routes->GET('getmorearticlesdata', 'Articles::articlesAjax');

$routes->GET(urlencode('addtomyfavorite') . '/(:segment)', 'Articles::addtomyfavoritelist/$1');
$routes->GET(urlencode('removefrommyfavorite') . '/(:segment)', 'Articles::removefrommyfavoritelist/$1');
$routes->GET(urlencode('favoritearticle'), 'Articles::favoritearticlelist');
$routes->GET('getmorefavoritearticlesdata', 'Articles::favoritearticlelistAjax');

// CATEGORY PAGES
$routes->GET(urlencode('category') . '/(:segment)/(:segment)', 'Articles::articles/$1/$2');
$routes->GET(urlencode('category') . '/(:segment)', 'Categories::index/$1');

// VIDEOS
$routes->GET('yawmiyati-tv', 'Videos::yawmiyati_tv');
$routes->GET('yawmiyati-tv/(:segment)', 'Videos::yawmiyati_tv/$1');
$routes->GET('getmorecategoryvideosdata', 'Videos::categoryvideosAjax');
$routes->GET('yawmiyati-tv/(:segment)/(:segment)', 'Videos::videodetails/$1/$2');

$routes->GET(urlencode('videos') . '/(:segment)', 'Videos::index/$1');
$routes->GET(urlencode('videos') . '/(:segment)/(:segment)', 'Videos::videodetails/$1/$2');

// SITE MAP ARTICLES
$routes->GET('sitemap/articles/(:segment)', 'Sitemap::articles/$1');

// PRIVACY POLICY ROUTE 
$routes->GET(urlencode('privacy-policy'), 'Staticpages::privacy_policy');

// TERMS AND CONDITIONS ROUTE 
$routes->GET(urlencode('terms-conditions'), 'Staticpages::terms_conditions');

// CONTACT US ROUTE 
$routes->GET(urlencode('contact'), 'Staticpages::contact');
$routes->POST(urlencode('contact'), 'Staticpages::contact');

// SCHEDULE ROUTE
$routes->GET(urlencode('schedule'), 'Staticpages::schedule');
$routes->GET(urlencode('horaires'), 'Staticpages::schedule');
