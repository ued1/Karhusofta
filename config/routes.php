<?php

$routes->get('/', function() {
    EtusivuController::index();
});

$routes->get('/karhut', function() {
    KarhuController::index();
});

$routes->post('/karhut', function() {
    KarhuController::lisaa();
});

$routes->get('/karhut/uusi', function() {
    KarhuController::uusi();
});

$routes->get('/karhut/:karhuid/muokkaa', function($karhuid) {
    KarhuController::muokkaa($karhuid);
});

$routes->get('/karhut/:karhuid', function($karhuid) {
    KarhuController::nayta($karhuid);
});

$routes->get('/keikat', function() {
    KeikkaController::index();
});

$routes->post('/keikat', function() {
    KeikkaController::lisaa();
});

$routes->get('/keikat/uusi', function() {
    KeikkaController::uusi();
});

$routes->get('/keikat/:keikkaid', function($keikkaid) {
    KeikkaController::nayta($keikkaid);
});

$routes->get('/kohteet', function() {
    KohdeController::index();
});

$routes->post('/kohteet', function() {
    KohdeController::lisaa();
});

$routes->get('/kohteet/uusi', function() {
    KohdeController::uusi();
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
