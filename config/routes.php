<?php

function tarkista_onko_kirjautunut() {
    BaseController::check_logged_in();
}

$routes->get('/', function() {
    EtusivuController::index();
});

$routes->post('/kirjaudu', function() {
    EtusivuController::kirjaudu();
});

$routes->post('/poistu', function() {
    EtusivuController::poistu();
});

$routes->get('/karhut', 'tarkista_onko_kirjautunut', function() {
    KarhuController::index();
});

$routes->post('/karhut', 'tarkista_onko_kirjautunut', function() {
    KarhuController::lisaa();
});

$routes->get('/karhut/uusi', 'tarkista_onko_kirjautunut', function() {
    KarhuController::uusi();
});

$routes->get('/karhut/:karhuid/muokkaa', 'tarkista_onko_kirjautunut', function($karhuid) {
    KarhuController::muokkaa($karhuid);
});

$routes->post('/karhut/:karhuid/muokkaa', 'tarkista_onko_kirjautunut', function($karhuid) {
    KarhuController::paivita($karhuid);
});

$routes->post('/karhut/:karhuid/poista', 'tarkista_onko_kirjautunut', function($karhuid) {
    KarhuController::poista($karhuid);
});

$routes->get('/karhut/:karhuid', 'tarkista_onko_kirjautunut', function($karhuid) {
    KarhuController::nayta($karhuid);
});

$routes->get('/keikat', 'tarkista_onko_kirjautunut', function() {
    KeikkaController::index();
});

$routes->post('/keikat', 'tarkista_onko_kirjautunut', function() {
    KeikkaController::lisaa();
});

$routes->get('/keikat/uusi', 'tarkista_onko_kirjautunut', function() {
    KeikkaController::uusi();
});

$routes->get('/keikat/:keikkaid', 'tarkista_onko_kirjautunut', function($keikkaid) {
    KeikkaController::nayta($keikkaid);
});

$routes->get('/kohteet', 'tarkista_onko_kirjautunut', function() {
    KohdeController::index();
});

$routes->post('/kohteet', 'tarkista_onko_kirjautunut', function() {
    KohdeController::lisaa();
});

$routes->get('/kohteet/uusi', 'tarkista_onko_kirjautunut', function() {
    KohdeController::uusi();
});

$routes->get('/kohteet/:kohdeid/muokkaa', 'tarkista_onko_kirjautunut', function($kohdeid) {
    KohdeController::muokkaa($kohdeid);
});

$routes->post('/kohteet/:kohdeid/muokkaa', 'tarkista_onko_kirjautunut', function($kohdeid) {
    KohdeController::paivita($kohdeid);
});

$routes->post('/kohteet/:kohdeid/poista', 'tarkista_onko_kirjautunut', function($kohdeid) {
    KohdeController::poista($kohdeid);
});


$routes->get('/kohteet/:kohdeid', 'tarkista_onko_kirjautunut', function($kohdeid) {
    KohdeController::nayta($kohdeid);
});

$routes->get('/tilasto', 'tarkista_onko_kirjautunut', function() {
    TilastoController::index();
});


$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});
