INSERT INTO Type_utilisateur
	(tu_libelle	)
VALUES
	("membre"),("redacteur"),("administrateur");

-- insertion des donnees dans la baseName
INSERT INTO Utilisateur (
	uti_id,	uti_tu_id,	uti_nom,		uti_prenom,		uti_sexe,	uti_numero_licence,	uti_mention,
	uti_date_naissance,	uti_email,					uti_mdp,	uti_adresse,
	uti_cp,		uti_ville,		uti_tel_portable,	uti_tel_bureau,	uti_tel_resp_legal_1,
	uti_tel_resp_legal_2,	uti_lieu_dit,	uti_offrecom)
VALUES(
    null,	"SAUVIGNON",	"Dominique",	"M",		"42146M",			"Joueur 12 ans",
	"1976-03-26",		"dominique21000@gmail.com",	"mdp",		"5 Rue de Provence",
	"67300",	"SCHILTIGHEIM",	"0685400936",		"",				"",
	"",						"",				1),
	(
    null,	"DUJARDIN",		"Jean",			"M",		"34665M",			"Joueur 16 ans",
	"2002-04-21",		"toto@ifrance.com",			"mdp",		"13 rue des lilas",
	"21000",	"Dijon",		"0754657543",		"0380436754",	"0356565656",
	"0656435478",			"Commarin",		1)
;
