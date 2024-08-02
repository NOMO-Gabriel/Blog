
---

# Votre Blog 

Bienvenue dans le projet **Blog** ! Ce projet implémente une application web simple et efficace en PHP avec utilisation du framework symfony.

## Table des matières

- [Aperçu](#aperçu)
- [Démonstration](#démonstration)
- [Fonctionnalités](#fonctionnalités)
- [Technologies Utilisées](#technologies-utilisées
)
- [Comment utiliser](#comment-utiliser)
- [Installation](#installation)
- [Configuration de la base de données](#configuration-de-la-base-de-données)
- [Contribuer](#contribuer)
- [Licence](#licence)

## Aperçu

Blog est une application Web permettant a une entreprise de gerrer les problemes de ses utilisteurs a travers un forum.Elle fourmit plusieurs interfaces et des fonctionnalites dependant de chauqe utilisteur: un simple visiteur pouvant consulter le site.un utilisateur inscrit pouvant poser des questions et repondre a des questions des autres et des administrateurs ayant tous le droits sur le site.

![Capture d'écran de l'application](/utilReadme/images/1.png)
![Capture d'écran de l'application](/utilReadme/images/2.png)
![Capture d'écran de l'application](/utilReadme/images/3.png)
![Capture d'écran de l'application](/utilReadme/images/4.png)
![Capture d'écran de l'application](/utilReadme/images/5.png)
![Capture d'écran de l'application](/utilReadme/images/6.png)


## Fonctionnalités

- **Authentification des utilisateurs** : Fonctionnalités d'inscription, de connexion et de déconnexion.
- **Gestion des services** : Création, modification, suppression et filtrage des services et questions relatives a ces services.
- **Gestion des publications** : Création, modification, suppression et consultation des articles de blog.
- **Système de commentaires** : Ajout, suppression, modification et gestion des commentaires sur les articles de blog.
- **Contrôle d'accès basé sur les rôles** : Les administrateurs disposent de permissions accrues pour gérer le contenu et les utilisateurs.
- **Pagination** : Prend en charge les vues paginées des publications et des utilisateurs.
- **Icônes SVG** : Icônes SVG personnalisées pour des actions telles que la modification, la suppression, la déconnexion, etc.

## Technologies Utilisées

- **Framework** : Symfony 7
- **Moteur de templating** : Twig
- **CSS** : Styles personnalisés avec des pratiques CSS modernes
- **JavaScript** : Pour les éléments interactifs
- **Base de données** : MySQL (ou toute autre base de données supportée par Symfony)

## Comment utiliser
### Prérequis

- PHP >= 8.2 (pour Symfony 7)
- Composer
- MySQL (ou une autre base de données supportée)

### Étapes

1. **Clonez le dépôt :**

    ```bash
    git clone https://github.com/NOMO-Gabriel/Blog.git
    cd blog
    ```

2. **Installez les dépendances :**

    ```bash
    composer install
    ```

3. **Configurez les variables d'environnement :**

   Copiez le fichier `.env` et configurez la connexion à la base de données et autres paramètres :

    ```bash
    cp .env .env.local
    ```

4. **Créez la base de données :**

    ```bash
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
    ```

5. **Démarrez le serveur Symfony :**

    ```bash
    symfony server:start
    ```

   Ou utilisez :

    ```bash
    php -S localhost:8000 -t public
    ```


## Utilisation

### Page d'accueil

Accédez à la page d'accueil du site et laissez vous guider par notre interface naturelle.

### Gestion des utilisateurs

Les utilisateurs peuvent s'inscrire, se connecter et gérer leurs profils.

### Gestion des administrateurs

Les administrateurs ont des fonctionnalités supplémentaires pour gérer le contenu et les utilisateurs.

### Gestion des services

Les services permettent de créer, modifier et supprimer des posts de blog.

### Gestion des publications de blog

Les utilisateurs et les administrateurs peuvent créer, modifier, supprimer et consulter des articles de blog.

### Gestion des commentaires

Les commentaires peuvent être ajoutés, modifiés ou supprimés sur les articles de blog.

### Authentification

Les utilisateurs doivent s'authentifier pour accéder aux fonctionnalités spécifiques.

## Contribuer

Les contributions à ce projet sont les bienvenues ! N'hésitez pas à soumettre des problèmes, suggestions ou améliorations via GitHub.

## Licence

Ce projet est sous licence [MIT](licence.txt). Vous pouvez consulter le fichier `licence.txt` pour plus de détails.




