<?php

function tarkista_onko_kirjautunut() {
    BaseController::check_logged_in();
}

function tarkista_onko_admin() {
    BaseController::check_admin();
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

$routes->get('/chat', 'tarkista_onko_kirjautunut', function() {
    ChatController::index();
});

$routes->post('/chat', 'tarkista_onko_kirjautunut', function() {
    ChatController::uusi();
});

$routes->post('/chat/tyhjenna', 'tarkista_onko_kirjautunut', 'tarkista_onko_admin', function() {
    ChatController::tyhjenna();
});

$routes->get('/viestit', 'tarkista_onko_kirjautunut', function() {
    ViestiController::index();
});

$routes->get('/viestit/uusi', 'tarkista_onko_kirjautunut', function() {
    ViestiController::uusi(null);
});

$routes->get('/viestit/uusi/:karhuid', 'tarkista_onko_kirjautunut', function($karhuid) {
    ViestiController::uusi($karhuid);
});

$routes->post('/viestit', 'tarkista_onko_kirjautunut', function() {
    ViestiController::laheta();
});

$routes->post('/viestit/:viestiid/poista', 'tarkista_onko_kirjautunut', function($viestiid) {
    ViestiController::poista($viestiid);
});

$routes->get('/viestit/:viestiid/vastaa', 'tarkista_onko_kirjautunut', function($viestiid) {
    ViestiController::vastaa($viestiid);
});

$routes->get('/viestit/:viestiid', 'tarkista_onko_kirjautunut', function($viestiid) {
    ViestiController::nayta($viestiid);
});

$routes->get('/karhut', 'tarkista_onko_kirjautunut', function() {
    KarhuController::index();
});

$routes->post('/karhut', 'tarkista_onko_kirjautunut', 'tarkista_onko_admin', function() {
    KarhuController::lisaa();
});

$routes->get('/karhut/uusi', 'tarkista_onko_kirjautunut', 'tarkista_onko_admin', function() {
    KarhuController::uusi();
});

$routes->get('/karhut/:karhuid/muokkaa', 'tarkista_onko_kirjautunut', 'tarkista_onko_admin', function($karhuid) {
    KarhuController::muokkaa($karhuid);
});

$routes->post('/karhut/:karhuid/poista', 'tarkista_onko_kirjautunut', function($karhuid) {
    KarhuController::poista($karhuid);
});

$routes->post('/karhut/:karhuid', 'tarkista_onko_kirjautunut', 'tarkista_onko_admin', function($karhuid) {
    KarhuController::paivita($karhuid);
});

$routes->get('/karhut/:karhuid', 'tarkista_onko_kirjautunut', function($karhuid) {
    KarhuController::nayta($karhuid);
});

$routes->get('/keikat', 'tarkista_onko_kirjautunut', function() {
    KeikkaController::index(null, null);
});

$routes->get('/keikat', 'tarkista_onko_kirjautunut', function($viesti, $virhe) {
    KeikkaController::index($viesti, $virhe);
});

$routes->post('/keikat', 'tarkista_onko_kirjautunut', function() {
    KeikkaController::lisaa();
});

$routes->get('/keikat/uusi', 'tarkista_onko_kirjautunut', function() {
    KeikkaController::uusi(null);
});

$routes->get('/keikat/uusi/:kohdeid', 'tarkista_onko_kirjautunut', function($kohdeid) {
    KeikkaController::uusi($kohdeid);
});

$routes->get('/keikat/:keikkaid/ilmoittaudu', 'tarkista_onko_kirjautunut', function($keikkaid) {
    KeikkaController::ilmoittaudu($keikkaid);
});

$routes->post('/keikat/:keikkaid/ilmoittaudu', 'tarkista_onko_kirjautunut', function($keikkaid) {
    KeikkaController::lisaa_ilmoittautuminen($keikkaid);
});

$routes->post('/keikat/:keikkaid/aloita', 'tarkista_onko_kirjautunut', function($keikkaid) {
    KeikkaController::aloita($keikkaid);
});

$routes->get('/keikat/:keikkaid/kirjaa', 'tarkista_onko_kirjautunut', function($keikkaid) {
    KeikkaController::kirjaa_tulos($keikkaid);
});

$routes->post('/keikat/:keikkaid/kirjaa', 'tarkista_onko_kirjautunut', function($keikkaid) {
    KeikkaController::tallenna_tulos($keikkaid);
});

$routes->post('/keikat/:keikkaid/ilmoittautuminen/peru', 'tarkista_onko_kirjautunut', function($keikkaid) {
    KeikkaController::peru_ilmoittautuminen($keikkaid);
});

$routes->get('/keikat/:keikkaid', 'tarkista_onko_kirjautunut', function($keikkaid) {
    KeikkaController::nayta($keikkaid);
});

$routes->get('/roolit', 'tarkista_onko_kirjautunut', function() {
    RooliController::index();
});

$routes->get('/roolit/uusi', 'tarkista_onko_kirjautunut', 'tarkista_onko_admin', function() {
    RooliController::uusi();
});

$routes->post('/roolit', 'tarkista_onko_kirjautunut', 'tarkista_onko_admin', function() {
    RooliController::lisaa();
});

$routes->get('/roolit/:rooliid/muokkaa', 'tarkista_onko_kirjautunut', 'tarkista_onko_admin', function($rooliid) {
    RooliController::muokkaa($rooliid);
});

$routes->post('/roolit/:rooliid/muokkaa', 'tarkista_onko_kirjautunut', 'tarkista_onko_admin', function($rooliid) {
    RooliController::paivita($rooliid);
});

$routes->post('/roolit/:rooliid/poista', 'tarkista_onko_kirjautunut', 'tarkista_onko_admin', function($rooliid) {
    RooliController::poista($rooliid);
});

$routes->get('/roolit/:roolitid', 'tarkista_onko_kirjautunut', function($rooliid) {
    RooliController::nayta($rooliid);
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
