CREATE TABLE Rooli(
    rooliID SERIAL PRIMARY KEY,
    nimi varchar(20) NOT NULL,
    kuvaus varchar(120),
    vaativuuskerroin INTEGER NOT NULL,
    maksimimaara INTEGER NOT NULL
);

CREATE TABLE Karhu(
    karhuID SERIAL PRIMARY KEY,
    nimi varchar(20) NOT NULL,
    tunnus varchar(20) NOT NULL,
    salasana varchar(20) NOT NULL,
    saldo INTEGER NOT NULL,
    pvm DATE,
    admin BOOLEAN DEFAULT FALSE
);

CREATE TABLE Osaaminen(
    karhuID INTEGER REFERENCES Karhu(karhuID) ON DELETE CASCADE,
    rooliID INTEGER REFERENCES Rooli(rooliID) ON DELETE CASCADE,
    PRIMARY KEY (karhuID, rooliID)
);

CREATE TABLE Kohde(
    kohdeID SERIAL PRIMARY KEY,
    nimi varchar(30) NOT NULL,
    osoite varchar(50),
    kuvaus varchar(500),
    arvo INTEGER NOT NULL
);

CREATE TABLE Keikka(
    keikkaID SERIAL PRIMARY KEY,
    nimi varchar(50) NOT NULL,
    osallistujamaara INTEGER NOT NULL,
    kohdeID INTEGER REFERENCES Kohde(kohdeID) ON DELETE SET NULL,
    karhuID INTEGER REFERENCES Karhu(karhuID) ON DELETE SET NULL,
    paikka varchar(30),
    johtaja varchar(20),
    suoritettu DATE,
    kommentti varchar(100),
    saalis INTEGER
);

CREATE TABLE Osallistuminen(
    keikkaID INTEGER REFERENCES Keikka(keikkaID) ON DELETE CASCADE,
    karhuID INTEGER REFERENCES Karhu(karhuID) ON DELETE CASCADE,
    rooliID INTEGER REFERENCES Rooli(rooliID) ON DELETE SET NULL,
    PRIMARY KEY (keikkaID, karhuID)
);

CREATE TABLE Chat(
    chatviestiID SERIAL PRIMARY KEY,
    aika TIMESTAMP NOT NULL,
    viesti varchar(320) NOT NULL,
    karhuID INTEGER REFERENCES Karhu(karhuID) ON DELETE CASCADE
);

CREATE TABLE Viesti(
    viestiID SERIAL PRIMARY KEY,
    lahetysaika TIMESTAMP NOT NULL,
    lukemisaika TIMESTAMP DEFAULT NULL,
    lahettajaID INTEGER REFERENCES Karhu(karhuID) ON DELETE CASCADE,
    saajaID INTEGER REFERENCES Karhu(karhuID) ON DELETE CASCADE,
    otsikko varchar(30),
    viesti varchar(500)
);

