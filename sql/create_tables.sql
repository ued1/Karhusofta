CREATE TABLE Rooli(
    rooliID SERIAL PRIMARY KEY,
    nimi varchar(20) NOT NULL,
    kuvaus varchar(120),
    vaativuuskerroin INTEGER NOT NULL,
    poistettavissa BOOLEAN DEFAULT TRUE
);

CREATE TABLE Karhu(
    karhuID SERIAL PRIMARY KEY,
    nimi varchar(20) NOT NULL,
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
    kohdeID INTEGER REFERENCES Kohde(kohdeID) NOT NULL,
    karhuID INTEGER REFERENCES Karhu(karhuID) NOT NULL
);

CREATE TABLE Tulos(
    tulosID SERIAL PRIMARY KEY,
    keikkaID INTEGER REFERENCES Keikka(keikkaID) NOT NULL,
    paiva DATE NOT NULL,
    kuvaus varchar(120) NOT NULL,
    saalis INTEGER NOT NULL
);

CREATE TABLE Osallistuminen(
    keikkaID INTEGER REFERENCES Keikka(keikkaID),
    karhuID INTEGER REFERENCES Karhu(karhuID) ON DELETE CASCADE,
    rooliID INTEGER REFERENCES Rooli(rooliID) ON DELETE SET NULL,
    PRIMARY KEY (keikkaID, karhuID)
);

CREATE TABLE Viesti(
    viestiID SERIAL PRIMARY KEY,
    aika TIMESTAMP NOT NULL,
    viesti varchar(320) NOT NULL,
    karhuID INTEGER REFERENCES Karhu(karhuID) ON DELETE CASCADE
);

