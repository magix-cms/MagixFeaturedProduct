# MagixFeaturedProduct

[![Release](https://img.shields.io/github/release/magix-cms/MagixFeaturedProduct.svg)](https://github.com/magix-cms/MagixFeaturedProduct/releases/latest)
[![License](https://img.shields.io/github/license/magix-cms/MagixFeaturedProduct.svg)](LICENSE)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D%208.2-blue.svg)](https://php.net/)
[![Magix CMS](https://img.shields.io/badge/Magix%20CMS-4.x-success.svg)](https://www.magix-cms.com/)

**MagixFeaturedProduct** est un plugin officiel pour Magix CMS 4.x permettant de mettre en avant une sélection personnalisée de produits directement sur la page d'accueil de votre boutique en ligne.

## 🌟 Fonctionnalités principales

* **Recherche AJAX ultra-rapide** : Ajoutez des produits à votre sélection en les cherchant par leur nom ou leur référence, sans recharger la page.
* **Filtre intelligent** : Les produits déjà ajoutés à votre liste n'apparaissent plus dans les résultats de recherche pour éviter les doublons.
* **Drag & Drop fluide** : Modifiez l'ordre d'affichage de vos produits phares d'un simple glisser-déposer (propulsé par `Sortable.js`).
* **Sauvegarde 100% automatique** : Chaque ajout, suppression ou déplacement est sauvegardé instantanément en arrière-plan via AJAX. Fini les boutons "Enregistrer" !
* **Intégration native au thème** : Le widget public réutilise la boucle de produits native (`product-grid.tpl`) de votre thème. Vos produits phares auront exactement le même design, les mêmes animations et le même responsive que le reste de votre catalogue.
* **SEO Orienté** : Délégation complète au moteur central du CMS. Le plugin profite de la génération automatique du format WebP pour les images et des balises `JSON-LD` (Schema.org) configurées dans le `ProductPresenter`.

## ⚙️ Installation

1. Téléchargez la dernière version du plugin.
2. Décompressez l'archive et placez le dossier `MagixFeaturedProduct` dans le répertoire `plugins/` de votre installation Magix CMS.
3. Connectez-vous à l'administration de Magix CMS.
4. Rendez-vous dans **Extensions > Plugins**.
5. Repérez **MagixFeaturedProduct** dans la liste et cliquez sur **Installer**.

*Note : Lors de l'installation, le système créera automatiquement la table `mc_plug_featured_product` dans votre base de données et greffera le widget sur le hook `displayHomeBottom`.*

## 🚀 Utilisation

### Côté Administration
1. Accédez à la configuration du plugin depuis votre panneau de contrôle.
2. Utilisez la barre de recherche à gauche pour trouver un produit.
3. Cliquez sur le produit dans la liste des résultats pour l'ajouter à votre sélection.
4. Dans la colonne de droite, utilisez l'icône de poignée (⋮⋮) pour réorganiser vos produits par glisser-déposer.
5. Une notification verte `MagixToast` vous confirmera la sauvegarde automatique à chaque action.

### Côté Public (Frontend)
Le plugin s'affiche automatiquement sur votre page d'accueil via le hook défini dans le `manifest.json`. Si aucun produit n'est sélectionné dans l'administration, le widget devient totalement invisible et n'alourdit pas le code source de la page.

## 🛠️ Architecture Technique (Pour les développeurs)

Ce plugin a été conçu en respectant l'architecture stricte de **Magix CMS V4** et le principe **DRY** (Don't Repeat Yourself) :

* **Frontend** : Il ne refait pas de requêtes complexes avec des jointures multiples. Il se contente de récupérer une liste d'IDs (dans l'ordre défini) et délègue la récupération des datas complètes au cœur du CMS via `ProductDb::getProductsByIds()`.
* **Backend UI** : L'interface d'administration repose sur la classe Javascript mutualisée `MagixItemSelector.js` qui orchestre Fetch API, Sortable.js et MagixToast.
* **Sécurité** : Intégration d'un système de `try/catch` global (Sandboxing) dans le `FrontendController`. Si le plugin rencontre une erreur (ex: template introuvable ou erreur DB), il n'entraîne pas d'Erreur Fatale PHP et laisse le reste du site public s'afficher normalement.

## 📄 Licence

Ce projet est sous licence **GPLv3**. Voir le fichier [LICENSE](LICENSE) pour plus de détails.
Copyright (C) 2008 - 2026 Gerits Aurelien (Magix CMS)
Ce programme est un logiciel libre ; vous pouvez le redistribuer et/ou le modifier selon les termes de la Licence Publique Générale GNU telle que publiée par la Free Software Foundation ; soit la version 3 de la Licence, ou (à votre discrétion) toute version ultérieure.