-- script de creation de la structure de la base de donnees
-- creation de la base
--CREATE  DATABASE IF NOT EXISTS appli-hbck_bd CHARACTER SET UTF8 COLLATE utf8_general_ci;

-- on se positionne sur la DATABASE
--USE appli-hbck_bd;

-- creation de la table user
CREATE TABLE Utilisateur (
    uti_id int PRIMARY KEY AUTO_INCREMENT,
    uti_nom VARCHAR(50),
    uti_prenom VARCHAR(50),
    uti_sexe char,
    uti_numero_licence VARCHAR(20),
    uti_mention VARCHAR(30),
    uti_date_naissance DATE,
    uti_email VARCHAR(100),
    uti_mdp VARCHAR(1024),
    uti_adresse VARCHAR(100),
    uti_cp VARCHAR(5),
    uti_ville VARCHAR(50),
    uti_tel_portable VARCHAR(20),
    uti_tel_bureau VARCHAR(20),
    uti_tel_resp_legal_1 VARCHAR(20),
    uti_tel_resp_legal_2 VARCHAR(20),
    uti_lieu_dit VARCHAR(50),
    uti_offrecom TINYINT
);
