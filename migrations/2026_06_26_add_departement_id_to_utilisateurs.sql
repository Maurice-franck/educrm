-- ============================================================
-- Migration : Ajout du rattachement département pour les chefs
-- de département (table utilisateurs)
-- ============================================================
-- À exécuter une seule fois sur la base de données existante.
-- Compatible MySQL / MariaDB.
-- ============================================================

ALTER TABLE utilisateurs
    ADD COLUMN departement_id INT NULL DEFAULT NULL AFTER role,
    ADD CONSTRAINT fk_utilisateurs_departement
        FOREIGN KEY (departement_id) REFERENCES departements(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE;

-- Index pour accélérer les filtres par département
CREATE INDEX idx_utilisateurs_departement_id ON utilisateurs(departement_id);

-- ============================================================
-- Exemple d'affectation d'un chef de département existant
-- (à adapter avec les vrais id) :
--
-- UPDATE utilisateurs SET departement_id = 1 WHERE id = 5 AND role = 'CHEF_DEPARTEMENT';
-- ============================================================
