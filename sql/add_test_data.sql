INSERT INTO Rooli (nimi, kuvaus, vaativuuskerroin) VALUES 
    ('Autonkuljettaja', 'Kuljettaa pakoautoa', 5),
    ('Murtoveikko', 'Murtautuu ja kerää saalisrahoja', 7),
    ('Puolustaja', 'Puolustaa muita ja taistelee vihollisia vastaan', 6),
    ('Tarkkailija', 'Tarkkailee ympäristöä ja varoittaa tarvittaessa muita', 2);

INSERT INTO Karhu (nimi, salasana, saldo) VALUES 
    ('testikarhu1', 'salasana1', 100),
    ('testikarhu2', 'salasana2', 1),
    ('testikarhu3', 'salasana3', 333),
    ('testikarhu4', 'salasana4', 666),
    ('testikarhu5', 'salasana5', 32700),
    ('testikarhu6', 'salasana6', 0),
    ('testikarhu7', 'salasana7', 1),
    ('testikarhu8', 'salasana8', 0),
    ('testikarhu9', 'salasana9', 3);

INSERT INTO Osaaminen(karhuID, rooliID) VALUES
    (1, 1),
    (1, 4),
    (2, 2),
    (3, 2),
    (4, 3),
    (5, 3),
    (6, 4),
    (7, 1);

INSERT INTO Kohde (nimi, osoite, kuvaus, arvo) VALUES 
    ('Ankkalinnan pankki', 'Ankkalinnankatu 1', 'Pankki, jossa on paljon rahaa ja vartioita 3', 100000),
    ('Roope Ankan kioski', 'Roopentie 1', NULL, 500),
    ('Hanhivaaran posti', 'Hanhitie 3', 'Huonosti vartioitu posti, jossa kaksi työntekijää', 10000),
    ('Pelle Pelottoman vaja', 'Pellentie 2', 'Ryöstettävissä kaikenlaista tavaraa', 5000);

INSERT INTO Keikka (nimi, osallistujamaara, kohdeID, karhuID) VALUES
    ('Ryöstetään Roopen kioski', 5, 2, 5),
    ('Postiryöstö hanhivaaraan', 6, 3, 2),
    ('Ankkalinnan pankkikeikka', 4, 1, 1);

INSERT INTO Osallistuminen (keikkaID, karhuID, rooliID) VALUES
    (1, 5, 3),
    (1, 1, 1),
    (1, 2, 2);

