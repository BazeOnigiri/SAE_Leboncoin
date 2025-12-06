/*===========================================================================================*/
/* Nom de SGBD :  PostgreSQL 8                                                               */
/* Date de création :  12/11/2025 15:09:41                                                   */
/* Derniere modification : ajout des incidents et regroupement des types 19/11/2025          */
/*===========================================================================================*/

/*===========================================================================================*/
/*===========================================================================================*/
/* SUPPRESSION DES ANCIENNES TABLES                                                          */
/*===========================================================================================*/
/*===========================================================================================*/

drop table if exists adresse CASCADE;
drop table if exists annonce CASCADE;
drop table if exists avis CASCADE;
drop table if exists cartebancaire CASCADE;
drop table if exists chambre CASCADE;
drop table if exists cibler CASCADE;
drop table if exists date CASCADE;
drop table if exists departement CASCADE;
drop table if exists disposer CASCADE;
drop table if exists favoriser CASCADE;
drop table if exists filtrer CASCADE;
drop table if exists heure CASCADE;
drop table if exists inclure CASCADE;
drop table if exists message CASCADE;
drop table if exists particulier CASCADE;
drop table if exists photo CASCADE;
drop table if exists professionnel CASCADE;
drop table if exists proposer CASCADE;
drop table if exists recherche CASCADE;
drop table if exists region CASCADE;
drop table if exists relier CASCADE;
drop table if exists reservation CASCADE;
drop table if exists transaction CASCADE;
drop table if exists typeequipement CASCADE;
drop table if exists typeexterieur CASCADE;
drop table if exists typehebergement CASCADE;
drop table if exists typeservice CASCADE;
drop table if exists typevoyageur CASCADE;
drop table if exists utilisateur CASCADE;
drop table if exists ville CASCADE;
drop table if exists incident CASCADE;
drop table if exists demander CASCADE;
drop table if exists compensation CASCADE;
drop table if exists ressembler CASCADE;
drop table if exists commodite CASCADE;
drop table if exists categorie CASCADE;


/*===========================================================================================*/
/*===========================================================================================*/
/* CREATION DES TABLES                                                                       */
/*===========================================================================================*/
/*===========================================================================================*/

/*==============================================================*/
/* Table : adresse                                              */
/*==============================================================*/
create table adresse (
   idadresse            serial               not null,
   idville              int4                 not null,
   numerorue            int4                 null,
   nomrue               varchar(39)          null,
   constraint pk_adresse primary key (idadresse)
);

/*==============================================================*/
/* Table : annonce                                              */
/*==============================================================*/
create table annonce (
   idannonce            serial               not null,
   idadresse            int4                 not null,
   iddate               int4                 not null,
   idheuredepart        int4                 not null,
   idtypehebergement    int4                 not null,
   idheurearrivee       int4                 not null,
   idutilisateur        int4                 not null,
   titreannonce         varchar(50)          not null,
   descriptionannonce   varchar(4000)        not null,
   nombreetoilesleboncoin int4               null,
   montantacompte       decimal(10,2)        null,
   pourcentageacompte   int4                 null,
   prixnuitee           decimal(10,2)        not null,
   capacite             int4                 null,
   nbChambres           int4                 null,
   minimumnuitee        int4                 null,
   nombreanimauxmax     int4                 null,
   nombrebebesmax       int4                 null,
   possibilitefumeur    bool                 not null,
   constraint pk_annonce primary key (idannonce)
);

/*==============================================================*/
/* Table : demander                                       */
/*==============================================================*/
create table demander (
   idincident           int4                 not null,
   idcompensation       int4                 not null,
   constraint pk_demander primary key (idincident, idcompensation)
);

/*==============================================================*/
/* Table : avis                                                 */
/*==============================================================*/
create table avis (
   idavis               serial               not null,
   idannonce            int4                 not null,
   iddate               int4                 not null,
   idutilisateur        int4                 not null,
   texteavis            varchar(500)         null,
   nombreetoiles        decimal(2,1)         not null,
   constraint pk_avis primary key (idavis)
);

/*==============================================================*/
/* Table : cartebancaire                                        */
/*==============================================================*/
create table cartebancaire (
   idcartebancaire      serial               not null,
   idutilisateur        int4                 not null,
   nomtitulaire         varchar(50)          null,
   prenomtitulaire      varchar(50)          null,
   numerocartebancaire  numeric(16)          null,
   dateexpiration       date                 null,
   numerocvv            numeric(3)           null,
   constraint pk_cartebancaire primary key (idcartebancaire)
);

/*==============================================================*/
/* Table : categorie                                            */
/*==============================================================*/
create table categorie (
   idcategorie               serial               not null,
   nomcategorie         varchar(24)          null,
   constraint pk_categorie primary key (idcategorie)
);

/*==============================================================*/
/* Table : cibler                                               */
/*==============================================================*/
create table cibler (
   idtypehebergement    int4                 not null,
   idrecherche          int4                 not null,
   constraint pk_cibler primary key (idtypehebergement, idrecherche)
);

/*==============================================================*/
/* Table : commodite                                            */
/*==============================================================*/
create table commodite (
   idcommodite          serial               not null,
   idcategorie               int4                 not null,
   nomcommodite         varchar(50)          null,
   constraint pk_commodite primary key (idcommodite)
);

/*==============================================================*/
/* Table : compensation                                         */
/*==============================================================*/
create table compensation (
   idcompensation       serial               not null,
   nomcompensation      varchar(100)          null,
   constraint pk_compensation primary key (idcompensation)
);

/*==============================================================*/
/* Table : date                                                 */
/*==============================================================*/
create table date (
   iddate               serial               not null,
   date                 date                 null,
   constraint pk_date primary key (iddate)
);

/*==============================================================*/
/* Table : departement                                          */
/*==============================================================*/
create table departement (
   iddepartement        serial               not null,
   idregion             int4                 not null,
   numerodepartement    varchar(3)           null,
   nomdepartement       varchar(25)          null,
   constraint pk_departement primary key (iddepartement)
);

/*==============================================================*/
/* Table : favoriser                                            */
/*==============================================================*/
create table favoriser (
   idutilisateur        int4                 not null,
   idannonce            int4                 not null,
   constraint pk_favoriser primary key (idutilisateur, idannonce)
);

/*==============================================================*/
/* Table : filtrer                                              */
/*==============================================================*/
create table filtrer (
   idrecherche          int4                 not null,
   idcommodite          int4                 not null,
   constraint pk_filtrer primary key (idrecherche, idcommodite)
);

/*==============================================================*/
/* Table : heure                                                */
/*==============================================================*/
create table heure (
   idheure              serial               not null,
   heure                time                 not null,
   constraint pk_heure primary key (idheure)
);

/*==============================================================*/
/* Table : incident                                             */
/*==============================================================*/
create table incident (
   idincident           serial               not null,
   idutilisateur        int4                 not null,
   idreservation       int4                 not null,
   iddate               int4                 not null,
   motifincident        varchar(100)         null,
   descriptionincident  varchar(2000)        null,
   constraint pk_incident primary key (idincident)
);

/*==============================================================*/
/* Table : inclure                                              */
/*==============================================================*/
create table inclure (
   idreservation       int4                  not null,
   idtypevoyageur       int4                 not null,
   nombrevoyageur       int4                 not null,
   constraint pk_inclure primary key (idreservation, idtypevoyageur)
);

/*==============================================================*/
/* Table : message                                              */
/*==============================================================*/
create table message (
   idmessage				  serial               not null,
   idutilisateurreceveur	  int4                 not null,
   iddate					  int4                 not null,
   idutilisateurexpediteur    int4                 not null,
   contenumessage       varchar(1000)              not null,
   constraint pk_message primary key (idmessage)
);

/*==============================================================*/
/* Table : particulier                                          */
/*==============================================================*/
create table particulier (
   idutilisateur        int4                 not null,
   civilite				   varchar(15)			   not null,
   iddate               int4                 not null, 
   constraint pk_particulier primary key (idutilisateur)
);

/*==============================================================*/
/* Table : photo                                                */
/*==============================================================*/
create table photo (
   idphoto              serial               not null,
   idannonce            int4                 null,
   idincident           int4                 null,
   lienphoto            varchar(200)         null,
   constraint pk_photo primary key (idphoto)
);

/*==============================================================*/
/* Table : professionnel                                        */
/*==============================================================*/
create table professionnel (
   idutilisateur        int4                 not null,
   numsiret             numeric(14)          not null unique,
   nomsociete           varchar(30)          not null,
   secteuractivite      varchar(50)          not null,
   constraint pk_professionnel primary key (idutilisateur)
);

/*==============================================================*/
/* Table : proposer                                             */
/*==============================================================*/
create table proposer (
   idcommodite          int4                 not null,
   idannonce            int4                 not null,
   constraint pk_proposer primary key (idcommodite, idannonce)
);

/*==============================================================*/
/* Table : recherche                                            */
/*==============================================================*/
create table recherche (
   idrecherche             serial               not null,
   idutilisateur           int4                 not null,
   idville                 int4                 null,
   iddatefinrecherche      int4                 null,	
   iddepartement           int4                 null,
   idregion                int4                 null,
   iddatedebutrecherche    int4                 null,
   paiementenligne         BOOLEAN              NOT NULL,
   capaciteminimumvoyageur int4                 null,
   prixminimum             decimal(10,2)        null,
   prixmaximum             decimal(10,2)        null,
   nombreminimumchambre    int4                 null,
   nombremaximumchambre    int4                 null,
   constraint pk_recherche primary key (idrecherche)
);

/*==============================================================*/
/* Table : region                                               */
/*==============================================================*/
create table region (
   idregion             serial               not null,
   nomregion            varchar(30)          not null unique,
   constraint pk_region primary key (idregion)
);

/*==============================================================*/
/* Table : relier                                               */
/*==============================================================*/
create table relier (
   idannonce            int4                 not null,
   iddate               int4                 not null,
   estdisponible        bool                 not null,
   constraint pk_relier primary key (idannonce, iddate)
);

/*==============================================================*/
/* Table : reservation                                          */
/*==============================================================*/
create table reservation (
   idreservation       serial                not null,
   idannonce            int4                 not null,
   iddatedebutreservation int4               not null,
   iddatefinreservation int4                 not null,
   idutilisateur        int4                 not null,
   nomclient            varchar(50)          not null,
   prenomclient         varchar(50)          not null,
   telephoneclient      char(10)             null,
   constraint pk_reservation primary key (idreservation)
);

/*==============================================================*/
/* Table : ressembler                                           */
/*==============================================================*/
create table ressembler (
   idannonce_a        int4                 not null,
   idannonce_b        int4                 not null,
   constraint pk_ressembler primary key (idannonce_a, idannonce_b)
);

/*==============================================================*/
/* Table : transaction                                          */
/*==============================================================*/
create table transaction (
   idtransaction        serial               not null,
   iddate               int4                 not null,
   idreservation        int4                 not null,
   idcartebancaire      int4                 not null,
   montanttransaction   decimal(10,2)        not null,
   constraint pk_transaction primary key (idtransaction)
);

/*==============================================================*/
/* Table : typehebergement                                      */
/*==============================================================*/
create table typehebergement (
   idtypehebergement    serial               not null,
   idcategorie               int4            not null,
   nomtypehebergement   varchar(30)          null,
   constraint pk_typehebergement primary key (idtypehebergement)
);

/*==============================================================*/
/* Table : typevoyageur                                         */
/*==============================================================*/
create table typevoyageur (
   idtypevoyageur       serial               not null,
   nomtypevoyageur      varchar(30)          not null unique,
   constraint pk_typevoyageur primary key (idtypevoyageur)
);

/*==============================================================*/
/* Table : utilisateur                                          */
/*==============================================================*/
CREATE TABLE utilisateur (
   idutilisateur             SERIAL          NOT NULL,
   idphoto                   INT4            NULL,
   idadresse                 INT4            NOT NULL,
   idcartebancaire           INT4            NULL,
   iddate                    INT4            NOT NULL,

   nomutilisateur            VARCHAR(50)     NOT NULL,
   prenomutilisateur         VARCHAR(50)     NOT NULL,
   pseudonyme                VARCHAR(50)     NOT NULL,
   email                     VARCHAR(320)    NOT NULL UNIQUE,
   email_verified_at         TIMESTAMP       NULL,
   password                  VARCHAR(255)    NOT NULL,
   telephoneutilisateur      CHAR(10)        NULL,
   phone_verified            BOOLEAN         NOT NULL,
   identity_verified         BOOLEAN         NOT NULL,
   solde                     DECIMAL(10,2)   NOT NULL,

   -- Jetstream / 2FA
   remember_token            VARCHAR(100)    NULL,
   two_factor_secret         TEXT            NULL,
   two_factor_recovery_codes TEXT            NULL,
   two_factor_confirmed_at   TIMESTAMP       NULL,

   CONSTRAINT pk_utilisateur PRIMARY KEY (idutilisateur)
);

/*==============================================================*/
/* Table : ville                                                */
/*==============================================================*/
create table ville (
   idville              serial               not null,
   iddepartement        int4                 not null,
   codepostal           char(5)				   not null,
   nomville             varchar(40)          not null unique,
   taxedesejour         decimal(10,2)        not null,
   constraint pk_ville primary key (idville)
);


/*===========================================================================================*/
/*===========================================================================================*/
/* CREATION DES CLES ETRANGERES                                                              */
/*===========================================================================================*/
/*===========================================================================================*/

alter table adresse
   add constraint fk_adresse_posseder_ville foreign key (idville)
      references ville (idville)
      on delete restrict on update restrict;

alter table annonce
   add constraint fk_annonce_arriver_heure foreign key (idheurearrivee)
      references heure (idheure)
      on delete restrict on update restrict;

alter table annonce
   add constraint fk_annonce_diffuser_utilisateur foreign key (idutilisateur)
      references utilisateur (idutilisateur)
      on delete restrict on update restrict;

alter table annonce
   add constraint fk_annonce_partir_heure foreign key (idheuredepart)
      references heure (idheure)
      on delete restrict on update restrict;

alter table annonce
   add constraint fk_annonce_publier_date foreign key (iddate)
      references date (iddate)
      on delete restrict on update restrict;

alter table annonce
   add constraint fk_annonce_qualifier_typehebe foreign key (idtypehebergement)
      references typehebergement (idtypehebergement)
      on delete restrict on update restrict;

alter table annonce
   add constraint fk_annonce_se_trouve_adresse foreign key (idadresse)
      references adresse (idadresse)
      on delete restrict on update restrict;

alter table demander
   add constraint fk_demander_incident foreign key (idincident)
      references incident (idincident)
      on delete restrict on update restrict;

alter table demander
   add constraint fk_demander_compensation foreign key (idcompensation)
      references compensation (idcompensation)
      on delete restrict on update restrict;

alter table avis
   add constraint fk_avis_commenter_utilisateur foreign key (idutilisateur)
      references utilisateur (idutilisateur)
      on delete restrict on update restrict;

alter table avis
   add constraint fk_avis_deposer_date foreign key (iddate)
      references date (iddate)
      on delete restrict on update restrict;

alter table avis
   add constraint fk_avis_noter_annonce foreign key (idannonce)
      references annonce (idannonce)
      on delete restrict on update restrict;

alter table cartebancaire
   add constraint fk_carteban_enregistr_utilisat foreign key (idutilisateur)
      references utilisateur (idutilisateur)
      on delete restrict on update restrict;

alter table cibler
   add constraint fk_cibler_cibler_typehebe foreign key (idtypehebergement)
      references typehebergement (idtypehebergement)
      on delete restrict on update restrict;

alter table cibler
   add constraint fk_cibler_cibler2_recherch foreign key (idrecherche)
      references recherche (idrecherche)
      on delete restrict on update restrict;

alter table commodite
   add constraint fk_commodit_apparteni_categori foreign key (idcategorie)
      references categorie (idcategorie)
      on delete restrict on update restrict;

alter table departement
   add constraint fk_departem_localiser_region foreign key (idregion)
      references region (idregion)
      on delete restrict on update restrict;

alter table favoriser
   add constraint fk_favorise_favoriser_utilisateur foreign key (idutilisateur)
      references utilisateur (idutilisateur)
      on delete restrict on update restrict;

alter table favoriser
   add constraint fk_favorise_favoriser_annonce foreign key (idannonce)
      references annonce (idannonce)
      on delete restrict on update restrict;

alter table filtrer
   add constraint fk_filtrer_filtrer_recherch foreign key (idrecherche)
      references recherche (idrecherche)
      on delete restrict on update restrict;

alter table filtrer
   add constraint fk_filtrer_filtrer2_commodit foreign key (idcommodite)
      references commodite (idcommodite)
      on delete restrict on update restrict;

alter table incident
   add constraint fk_incident_associati_utilisat foreign key (idutilisateur)
      references utilisateur (idutilisateur)
      on delete restrict on update restrict;

alter table incident
   add constraint fk_incident_associati_reservat foreign key (idreservation)
      references reservation (idreservation)
      on delete restrict on update restrict;

alter table incident
   add constraint fk_incident_associati_date foreign key (iddate)
      references date (iddate)
      on delete restrict on update restrict;

alter table inclure
   add constraint fk_inclure_inclure_reservat foreign key (idreservation)
      references reservation (idreservation)
      on delete restrict on update restrict;

alter table inclure
   add constraint fk_inclure_inclure2_typevoya foreign key (idtypevoyageur)
      references typevoyageur (idtypevoyageur)
      on delete restrict on update restrict;

alter table message
   add constraint fk_message_associati_date foreign key (iddate)
      references date (iddate)
      on delete restrict on update restrict;

alter table message
   add constraint fk_message_expedier_utilisat foreign key (idutilisateurexpediteur)
      references utilisateur (idutilisateur)
      on delete restrict on update restrict;

alter table message
   add constraint fk_message_recevoir_utilisat foreign key (idutilisateurreceveur)
      references utilisateur (idutilisateur)
      on delete restrict on update restrict;

alter table particulier
   add constraint fk_particulier_heritage__utilisateur foreign key (idutilisateur)
      references utilisateur (idutilisateur)
      on delete restrict on update restrict;

alter table particulier
   add constraint fk_particulier_naitre_date foreign key (iddate)
      references date (iddate)
      on delete restrict on update restrict;

alter table photo
   add constraint fk_photo_prouver_incident foreign key (idincident)
      references incident (idincident)
      on delete restrict on update restrict;

alter table photo
   add constraint fk_photo_comporter_annonce foreign key (idannonce)
      references annonce (idannonce)
      on delete restrict on update restrict;

alter table professionnel
   add constraint fk_professionnel_heritage__utilisateur foreign key (idutilisateur)
      references utilisateur (idutilisateur)
      on delete restrict on update restrict;

alter table proposer
   add constraint fk_proposer_proposer_commodit foreign key (idcommodite)
      references commodite (idcommodite)
      on delete restrict on update restrict;

alter table proposer
   add constraint fk_proposer_proposer2_annonce foreign key (idannonce)
      references annonce (idannonce)
      on delete restrict on update restrict;

alter table recherche
   add constraint fk_recherch_associati_utilisat foreign key (idutilisateur)
      references utilisateur (idutilisateur)
      on delete restrict on update restrict;

alter table recherche
   add constraint fk_recherch_associer_ville foreign key (idville)
      references ville (idville)
      on delete restrict on update restrict;

alter table recherche
   add constraint fk_recherch_commencer_date foreign key (iddatedebutrecherche)
      references date (iddate)
      on delete restrict on update restrict;

alter table recherche
   add constraint fk_recherch_connecter_departem foreign key (iddepartement)
      references departement (iddepartement)
      on delete restrict on update restrict;

alter table recherche
   add constraint fk_recherch_lier_region foreign key (idregion)
      references region (idregion)
      on delete restrict on update restrict;

alter table recherche
   add constraint fk_recherch_terminer_date foreign key (iddatefinrecherche)
      references date (iddate)
      on delete restrict on update restrict;

alter table relier
   add constraint fk_relier_relier_annonce foreign key (idannonce)
      references annonce (idannonce)
      on delete restrict on update restrict;

alter table relier
   add constraint fk_relier_relier2_date foreign key (iddate)
      references date (iddate)
      on delete restrict on update restrict;

alter table reservation
   add constraint fk_reservat_concerner_annonce foreign key (idannonce)
      references annonce (idannonce)
      on delete restrict on update restrict;

alter table reservation
   add constraint fk_reservat_debuter_date foreign key (iddatedebutreservation)
      references date (iddate)
      on delete restrict on update restrict;

alter table reservation
   add constraint fk_reservat_finir_date foreign key (iddatefinreservation)
      references date (iddate)
      on delete restrict on update restrict;

alter table reservation
   add constraint fk_reservation_reserver_utilisateur foreign key (idutilisateur)
      references utilisateur (idutilisateur)
      on delete restrict on update restrict;

alter table ressembler
   add constraint fk_ressembler_idannonce_a foreign key (idannonce_a)
      references annonce (idannonce)
      on delete restrict on update restrict;

alter table ressembler
   add constraint fk_ressembler_idannonce_b foreign key (idannonce_b)
      references annonce (idannonce)
      on delete restrict on update restrict;

alter table transaction
   add constraint fk_transact_effectuer_date foreign key (iddate)
      references date (iddate)
      on delete restrict on update restrict;

alter table transaction
   add constraint fk_transact_faire_carteban foreign key (idcartebancaire)
      references cartebancaire (idcartebancaire)
      on delete restrict on update restrict;

alter table transaction
   add constraint fk_transact_regler_reservat foreign key (idreservation)
      references reservation (idreservation)
      on delete restrict on update restrict;

alter table typehebergement
   add constraint fk_typehebe_classer_categori foreign key (idcategorie)
      references categorie (idcategorie)
      on delete restrict on update restrict;

alter table utilisateur
   add constraint fk_utilisat_enregistr_carteban foreign key (idcartebancaire)
      references cartebancaire (idcartebancaire)
      on delete restrict on update restrict;

alter table utilisateur
   add constraint fk_utilisat_resider_adresse foreign key (idadresse)
      references adresse (idadresse)
      on delete restrict on update restrict;

alter table utilisateur
   add constraint fk_utilisat_creer_date foreign key (iddate)
      references date (iddate)
      on delete restrict on update restrict;

alter table utilisateur
   add constraint fk_utilisat_utiliser_photo foreign key (idphoto)
      references photo (idphoto)
      on delete restrict on update restrict;

alter table ville
   add constraint fk_ville_situer_departem foreign key (iddepartement)
      references departement (iddepartement)
      on delete restrict on update restrict;


/*===========================================================================================*/
/*===========================================================================================*/
/* CREATION DES CONTRAINTES CHECK                                                            */
/*===========================================================================================*/
/*===========================================================================================*/

ALTER TABLE annonce
   ADD CONSTRAINT chk_annonce_prixnuitee CHECK (prixnuitee > 0),
   ADD CONSTRAINT chk_annonce_montantacompte CHECK (montantacompte >= 0),
   ADD CONSTRAINT chk_annonce_pourcentageacompte CHECK (pourcentageacompte >= 0 AND pourcentageacompte <= 100),
   ADD CONSTRAINT chk_annonce_minimumnuitee CHECK (minimumnuitee >= 1),
   ADD CONSTRAINT chk_annonce_nombreanimauxmax CHECK (nombreanimauxmax >= 0),
   ADD CONSTRAINT chk_annonce_nombrebebesmax CHECK (nombrebebesmax >= 0),
   ADD CONSTRAINT chk_annonce_etoiles CHECK (nombreetoilesleboncoin >= 1 AND nombreetoilesleboncoin <= 5),
   ADD CONSTRAINT chk_annonce_montantacompte_pourcentageacompte_exclusivite CHECK (montantacompte IS NULL OR pourcentageacompte IS NULL);

ALTER TABLE avis
   ADD CONSTRAINT chk_avis_nombreetoiles CHECK (nombreetoiles >= 1.0 AND nombreetoiles <= 5.0);

ALTER TABLE cartebancaire
   ADD CONSTRAINT chk_cartebancaire_dateexpiration CHECK (dateexpiration > CURRENT_DATE);

ALTER TABLE inclure
   ADD CONSTRAINT chk_inclure_nombrevoyageur CHECK (nombrevoyageur >= 0);


ALTER TABLE recherche
   ADD CONSTRAINT chk_recherche_capacite CHECK (capaciteminimumvoyageur >= 1),
   ADD CONSTRAINT chk_recherche_prixmin CHECK (prixminimum >= 0),
   ADD CONSTRAINT chk_recherche_prixmax CHECK (prixmaximum >= 0),
   ADD CONSTRAINT chk_recherche_prix_coherence CHECK (prixmaximum >= prixminimum),
   ADD CONSTRAINT chk_recherche_nbminchambre CHECK (nombreminimumchambre >= 0),
   ADD CONSTRAINT chk_recherche_nbmaxchambre CHECK (nombremaximumchambre >= 0),
   ADD CONSTRAINT chk_recherche_chambre_coherence CHECK (nombremaximumchambre >= nombreminimumchambre);

ALTER TABLE transaction
   ADD CONSTRAINT chk_transaction_montant CHECK (montanttransaction >= 0);

ALTER TABLE reservation
   ADD CONSTRAINT chk_reservation_telephoneclient_format CHECK (telephoneclient ~ '^0[1-9][0-9]{8}$');

ALTER TABLE utilisateur
   ADD CONSTRAINT chk_utilisateur_email_format CHECK (email LIKE '%_@_%._%'),
   ADD CONSTRAINT chk_utilisateur_telephoneutilisateur_format CHECK (telephoneutilisateur ~ '^0[1-9][0-9]{8}$'),
   ADD CONSTRAINT chk_utilisateur_pseudonyme_longueur CHECK (LENGTH(pseudonyme) BETWEEN 2 AND 50),
   ADD CONSTRAINT chk_utilisateur_solde_minimum CHECK (solde >= 0),
   ADD CONSTRAINT chk_utilisateur_password_complexity CHECK (password ~* '^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{12,}$');

ALTER TABLE particulier
   ADD CONSTRAINT chk_particulier_civilite_valeurs CHECK (civilite IN ('Monsieur', 'Madame', 'Non spécifié'));

ALTER TABLE ville
   ADD CONSTRAINT chk_ville_taxedesejour CHECK (taxedesejour >= 0);


/*===========================================================================================*/
/*===========================================================================================*/
/* ATTRIBUTION DES VALEURS PAR DEFAUT                                                        */
/*===========================================================================================*/
/*===========================================================================================*/

ALTER TABLE inclure
   ALTER COLUMN nombrevoyageur SET DEFAULT 0;

ALTER TABLE relier
   ALTER COLUMN estdisponible SET DEFAULT false;

ALTER TABLE utilisateur
   ALTER COLUMN solde SET DEFAULT 0,
   ALTER COLUMN phone_verified SET DEFAULT false,
   ALTER COLUMN identity_verified SET DEFAULT false;

ALTER TABLE recherche
   ALTER COLUMN paiementenligne SET DEFAULT false;

/*===========================================================================================*/
/*===========================================================================================*/
/* CREATION DES INDEXS                                                                       */
/*===========================================================================================*/
/*===========================================================================================*/

/* adresse */
CREATE INDEX idx_adresse_idville ON adresse(idville);

/* annonce */
CREATE INDEX idx_annonce_idadresse ON annonce(idadresse);
CREATE INDEX idx_annonce_iddate ON annonce(iddate);
CREATE INDEX idx_annonce_idheurearrivee ON annonce(idheurearrivee);
CREATE INDEX idx_annonce_idheuredepart ON annonce(idheuredepart);
CREATE INDEX idx_annonce_idtypehebergement ON annonce(idtypehebergement);
CREATE INDEX idx_annonce_idutilisateur ON annonce(idutilisateur);

/* avis */
CREATE INDEX idx_avis_idannonce ON avis(idannonce);
CREATE INDEX idx_avis_iddate ON avis(iddate);
CREATE INDEX idx_avis_idutilisateur ON avis(idutilisateur);

/* cartebancaire */
CREATE INDEX idx_cartebancaire_idutilisateur ON cartebancaire(idutilisateur);

/* cibler */
CREATE INDEX idx_cibler_idrecherche ON cibler(idrecherche);
CREATE INDEX idx_cibler_idtypehebergement ON cibler(idtypehebergement);

/* commodite */
CREATE INDEX idx_commodite_idcategorie ON commodite(idcategorie);

/* date */
CREATE INDEX idx_date_date ON date(date);

/* departement */
CREATE INDEX idx_departement_idregion ON departement(idregion);

/* demander */
CREATE INDEX idx_demander_idcompensation ON demander(idcompensation);
CREATE INDEX idx_demander_idincident ON demander(idincident);

/* favoriser */
CREATE INDEX idx_favoriser_idannonce ON favoriser(idannonce);
CREATE INDEX idx_favoriser_idutilisateur ON favoriser(idutilisateur);

/* filtrer */
CREATE INDEX idx_filtrer_idcommodite ON filtrer(idcommodite);
CREATE INDEX idx_filtrer_idrecherche ON filtrer(idrecherche);

/* incident */
CREATE INDEX idx_incident_iddate ON incident(iddate);
CREATE INDEX idx_incident_idreservation ON incident(idreservation);
CREATE INDEX idx_incident_idutilisateur ON incident(idutilisateur);

/* inclure */
CREATE INDEX idx_inclure_idreservation ON inclure(idreservation);
CREATE INDEX idx_inclure_idtypevoyageur ON inclure(idtypevoyageur);

/* message */
CREATE INDEX idx_message_iddate ON message(iddate);
CREATE INDEX idx_message_idutilisateurexpediteur ON message(idutilisateurexpediteur);
CREATE INDEX idx_message_idutilisateurreceveur ON message(idutilisateurreceveur);

/* particulier */
CREATE INDEX idx_particulier_iddate ON particulier(iddate);
CREATE INDEX idx_particulier_idutilisateur ON particulier(idutilisateur);

/* photo */
CREATE INDEX idx_photo_idannonce ON photo(idannonce);
CREATE INDEX idx_photo_idincident ON photo(idincident);

/* professionnel */
CREATE INDEX idx_professionnel_idutilisateur ON professionnel(idutilisateur);

/* proposer */
CREATE INDEX idx_proposer_idannonce ON proposer(idannonce);
CREATE INDEX idx_proposer_idcommodite ON proposer(idcommodite);

/* recherche */
CREATE INDEX idx_recherche_iddatedebutrecherche ON recherche(iddatedebutrecherche);
CREATE INDEX idx_recherche_iddatefinrecherche ON recherche(iddatefinrecherche);
CREATE INDEX idx_recherche_iddepartement ON recherche(iddepartement);
CREATE INDEX idx_recherche_idregion ON recherche(idregion);
CREATE INDEX idx_recherche_idutilisateur ON recherche(idutilisateur);
CREATE INDEX idx_recherche_idville ON recherche(idville);

/* relier */
CREATE INDEX idx_relier_idannonce ON relier(idannonce);
CREATE INDEX idx_relier_iddate ON relier(iddate);

/* reservation */
CREATE INDEX idx_reservation_idannonce ON reservation(idannonce);
CREATE INDEX idx_reservation_iddatefinreservation ON reservation(iddatefinreservation);
CREATE INDEX idx_reservation_iddatedebutreservation ON reservation(iddatedebutreservation);
CREATE INDEX idx_reservation_idutilisateur ON reservation(idutilisateur);

/* ressembler */
CREATE INDEX idx_ressembler_idannonce_a ON ressembler(idannonce_a);
CREATE INDEX idx_ressembler_idannonce_b ON ressembler(idannonce_b);

/* transaction */
CREATE INDEX idx_transaction_idcartebancaire ON transaction(idcartebancaire);
CREATE INDEX idx_transaction_iddate ON transaction(iddate);
CREATE INDEX idx_transaction_idreservation ON transaction(idreservation);

/* typehebergement */
CREATE INDEX idx_typehebergement_idcategorie ON typehebergement(idcategorie);

/* utilisateur */
CREATE INDEX idx_utilisateur_idadresse ON utilisateur(idadresse);
CREATE INDEX idx_utilisateur_idcartebancaire ON utilisateur(idcartebancaire);
CREATE INDEX idx_utilisateur_iddate ON utilisateur(iddate);
CREATE INDEX idx_utilisateur_idphoto ON utilisateur(idphoto);

/* ville */
CREATE INDEX idx_ville_iddepartement ON ville(iddepartement);


/*===========================================================================================*/
/*===========================================================================================*/
/* CREATION DES FONCTIONS                                                                    */
/*===========================================================================================*/
/*===========================================================================================*/

--voir annonces dispo entre 2 dates
CREATE OR REPLACE FUNCTION get_annonces_disponibles(
   p_date_debut DATE, 
   p_date_fin DATE
)
RETURNS TABLE (
   id_annonce INT,
   titre VARCHAR,
   type_logement VARCHAR,
   ville VARCHAR,
   prix DECIMAL
) 
AS $$
BEGIN
   RETURN QUERY
   SELECT 
      a.idannonce,
      a.titreannonce,
      th.nomtypehebergement,
      v.nomville,
      a.prixnuitee
   FROM 
      annonce a
   JOIN 
      typehebergement th ON a.idtypehebergement = th.idtypehebergement
   JOIN 
      adresse adr ON a.idadresse = adr.idadresse
   JOIN 
      ville v ON adr.idville = v.idville
   WHERE 
      (p_date_debut IS NULL AND p_date_fin IS NULL)
      OR
      a.idannonce NOT IN (
            SELECT r.idannonce
            FROM relier r
            JOIN date d ON r.iddate = d.iddate
            WHERE 
               r.estdisponible = false 
               AND
               (p_date_debut IS NULL OR d.date >= p_date_debut)
               AND
               (p_date_fin IS NULL OR d.date <= p_date_fin)
      )
   ORDER BY 
      v.nomville, 
      a.prixnuitee ASC; 
END;
$$ LANGUAGE plpgsql;

--voir annonces entre 2 prix (null possible)
CREATE OR REPLACE FUNCTION get_annonces_par_prix(
   p_prix_min DECIMAL, 
   p_prix_max DECIMAL
)
RETURNS TABLE (
   id_annonce INT,
   titre VARCHAR,
   ville VARCHAR,
   prix DECIMAL
) 
AS $$
BEGIN
   RETURN QUERY
   SELECT 
      a.idannonce,
      a.titreannonce,
      v.nomville,
      a.prixnuitee
   FROM 
      annonce a
   JOIN 
      adresse adr ON a.idadresse = adr.idadresse
   JOIN 
      ville v ON adr.idville = v.idville
   WHERE
      (p_prix_min IS NULL OR a.prixnuitee >= p_prix_min)
      AND
      (p_prix_max IS NULL OR a.prixnuitee <= p_prix_max)
   ORDER BY 
      a.prixnuitee ASC;
END;
$$ LANGUAGE plpgsql;

--voir annonces par type de logement
CREATE OR REPLACE FUNCTION get_annonces_par_types(
   p_types_noms VARCHAR[] 
)
RETURNS TABLE (
   id_annonce INT,
   titre VARCHAR,
   ville VARCHAR,
   type_logement VARCHAR,
   prix DECIMAL
) 
AS $$
BEGIN
   RETURN QUERY
   SELECT 
      a.idannonce,
      a.titreannonce,
      v.nomville,
      th.nomtypehebergement,
      a.prixnuitee
   FROM 
      annonce a
   JOIN 
      typehebergement th ON a.idtypehebergement = th.idtypehebergement
   JOIN 
      adresse adr ON a.idadresse = adr.idadresse
   JOIN 
      ville v ON adr.idville = v.idville
   WHERE 
      p_types_noms IS NULL 
      OR cardinality(p_types_noms) = 0
      OR th.nomtypehebergement = ANY(p_types_noms)
   ORDER BY 
      th.nomtypehebergement,
      a.prixnuitee ASC;
END;
$$ LANGUAGE plpgsql;

--voir annonces par particulier/pro
CREATE OR REPLACE FUNCTION get_annonces_par_vendeur(
   p_types_vendeur VARCHAR[]
)
RETURNS TABLE (
   id_annonce INT,
   titre VARCHAR,
   ville VARCHAR,
   type_logement VARCHAR,
   type_vendeur TEXT, 
   prix DECIMAL
) 
AS $$
BEGIN
   RETURN QUERY
   SELECT 
      a.idannonce,
      a.titreannonce,
      v.nomville,
      th.nomtypehebergement,
      CASE 
            WHEN part.idutilisateur IS NOT NULL THEN 'Particulier'
            WHEN pro.idutilisateur IS NOT NULL THEN 'Professionnel'
            ELSE 'Autre'
      END AS vendeur_type,
      a.prixnuitee
   FROM 
      annonce a
   JOIN 
      typehebergement th ON a.idtypehebergement = th.idtypehebergement
   JOIN 
      adresse adr ON a.idadresse = adr.idadresse
   JOIN 
      ville v ON adr.idville = v.idville
   LEFT JOIN 
      particulier part ON a.idutilisateur = part.idutilisateur
   LEFT JOIN 
      professionnel pro ON a.idutilisateur = pro.idutilisateur
   WHERE 
      (p_types_vendeur IS NULL OR cardinality(p_types_vendeur) = 0)
      OR
      (
            ('Particulier' = ANY(p_types_vendeur) AND part.idutilisateur IS NOT NULL)
            OR
            ('Professionnel' = ANY(p_types_vendeur) AND pro.idutilisateur IS NOT NULL)
      )
   ORDER BY 
      a.prixnuitee ASC;
END;
$$ LANGUAGE plpgsql;