-- script de creation de la structure de la base de donnees
-- creation de la base
CREATE DATABASE IF NOT EXISTS appli_hbck_bd CHARACTER SET UTF8 COLLATE utf8_general_ci ;

-- on se positionne sur la DATABASE
USE appli_hbck_bd;

-- creation de la table Adresse
CREATE TABLE IF NOT EXISTS Adresse (
    adr_id INT NOT NULL AUTO_INCREMENT,
    adr_adresse VARCHAR(100) NOT NULL,
    adr_cp VARCHAR(5) NOT NULL,
    adr_ville VARCHAR(100) NOT NULL,
    adr_lieu VARCHAR(100),
    PRIMARY KEY (adr_id)
);

-- creation de la table division
CREATE TABLE IF NOT EXISTS Division (
    div_id int NOT NULL AUTO_INCREMENT,
    div_nom VARCHAR(50) NOT NULL,
    PRIMARY KEY (div_id)
);

-- creation de la table type_utilisateur
CREATE TABLE IF NOT EXISTS Type_utilisateur (
    tu_id int NOT NULL AUTO_INCREMENT,
    tu_libelle VARCHAR(50) NOT NULL,
    PRIMARY KEY (tu_id)
);

-- creation de la table utilisateur
CREATE TABLE IF NOT EXISTS Utilisateur (
    uti_id INT NOT NULL AUTO_INCREMENT,
    uti_tu_id INT NOT NULL,
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
    uti_offrecom TINYINT,
    PRIMARY KEY (uti_id),
    FOREIGN KEY (uti_tu_id) REFERENCES Type_utilisateur(tu_id)
);

-- creation de la table equipe
CREATE TABLE IF NOT EXISTS Equipe (
    equ_id int NOT NULL AUTO_INCREMENT,
    equ_div_id INT NOT NULL,
    equ_responsable_id INT NOT NULL,
    equ_nom VARCHAR(50) NOT NULL,
    PRIMARY KEY (equ_id),
    FOREIGN KEY (equ_div_id) REFERENCES Division(div_id),
    FOREIGN KEY (equ_responsable_id) REFERENCES Utilisateur(uti_id)
);

-- creation de la table evenement
CREATE TABLE IF NOT EXISTS Evenement (
    eve_id INT NOT NULL AUTO_INCREMENT,
    eve_adr_id INT NOT NULL,
    eve_date DATE NOT NULL,
    PRIMARY KEY (eve_id),
    FOREIGN KEY (eve_adr_id) REFERENCES Adresse(adr_id)

);

-- creation de la table match_jeu
CREATE TABLE IF NOT EXISTS Match_jeu (
    mat_eve_id INT NOT NULL,
    mat_opposant varchar(50) NOT NULL,
    mat_resultat varchar(100),
    PRIMARY KEY (mat_eve_id),
    FOREIGN KEY (mat_eve_id) REFERENCES Evenement(eve_id)
);

-- creation de la table entrainement
CREATE TABLE IF NOT EXISTS Entrainement (
    ent_eve_id INT NOT NULL,
    ent_libele VARCHAR(50),
    ent_summary VARCHAR(200),
    PRIMARY KEY (ent_eve_id),
    FOREIGN KEY (ent_eve_id) REFERENCES Evenement(eve_id)
);

-- creation de la table fete
CREATE TABLE IF NOT EXISTS Fete (
    fet_eve_id INT NOT NULL,
    fet_nom VARCHAR(100) NOT NULL,
    fet_prix_adulte INT,
    fet_prix_enfant INT,
    PRIMARY KEY (fet_eve_id),
    FOREIGN KEY (fet_eve_id) REFERENCES Evenement(eve_id)
);
-- creation de la table de liaison entre utilisateur et equipe (membre)
CREATE TABLE IF NOT EXISTS L_utilisateur_equipe (
    lue_uti_id INT,
    lue_equ_id INT,
    lue_numero INT,
    PRIMARY KEY (lue_uti_id, em_equ_id),
    FOREIGN KEY (lue_uti_id) REFERENCES Utilisateur(uti_id),
    FOREIGN KEY (lue_equ_id) REFERENCES Equipe(equ_id)

);

-- creation de la table de liaison entre utilisateur et fete (a paye)
CREATE TABLE IF NOT EXISTS L_utilisateur_fete (
    luf_fet_id INT NOT NULL,
    luf_uti_id INT NOT NULL,
    luf_adultes INT NOT NULL,
    luf_enfant INT NOT NULL,
    PRIMARY KEY (luf_fet_id, ap_uti_id),
    FOREIGN KEY (luf_uti_id) REFERENCES Utilisateur(uti_id),
    FOREIGN KEY (luf_fet_id) REFERENCES Fete(fet_eve_id)
);

-- creation de la table a_paye
CREATE TABLE IF NOT EXISTS L_equipe_match (
    lem_equ_id INT NOT NULL,
    lem_mat_id INT NOT NULL,
    PRIMARY KEY (lem_equ_id, lem_mat_id),
    FOREIGN KEY (lem_equ_id) REFERENCES Equipe(equ_id),
    FOREIGN KEY (lem_mat_id) REFERENCES Match_jeu(mat_eve_id)
);
-- creation de la table a_paye
CREATE TABLE IF NOT EXISTS L_equipe_entrainement (
    lee_equ_id INT NOT NULL,
    lee_ent_id INT NOT NULL,
    PRIMARY KEY (lee_equ_id, lee_mat_id),
    FOREIGN KEY (lee_equ_id) REFERENCES Equipe(equ_id),
    FOREIGN KEY (lee_ent_id) REFERENCES Entrainement(ent_eve_id)
);
