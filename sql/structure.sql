-- script de creation de la structure de la base de donnees

START TRANSACTION;
-- creation de la base
DROP DATABASE appli_hbck_bd;
CREATE DATABASE appli_hbck_bd CHARACTER SET UTF8 COLLATE utf8_general_ci ;

-- on se positionne sur la DATABASE
USE appli_hbck_bd;
SET default_storage_engine=InnoDB;

-- creation de la table Adresse
CREATE TABLE Adresse (
    adr_id INT NOT NULL AUTO_INCREMENT,
    adr_adresse VARCHAR(100) NOT NULL,
    adr_cp VARCHAR(5) NOT NULL,
    adr_ville VARCHAR(100) NOT NULL,
    adr_lieu VARCHAR(100),
    PRIMARY KEY (adr_id)
);

-- creation de la table division
CREATE TABLE Division (
    div_id int NOT NULL AUTO_INCREMENT,
    div_nom VARCHAR(50) NOT NULL,
    PRIMARY KEY (div_id)
);

-- creation de la table type_utilisateur
CREATE TABLE Fonction_utilisateur (
    fon_id int NOT NULL AUTO_INCREMENT,
    fon_libelle VARCHAR(50) NOT NULL,
    PRIMARY KEY (fon_id)
);

-- creation de la table de droits
CREATE TABLE Droit_utilisateur (
    du_id int NOT NULL AUTO_INCREMENT,
    du_libelle VARCHAR(50) NOT NULL,
    PRIMARY KEY (du_id)
);

-- creation de la table utilisateur
CREATE TABLE Utilisateur (
    uti_id INT NOT NULL AUTO_INCREMENT,
    uti_tu_id INT NOT NULL,
    uti_du_id INT NOT NULL,
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
    CONSTRAINT fk_uti_du FOREIGN KEY (uti_du_id) 
        REFERENCES Droit_utilisateur(du_id)
        ON UPDATE CASCADE ON DELETE NO ACTION
);

-- creation de la table de liaison fonction utilisateur
CREATE TABLE L_fonction_utilisateur(
    lfu_uti_id INT NOT NULL,
    lfu_fon_id INT NOT NULL,
    PRIMARY KEY (lfu_uti_id, lfu_fon_id),
    CONSTRAINT fk_lfu_uti FOREIGN KEY (lfu_uti_id)
        REFERENCES Utilisateur(uti_id)
        ON DELETE NO ACTION ON UPDATE CASCADE,
    CONSTRAINT fk_lfu_fon FOREIGN KEY (lfu_fon_id) 
        REFERENCES Fonction_utilisateur(fon_eve_id)
        ON DELETE NO ACTION ON UPDATE CASCADE
);


-- creation de la table equipe
CREATE TABLE Equipe (
    equ_id int NOT NULL AUTO_INCREMENT,
    equ_div_id INT NOT NULL,
    equ_responsable_id INT NOT NULL,
    equ_nom VARCHAR(50) NOT NULL,
    PRIMARY KEY (equ_id),
    CONSTRAINT fk_equ_div FOREIGN KEY (equ_div_id) 
        REFERENCES Division(div_id)
        ON UPDATE CASCADE ON DELETE NO ACTION,
    CONSTRAINT fk_equ_uti_resp FOREIGN KEY (equ_responsable_id) 
        REFERENCES Utilisateur(uti_id)
        ON UPDATE CASCADE ON DELETE NO ACTION
);

-- creation de la table evenement
CREATE TABLE Evenement (
    eve_id INT NOT NULL AUTO_INCREMENT,
    eve_adr_id INT NOT NULL,
    eve_date DATE NOT NULL,
    PRIMARY KEY (eve_id),
    CONSTRAINT fk_eve_adr FOREIGN KEY (eve_adr_id) 
        REFERENCES Adresse(adr_id)
        ON UPDATE CASCADE ON DELETE NO ACTION

);

-- creation de la table match_jeu
CREATE TABLE Match_jeu (
    mat_eve_id INT NOT NULL,
    mat_opposant varchar(50) NOT NULL,
    mat_resultat varchar(100),
    PRIMARY KEY (mat_eve_id),
    CONSTRAINT fk_mat_eve FOREIGN KEY (mat_eve_id) 
        REFERENCES Evenement(eve_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- creation de la table entrainement
CREATE TABLE Entrainement (
    ent_eve_id INT NOT NULL,
    ent_libele VARCHAR(50),
    ent_summary VARCHAR(200),
    PRIMARY KEY (ent_eve_id),
    CONSTRAINT fk_ent_eve FOREIGN KEY (ent_eve_id)
        REFERENCES Evenement(eve_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- creation de la table fete
CREATE TABLE Fete (
    fet_eve_id INT NOT NULL,
    fet_nom VARCHAR(100) NOT NULL,
    fet_prix_adulte INT,
    fet_prix_enfant INT,
    PRIMARY KEY (fet_eve_id),
    CONSTRAINT fk_fet_eve FOREIGN KEY (fet_eve_id) 
        REFERENCES Evenement(eve_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);
-- creation de la table de liaison entre utilisateur et equipe (membre)
CREATE TABLE L_utilisateur_equipe (
    lue_uti_id INT,
    lue_equ_id INT,
    lue_numero INT,
    PRIMARY KEY (lue_uti_id, lue_equ_id),
    CONSTRAINT fk_lue_uti FOREIGN KEY (lue_uti_id) 
        REFERENCES Utilisateur(uti_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_lue_equ FOREIGN KEY (lue_equ_id)
        REFERENCES Equipe(equ_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- creation de la table de liaison entre utilisateur et fete (a paye)
CREATE TABLE L_utilisateur_fete (
    luf_fet_id INT NOT NULL,
    luf_uti_id INT NOT NULL,
    luf_adultes INT NOT NULL,
    luf_enfant INT NOT NULL,
    PRIMARY KEY (luf_fet_id, luf_uti_id),
    CONSTRAINT fk_luf_uti FOREIGN KEY (luf_uti_id) 
        REFERENCES Utilisateur(uti_id)
        ON DELETE NO ACTION ON UPDATE CASCADE,
    CONSTRAINT fk_luf_fet FOREIGN KEY (luf_fet_id) 
        REFERENCES Fete(fet_eve_id)
        ON DELETE NO ACTION ON UPDATE CASCADE
);

-- creation de la table a_paye
CREATE TABLE L_equipe_match (
    lem_equ_id INT NOT NULL,
    lem_mat_id INT NOT NULL,
    PRIMARY KEY (lem_equ_id, lem_mat_id),
    CONSTRAINT fk_lem_equ FOREIGN KEY (lem_equ_id) 
        REFERENCES Equipe(equ_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_lem_mat FOREIGN KEY (lem_mat_id)
        REFERENCES Match_jeu(mat_eve_id)
        ON DELETE NO ACTION ON UPDATE CASCADE
);

-- creation de la table a_paye
CREATE TABLE L_equipe_entrainement (
    lee_equ_id INT NOT NULL,
    lee_ent_id INT NOT NULL,
    PRIMARY KEY (lee_equ_id, lee_ent_id),
    CONSTRAINT fk_lee_equ FOREIGN KEY (lee_equ_id)
        REFERENCES Equipe(equ_id)
        ON DELETE NO ACTION ON UPDATE CASCADE,
    CONSTRAINT fk_lee_ent FOREIGN KEY (lee_ent_id) 
        REFERENCES Entrainement(ent_eve_id)
        ON DELETE NO ACTION ON UPDATE CASCADE
);

COMMIT;
