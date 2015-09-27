# Karhusofta

* [Linkki sovellukseen (web)](http://tnokka.users.cs.helsinki.fi/karhusofta/)
* [Linkki dokumentaatioon (pdf)](https://github.com/ued1/Karhusofta/blob/master/doc/dokumentaatio.pdf)

### Kuvaus

Karhukoplalle web-sovellus järjestelmällisen rosvoamisen tehostamiseksi. Hyötyohjelman tavoitteena on tehostaa keikkojen organisointia tarjoten keikkojen ilmoittautumis- ja hallintajärjestelmä. Sovelluksessa on myös tilastot ja tiedot keikkojen onnistumisista.

### Viikko 4 palautus

CRUD toimii tietokohteille Karhu ja Kohde tietyin rajoituksin:
- karhun voi poistaa, jos se ei ole ryhmänjohtajana jollakin keikalla
- itseään ei voi poistaa
- kohteen voi poistaa jos siihen ei ole liitetty keikkaa

Validointi, virheilmoitukset ym. toimivat myös.

Pakollinen kirjautuminen on käytössä. Tunnuksia (käyttäjätunnus - salasana):
- testikarhu1 - salasana1
- testikarhu2 - salasana2
- testikarhu3 - salasana3
- ...
- testikarhu9	- salasana9
