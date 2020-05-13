-- script de creation de la structure de la base de donnees
-- creation de la base
--CREATE  DATABASE IF NOT EXISTS appli-hbck_bd CHARACTER SET UTF8 COLLATE utf8_general_ci;

-- on se positionne sur la DATABASE
--USE appli-hbck_bd;

-- creation de la table des licencies
CREATE TABLE lps_Licence (
    lic_id int PRIMARY KEY,
    lic_num_structure varchar(50),
    lic_nom VARCHAR(50),
    lic_prenom VARCHAR(50),
    lic_sexe char,
    lic_numero_licence VARCHAR(20),
    lic_mention VARCHAR(30),
    lic_date_naissance DATE,
    lic_email VARCHAR(100),
    lic_mdp VARCHAR(1024),
    lic_rue VARCHAR(100),
    lic_cp VARCHAR(5),
    lic_ville VARCHAR(50),
    lic_tel_portable VARCHAR(20),
    lic_tel_domicile VARCHAR(20),
    lic_tel_bureau VARCHAR(20),
    lic_tel_resp_legal_1 VARCHAR(20),
    lic_tel_resp_legal_2 VARCHAR(20),
    lic_num_appt varchar(50),
    lic_residence varchar(50),
    lic_lieu_dit VARCHAR(50),
    lic_offrecom TINYINT
);

-- creation de la table utilisateur
CREATE TABLE lps_Utilisateur(
    uti_id int PRIMARY KEY,
    uti_nom varchar(50) NOT NULL,
    uti_prenom varchar(50) NOT NULL,
    uti_email varchar(50) NOT NULL,
    uti_login VARCHAR(50) NOT NULL,
    uti_actif TINYINT NULL,
    uti_mailconfirme TINYINT NULL, 
    uti_created_at DATETIME
);

-- creation de la table des mots de passe
CREATE TABLE lps_Identification (
    ide_id int PRIMARY KEY,
    ide_mdp text NOT NULL 
);

-- creation de la table des role
CREATE TABLE lps_Role(
    rol_id int PRIMARY KEY,
    rol_libelle varchar(50) NOT NULL
);

-- creation de la table de liens
CREATE TABLE lps_Disposer(
    dis_id int PRIMARY KEY,
    dis_uti_id int NOT NULL,
    dis_rol_id int NOT NULL,
    dis_ide_id int NULL
);

-- insertio des données de départ
-- insertion des rôles
INSERT INTO lps_Role(rol_id, rol_libelle) VALUES 
(1, "Administrateur"),
(2, "Membre du club");

-- insertion d'un utilisateur
INSERT INTO lps_Utilisateur( uti_id, uti_nom, uti_prenom, uti_email, uti_login, uti_mailconfirme, uti_created_at) VALUES 
(1, "GENETAY", "Alain", "alain.genetay@uha.fr", "alain", 0,  now());

-- ajout du role cet utilisateur
INSERT INTO lps_Disposer (dis_id, dis_uti_id, dis_rol_id) VALUES 
( 1, 1, 1);