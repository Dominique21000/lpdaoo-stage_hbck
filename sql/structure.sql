-- script de creation de la structure de la base de donnees
-- creation de la base
CREATE DATABASE IF NOT EXISTS appli_hbck_bd CHARACTER SET UTF8 COLLATE utf8_general_ci;

-- on se positionne sur la DATABASE
USE appli_hbck_bd;

-- creation de la table Adresse
CREATE TABLE IF NOT EXISTS Adresse (
    adr_id int PRIMARY KEY AUTO_INCREMENT,
    adr_adresse VARCHAR(100) NOT NULL,
    adr_cp VARCHAR(5) NOT NULL,
    adr_ville VARCHAR(100) NOT NULL,
    adr_lieu VARCHAR(100)
);

-- creation de la table utilisateur
CREATE TABLE IF NOT EXISTS Utilisateur (
    uti_id INT PRIMARY KEY AUTO_INCREMENT,
    uti_nom VARCHAR(50) NOT NULL,
    uti_prenom VARCHAR(50) NOT NULL,
    uti_sexe CHAR,
    uti_numero_licence VARCHAR(20),
    uti_mention VARCHAR(30),
    uti_date_naissance DATE NOT NULL,
    uti_email VARCHAR(100) NOT NULL,
    uti_mdp VARCHAR(1024) NOT NULL,
    uti_adresse VARCHAR(100) NOT NULL,
    uti_cp VARCHAR(5) NOT NULL,
    uti_ville VARCHAR(50) NOT NULL,
    uti_lieu_dit VARCHAR(50),
    uti_tel_portable VARCHAR(20),
    uti_tel_bureau VARCHAR(20),
    uti_tel_resp_legal_1 VARCHAR(20),
    uti_tel_resp_legal_2 VARCHAR(20),
    uti_offrecom TINYINT
);

-- creation de la table equipe
CREATE TABLE IF NOT EXISTS Equipe (
    equ_id int PRIMARY KEY AUTO_INCREMENT,
    equ_div_id INT NOT NULL,
    equ_nom VARCHAR(50) NOT NULL,
    equ_res_id INT NOT NULL
);

-- creation de la table type_utilisateur
CREATE TABLE IF NOT EXISTS Type_utilisateur (
    tu_id int PRIMARY KEY AUTO_INCREMENT,
    tu_libelle VARCHAR(50) NOT NULL
);

-- creation de la table division
CREATE TABLE IF NOT EXISTS Division (
    div_id int PRIMARY KEY AUTO_INCREMENT,
    div_nom VARCHAR(50) NOT NULL
);

-- creation de la table évènement
CREATE TABLE IF NOT EXISTS Evenement (
    eve_id INT PRIMARY KEY AUTO_INCREMENT,
    eve_adr_id INT NOT NULL,
    eve_date DATE NOT NULL
);

-- creation de la table match_jeu
CREATE TABLE IF NOT EXISTS Match_jeu (
    mat_eve_id INT PRIMARY KEY,
    mat_opposant varchar(50) NOT NULL,
    mat_resultat varchar(100)
);

-- creation de la table entrainement
CREATE TABLE IF NOT EXISTS Entrainement (
    ent_eve_id INT PRIMARY KEY,
    ent_libele VARCHAR(50),
    ent_summary VARCHAR(200)
);

-- creation de la table fête
CREATE TABLE IF NOT EXISTS Fete (
    fet_eve_id INT PRIMARY KEY,
    fet_nom VARCHAR(100) NOT NULL,
    fet_prix_adulte INT,
    fet_prix_enfant INT
);
-- creation de la table être_membre
CREATE TABLE IF NOT EXISTS Etre_membre (
    em_uti_id INT,
    em_equ_id INT,
    em_numero INT,
    PRIMARY KEY (em_uti_id, em_equ_id)
);

-- creation de la table à_payé
CREATE TABLE IF NOT EXISTS A_paye (
    ap_fet_id INT,
    ap_uti_id INT,
    ap_adultes INT NOT NULL,
    ap_enfant INT NOT NULL,
    PRIMARY KEY ( ap_fet_id, ap_uti_id)
);
