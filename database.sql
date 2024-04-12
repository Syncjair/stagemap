CREATE DATABASE IF NOT EXISTS kassasysteem;

use kassasysteem;

CREATE TABLE IF NOT EXISTS artikel(
    Artikelnummer INT PRIMARY KEY,
    omschrijving VARCHAR(255) NOT NULL,
    leverancier VARCHAR(255) NOT NULL,
    artikelgroep VARCHAR(255) NOT NULL,
    eenheid VARCHAR(255) NOT NULL,
    prijs VARCHAR(255) NOT NULL,
    aantal INT NOT NULL
);

CREATE TABLE IF NOT EXISTS verkoop(
    id INT PRIMARY KEY AUTO_INCREMENT,
    Artikelnummer INT NOT NULL,
    aantalverkocht INT NOT NULL
);

CREATE TABLE IF NOT EXISTS functie(
    id INT PRIMARY KEY AUTO_INCREMENT,
    functie VARCHAR(255) NOT NULL, 
    rol_id INT NOT NULL
);

CREATE TABLE IF NOT EXISTS inlog(
    id INT PRIMARY KEY AUTO_INCREMENT,
    gebruikersnaam VARCHAR(255) NOT NULL,
    wachtwoord VARCHAR(255) NOT NULL,
    rol_id INT NOT NULL
);

INSERT INTO inlog (gebruikersnaam, wachtwoord, rol_id)
VALUES ('admin', 'admin', 1),
('kassamedewerker', 'kassa', 2),
('manager', 'manager', 3),
('voorraadbeheerder', 'voorraad', 4);


INSERT INTO functie (functie, rol_id)
VALUES ('administrator', 1),
('kassamedewerker', 2),
('manager', 3),
('voorraadbeheerder', 4);

INSERT INTO verkoop (Artikelnummer, aantalverkocht)
VALUES ('123456', 40),('123457', 30),('123458', 20),('123459', 10),('123460', 10),('123461', 30),
('123462', 50),('123463', 40),('123464', 39),('123465', 28),('123466', 12),('123467', 50),('123468', 16),
('123469', 10),('123470', 50),('123471', 13),('123472', 25),('123473', 13),('123474', 15),('123475', 13),
('123476', 20),('123477', 13),('123478', 17),('123479', 26),('123480', 40),('123481', 13),('123482', 15),
('123483', 30),('123484', 15),('123485', 15),('123486', 27),('123487', 30),('123488', 12),('123489', 11),
('123490', 40),('123491', 18);
