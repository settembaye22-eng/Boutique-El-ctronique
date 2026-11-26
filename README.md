# Boutique Électronique

Boutique PHP/MySQL responsive avec panier, commande à la livraison, recherche, filtres, fiche produit et administration CRUD sécurisée.

## Installation

1. Importez `database/schema.sql` dans MySQL.
2. Configurez les variables : `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`.
3. Créez l’administrateur :
   ```bash
   php scripts/create_admin.php admin@exemple.com
   ```
4. Donnez les droits d’écriture au dossier `uploads/`.
5. Lancez localement :
   ```bash
   php -S 127.0.0.1:8000
   ```

Ouvrez `http://127.0.0.1:8000` et l’administration sur `/admin/login.php`.
