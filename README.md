# BileMo

Création d'une API REST avec Symfony pour BileMo.

[![Maintainability](https://api.codeclimate.com/v1/badges/31b3cca094c7e3a353bf/maintainability)](https://codeclimate.com/github/mdoutreluingne/bilemo/maintainability)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/ce4f4e062f8a4ca6bc7babbabcd9ac1c)](https://www.codacy.com/gh/mdoutreluingne/bilemo/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=mdoutreluingne/bilemo&amp;utm_campaign=Badge_Grade)

## Configuration du serveur requise

*   MySQL ou MariaDB
*   Apache2 (avec le mod_rewrite activé)
*   Php 7.4
*   Composer
*   git
*   OpenSSL

## Installation du projet

Cloner le projet sur votre disque dur avec la commande :
```text
https://github.com/mdoutreluingne/bilemo.git
```

Ensuite, effectuez la commande "composer install" depuis le répertoire du projet cloné, afin d'installer les dépendances back nécessaires :
```text
composer install
```

### Générer les clés SSH

Définissez JWT_PASSPHRASE dans .env et utilisez-le dans les commandes suivantes :

```bash
mkdir config/jwt
openssl genrsa -out config/jwt/private.pem -aes256 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```

### Paramétrage et accès à la base de données

Editez le fichier situé à la racine intitulé ".env" afin de remplacer les valeurs de paramétrage de la base de données :

````text
//Exemple : mysql://root:@127.0.0.1:3306/bilemo
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name"
````

Ensuite à la racine du projet, effectuez la commande `php bin/console doctrine:database:create` pour créer la base de données :

````text
php bin/console doctrine:database:create
````

Pour obtenir une structure similaire à mon projet au niveau de la base de données, je vous joins aussi dans le dossier `~src/migrations/` les versions de migrations que j'ai utilisées. Vous pouvez donc recréer la base de données en effectuant la commande suivante, à la racine du projet :

```text
php bin/console doctrine:migrations:migrate
```

Après avoir créer votre base de données, vous pouvez également injecter un jeu de données en effectuant la commande suivante :

```text
php bin/console doctrine:fixtures:load
```

### Lancer le projet

A la racine du projet :

*   Pour lancer le serveur de symfony, effectuez un `php bin/console server:start`.

### Bravo, la documentation de l'API est désormais accessible à l'adresse : localhost:8000/docs

### Authentification

A ce niveau la, vos requêtes vers l'api seront refusées car vous ne serez pas authentifié au sein du projet. Suivez donc les étapes suivantes :

#### Choisir un compte utilisateur

Utiliser le compte suivant pour générer un token :

*   Email : admin@bilemo.fr
*   Mot de passe : adminadmin

#### Générez un token JWT

```text
curl -X POST -H "Content-Type: application/json" http://localhost:8000/login -d "{\"email\":\"admin@bilemo.fr\",\"password\":\"adminadmin\"}"
```

Vous devriez obtenir le résultat suivant :

```json
{
	"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2NDAwOTUwODQsImV4cCI6MTY0MDA5ODY4NCwicm9sZXMiOlsiUk9MRV9VU0VSIiwiUk9MRV9BRE1JTiJdLCJ1c2VybmFtZSI6ImFkbWluQGJpbGVtby5mciJ9.niQsOWWVPq1YuM01huCWgirsS3KdyphB06hGadvwudY98DxIKpk3qkh4DGk6k3HPJ15mcmOIJKufHY7IJfDdjkQzOhTSEh7_AgkHtHD6eZvnM-o9I6CyXa3DaUSWHC2-0WuRDNVw1_8bmcLhlEJ3dttrPNkL9aZ4XbNVq_FcDZJt-99FxjO5oleMJl49n_MSrNRt-bHUSfj46CAIDFHW09OPaNoLP1SptvqF065Md3Ml_RTmsu9EBdTGxQyVVDAD20y0YGqteBUaaizWfsFoO8FQBijgm8RqH68ZbFme91UE7Uu4-1zes2PFSMx5ThS-OOUc9IK3p13pAPGIK4rt6j3eY5SuMAWlSSnDnwYqAv4LbCsup8wFwBP9L5OdNAOh7KNxYWagkq3sZbmpnPsZYxxjiDt-Q2FM26u5-9-f63kiNCJug2vdJRZ5Vsklc1YX1xgSTNSwrvi-eUfVh1hPbGicoFRAH-MjTE_kVUBzHHKieGCZqtU1y-QFmBUD9Csnt1Bb-L4tOod-hzDizKw-_dala4JSZzm9u8ySq0hhWBAByWz5M_tfLqnqlZHt7lT-DlrgcWYNPFhbpJoMrhFgIoydHltMZM3cd36APUtR4T-fpcGvo5YQ03gTfd6Q0cdl7Dwuyl3A8rp0J_4D4ESdQIb1cA6L44fACy9qo5iU2aA"
}
```

#### Utilisez le token JWT pour effectuer des opérations

Récupérez le token généré pour commencer à utiliser l'API de BileMo.

```text
curl -H "Authorization: Bearer {yourtoken}" http://localhost:8000/users
```