# Projet SmartCart

Ce projet SmartCart est une application web de gestion de listes de courses. Elle permet aux utilisateurs de créer, modifier et supprimer des listes de courses, ainsi que d'obtenir des statistiques sur leurs dépenses et les types d'aliments achetés. L'administrateur Minh peut gérer les articles et les types.

Le projet se trouve dans la branche master, il faudra donc s'assurer d'être dans cette branche.

## Installation

Pour installer et exécuter ce projet localement, suivez les étapes ci-dessous :

1. Clonez ce dépôt dans www (Windows ou Linux) ou htdocs (Mac) sur votre machine locale :

   ```bash
   git clone https://github.com/yacebni/smartcart.git

2. Accédez au répertoire du projet :

    ```bash
    cd smartcart

3. Installez les dépendances PHP avec Composer :
    ```bash
    composer install

4. Installez les dépendances JavaScript avec npm :
   ```bash
    npm install

5. Créez une base de données **listeCourse** dans PhPMyAdmin.


6. Configurez votre fichier .env.local en copiant le fichier .env et en ajustant les valeurs selon votre configuration :
   ```bash
   cp .env .env.local
Remplacez les valeurs de DATABASE_URL par les informations de votre connexion PhpMyAdmin.
Si vous êtes sous Windows, vous pouvez utiliser l'URL suivant : 
**DATABASE_URL="mysql://root@127.0.0.1:3306/listeCourse?serverVersion=10.11.2-MySQL&charset=utf8mb4"**

7. Lancez votre serveur (**Wamp** ou **Mamp** ou **Xamp**)


8. Effectuez les migrations Doctrine pour créer la structure de la base de données :
   ```bash
   php bin/console doctrine:migrations:migrate

## Utilisation

Pour exécuter l'application, suivez les étapes ci-dessous :

1. Compilez les fichiers CSS et JavaScript en mode de développement :
   ```bash
   npm run watch

2. Lancez un serveur PHP dans le dossier public. Dans un autre terminal appliquer les commandes suivantes :
   ```bash
   cd public
   php -S localhost:8080

3. Ouvrez votre navigateur et accédez à l'URL http://localhost:8080 pour utiliser l'application.

## Informations de connexion

Pour accéder à l'interface d'administration, vous pouvez utiliser les informations de connexion suivantes :

- **Nom d'utilisateur** : Minh
- **Mot de passe** : Minh69

## Fonctionnalités

- Créer, modifier et supprimer des listes de courses.
- Obtenir des statistiques sur les dépenses totales, la moyenne des dépenses par liste, les articles les moins chers et les plus chers, ainsi que les proportions de types d'aliments achetés.
- Interface d'administration protégée par authentification pour la gestion des articles et des types

## Réalisateurs

Ce projet a été réalisé par un groupe d'étudiants du BUT Informatique de l'IUT Lyon 1 :
- Redouane **AZIZI** 
- Gatta **BA** @gattacode
- Yacine **BOUANANI**
- Jules **VIC**