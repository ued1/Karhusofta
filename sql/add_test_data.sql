INSERT INTO Rooli (nimi, kuvaus, vaativuuskerroin, maksimimaara) VALUES
    ('Jokapaikanhöylä', 'Tekee kaikenlaista', 1, 50),
    ('Autonkuljettaja', 'Kuljettaa pakoautoa', 5, 2),
    ('Murtoveikko', 'Murtautuu ja kerää saalisrahoja', 7, 10),
    ('Puolustaja', 'Puolustaa muita ja taistelee vihollisia vastaan', 4, 2),
    ('Tarkkailija', 'Tarkkailee ympäristöä ja varoittaa tarvittaessa muita', 3, 1),
    ('Soluttautuja', 'Soluttautuu kohteeseen valeasussa', 3, 1),
    ('Välinemestari', 'Hankkii, huoltaa ja huolehtii tarvittavista välineistä', 3, 1);

INSERT INTO Karhu (nimi, salasana, saldo, pvm, admin) VALUES
    ('karhumuori', 'salasana', 0, '2000-1-1', true),
    ('testikarhu1', 'salasana1', 100, '2014-11-1', false),
    ('testikarhu2', 'salasana2', 1, '2015-3-1', false),
    ('testikarhu3', 'salasana3', 333, '2015-8-25', false),
    ('testikarhu4', 'salasana4', 666, '1999-12-22', false),
    ('testikarhu5', 'salasana5', 32700, '2013-1-1', false),
    ('testikarhu6', 'salasana6', 0, '2014-11-1', false),
    ('testikarhu7', 'salasana7', 1, null, false),
    ('testikarhu8', 'salasana8', 0, '2014-11-1', false),
    ('testikarhu9', 'salasana9', 3, '2014-11-1', false);

INSERT INTO Osaaminen(karhuID, rooliID) VALUES
    (2, 1),
    (2, 2),
    (2, 3),
    (2, 4),
    (3, 2),
    (3, 6),
    (4, 3),
    (5, 1),
    (5, 3),
    (6, 4),
    (6, 7),
    (7, 5),
    (7, 6),
    (8, 1),
    (8, 2),
    (8, 4),
    (8, 5);

INSERT INTO Kohde (nimi, osoite, kuvaus, arvo) VALUES 
    ('Ankkalinnan pankki', 'Ankkalinnankatu 1', 'Pankki, jossa on paljon rahaa ja vartioita 3', 100000),
    ('Roope Ankan kioski', 'Roopentie 1', NULL, 500),
    ('Hanhivaaran posti', 'Hanhitie 3', 'Huonosti vartioitu posti, jossa kaksi työntekijää', 10000),
    ('Pelle Pelottoman vaja', 'Pellentie 2', 'Ryöstettävissä kaikenlaista tavaraa', 5000),
    ('Iineksen kampaamo', 'Kampaamokatu 4', 'Aika vähän pöllittävää', 100),
    ('Hessun autokorjaamo', 'Kaasutie 1', 'Rikkinäisiä vanhoja autoja', 5000),
    ('Mikin venevuokraamo', 'Satamakatu', NULL, 100000),
    ('Ankkalinnan huoltoasema', 'Bensatie 3', NULL, 3000),
    ('Rahankuljetusauto', NULL, 'Liikkuva kohde, aseellisesti vartioitu', 1000000),
    ('Pankkiautomaatti 2', 'Ankkalinnan ostari', NULL, 10000),
    ('Pajaluokka', 'Exactum', 'Paljon vanhoja tietokoneita', 1000);

INSERT INTO Keikka (nimi, osallistujamaara, kohdeID, karhuID, suoritettu, kommentti, saalis, paikka, johtaja) VALUES
    ('Ryöstetään Roopen kioski', 5, 2, 5, NULL, NULL, NULL, NULL, NULL),
    ('Postiryöstö hanhivaaraan', 6, 3, 2, NULL, NULL, NULL, NULL, NULL),
    ('Ankkalinnan pankkikeikka', 4, 1, 1, NULL, NULL, NULL, NULL, NULL),
    ('Isku venevuokraamoon', 5, 7, 1, NULL, NULL, NULL, NULL, NULL),
    ('Keikka pajaluokkaan', 10, 8, 3, NULL, NULL, NULL, NULL, NULL),
    ('Vanhan pankin ryöstö', 5, null, null, '2001-11-2', 'Hyvin meni!', 50000, 'Vanha pankki', 'Eläkeläinen'),
    ('Roopen rahasäiliön tyhjennys', 50, null, null, '2002-1-2', 'Kaikki jänistivät', 0, 'Roopen rahasäiliö', 'Erotettukarhu');

INSERT INTO Osallistuminen (keikkaID, karhuID, rooliID) VALUES
    (1, 3, 3),
    (1, 1, 1),
    (1, 2, 2),
    (1, 4, 4),
    (2, 1, 5),
    (2, 6, 1);

