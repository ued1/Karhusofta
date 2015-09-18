<?php

$routes->get('/', function() {
    EtusivuController::index();
});

$routes->get('/karhut', function() {
    KarhuController::index();
});

$routes->get('/karhut/uusi', function() {
    KarhuController::luo();
});

$routes->get('/karhut/:karhuid', function($karhuid) {
    KarhuController::nayta($karhuid);
});

$routes->get('/karhut/:karhuid/muokkaa', function($karhuid) {
    KarhuController::muokkaa($karhuid);
});

$routes->get('/keikat', function() {
    KeikkaController::index();
});

$routes->get('/keikat/uusi', function() {
    KeikkaController::luo();
});

$routes->get('/keikat/:keikkaid', function($keikkaid) {
    KeikkaController::nayta($keikkaid);
});

$routes->get('/kohteet', function() {
    KohdeController::index();
});

$routes->get('/kohteet/uusi', function() {
    KohdeController::luo();
});

$routes->get('/kohteet/:kohdeid/muokkaa', function($kohdeid) {
    KohdeController::muokkaa($kohdeid);
});

$routes->get('/kohteet/:kohdeid', function($kohdeid) {
    KohdeController::nayta($kohdeid);
});

$routes->get('/tilasto', function() {
    TilastoController::index();
});


$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});
