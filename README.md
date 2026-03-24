# GoogleRecaptcha

[![Release](https://img.shields.io/github/release/magix-cms/google-recaptcha.svg)](https://github.com/magix-cms/google-recaptcha/releases/latest)
[![License](https://img.shields.io/github/license/magix-cms/google-recaptcha.svg)](LICENSE)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D%208.2-blue.svg)](https://php.net/)
[![Magix CMS](https://img.shields.io/badge/Magix%20CMS-4.x-success.svg)](https://www.magix-cms.com/)

**GoogleRecaptcha** est un plugin de sécurité anti-spam de haute performance conçu pour **Magix CMS 4**. Il intègre de manière transparente la technologie Google reCAPTCHA v3 pour protéger vos formulaires sans imposer de contraintes visuelles (widgets) dans vos mises en page.

## 🚀 Installation

### Option 1 : Via Composer (Recommandé)

1. Vérifiez que votre fichier `composer.json` autorise l'installation dans `/plugins/` :

```json
"extra": {
    "installer-paths": {
      "plugins/GoogleRecaptcha/": ["magix-cms/google-recaptcha"]
    }
}
```
2. Exécutez : `composer require magix-cms/google-recaptcha`
3. Dans l'administration : **Extensions** > **Gestionnaire** > **Installer**.

### Option 2 : Installation Manuelle

1. Placez le dossier `GoogleRecaptcha` dans le répertoire `plugins/`.
2. Dans l'administration : **Extensions** > **Gestionnaire** > **Installation automatique**.

## 🛠 Configuration & Logique Statique

À la différence des modules de contenu, ce plugin utilise une logique de **Widget Statique**. Il n'apparaît pas dans le gestionnaire de Layout car sa présence est liée à la logique métier des formulaires :

* **Configuration Centralisée :** Renseignez vos clés API (Clé de site et Clé secrète) v3 dans les paramètres du plugin.
* **Activation par Module :** Sélectionnez les modules (ex: Contact, Inscription) que vous souhaitez protéger. Le plugin injecte alors automatiquement les scripts nécessaires sur les pages concernées.
* **Hooks Automatiques :** Le plugin utilise les hooks système pour insérer le code JS de manière non-intrusive, garantissant une protection sans intervention manuelle dans vos templates.

## ✨ Fonctionnalités

* **Protection Invisible :** Aucune case à cocher. L'analyse de risque de Google v3 se fait totalement en arrière-plan.
* **Exécution Just-In-Time (JIT) :** Le jeton de sécurité est généré uniquement lors de la tentative de soumission du formulaire. Cela évite les erreurs d'expiration de jeton (2 minutes) fréquentes sur les formulaires longs à remplir.
* **Validation Backend Robuste :** Utilisation de l'API de vérification Google via cURL avec gestion de timeout (compatible PHP 8.2+).
* **Compatibilité AJAX :** Conçu pour fonctionner nativement avec `MagixFrontForms` pour des soumissions fluides sans rechargement de page.
* **Zero Layout Impact :** Étant un widget statique, il ne modifie pas le design de votre site et ne surcharge pas le DOM inutilement.

## 📄 Licence

Ce projet est sous licence **GPLv3**. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

Copyright (C) 2008 - 2026 Gerits Aurelien (Magix CMS)