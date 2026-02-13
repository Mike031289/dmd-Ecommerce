# dmd-ecommerce 🛒  
**Projet e-commerce complet avec Symfony 7**

## 📌 Présentation du projet

**dmd-ecommerce** est un projet e-commerce développé avec **Symfony 7**, réalisé dans le cadre d’une **formation Udemy** dédiée à l’apprentissage du framework à travers un projet concret et complet.

L’objectif principal de ce projet est de **mettre en pratique les concepts clés de Symfony 6/7** en développant une application réaliste incluant un **front-office**, un **back-office**, un **système de paiement**, ainsi que l’**envoi d’emails transactionnels**.

Ce projet s’adresse avant tout aux développeurs PHP souhaitant **monter en compétences sur Symfony** en travaillant sur une architecture proche de la production.

---

## 🚀 Fonctionnalités principales

### 👤 Utilisateurs
- Inscription et connexion sécurisées
- Gestion du compte utilisateur
- Consultation de l’historique des commandes
- Impression des factures au format PDF
- Réception d’emails de confirmation (commande, paiement)

### 🛍️ Boutique
- Consultation des produits
- Ajout / suppression de produits dans le panier
- Tunnel d’achat en plusieurs étapes
- Paiement en ligne via **Stripe**

### 🛠️ Administration (Backoffice)
- Interface d’administration avec **EasyAdmin**
- Gestion des utilisateurs
- Gestion des produits
- Suivi et modification du statut des commandes

---

## 🧰 Technologies & outils utilisés

- **PHP 8+**
- **Symfony 7** (compatible Symfony 6)
- **Twig** (templating)
- **Doctrine ORM**
- **Symfony Security**
- **EasyAdmin Bundle**
- **Stripe API** (paiement)
- **Mailjet API** (emails transactionnels)
- **DomPDF** (factures PDF)
- **Bootstrap 5**
- **MySQL**

---

## 🎯 Objectifs pédagogiques

Ce projet permet de :

- Développer une application Symfony complète et structurée
- Comprendre et maîtriser :
  - Les controllers et les routes
  - Les formulaires et la validation des données
  - Les entités, relations et la base de données
  - Le composant Security (authentification / autorisations)
  - Les services et l’injection de dépendances
- Mettre en place un tunnel de paiement sécurisé
- Envoyer des emails transactionnels
- Créer une interface d’administration
- Préparer et déployer une application Symfony en production

---

## 📚 Contexte de la formation

- **Plateforme** : Udemy  
- **Durée** : ~21 heures  
- **Niveau** : Débutant à intermédiaire  
- **Langue** : Français  
- **Nombre de participants** : +5 500  
- **Note moyenne** : ⭐ 4,8 / 5  
- **Dernière mise à jour** : Juin 2024  

---

## ✅ Prérequis

- Bonne maîtrise de **PHP** et de la programmation orientée objet
- Connaissances de base en **HTML / CSS**
- Notions de base en base de données relationnelle

---

## 🧑‍💻 À qui s’adresse ce projet ?

- Développeurs PHP souhaitant découvrir **Symfony 6 et 7**
- Développeurs voulant aller au-delà de la documentation officielle
- Personnes en formation ou en reconversion souhaitant travailler sur un **projet e-commerce réel**

---

## 📦 Installation (local)

```bash
git clone https://github.com/Mike031289/dmd-Ecommerce
cd dmd-ecommerce
composer install
npm install
npm run build

Configurer le fichier .env (base de données, Stripe, Mailjet), puis :

php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
symfony server:start

## Si vous souhaitez tester l'application dans son intégrallité en mode administrateur, veuillez utiliser ce compte test (Email : jonne@yopmail.compour / Password : 1234567) pour tester l'interface administrateur pour gérer les utilisateurs, et leurs commandes.
Vous pouvez également vous inscrire avec votre propre adresse mail pour tester les fonctionnalité d'inscription d'un user pour tester l'interface utilisateur uniquement.
Si vous souhaitez tester l'interface administrateur avec votre adresse mail, veuillez me contacter via mon adresse mail : mike.agbelou@gmail.com, afin que je puisse vous octroyer les droits d'administration.
L'application est disponible et consultable via le lien suivant : https://dmdprod.com/

Merci :) 
