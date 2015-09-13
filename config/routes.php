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

$routes->get('/karhut/:id', function($id) {
    KarhuController::nayta($id);
});

$routes->get('/karhut/:id/muokkaa', function($id) {
    KarhuController::muokkaa($id);
});

$routes->get('/keikat', function() {
    KeikkaController::index();
});

$routes->get('/keikat/uusi', function() {
    KeikkaController::luo();
});

$routes->get('/keikat/:id', function($id) {
    KeikkaController::nayta($id);
});

$routes->get('/kohteet', function() {
    KohdeController::index();
});

$routes->get('/kohteet/uusi', function() {
    KohdeController::luo();
});

$routes->get('/kohteet/:id/muokkaa', function($id) {
    KohdeController::muokkaa($id);
});

$routes->get('/kohteet/:id', function($id) {
    KohdeController::nayta($id);
});

$routes->get('/tilasto', function() {
    TilastoController::index();
});


$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});
