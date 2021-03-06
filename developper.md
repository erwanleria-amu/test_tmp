# POUR LES DEVELOPPEURS

# Sommaire
## [Préface](#preface)  
## I.	[Installer php7 sur Linux](#install_php7_linux)  
## II.	[Installer composer](#install_composer)  
## III.	[Accéder au projet](#acces_projet)  
## [Aparté : Créer un projet Symfony](#aparte)  
## [Sous Windows](#msdos)  
## [Documentation](#doc)  
___________________________________________  

    
## Préface : <a name="preface"></a>

Ce document explique comment lancer le projet MétéoRando (de l'équipe-projet MétéoRando1) de A à Z sur Linux/Debian via le terminal.
A la fin du document une partie est dédiée au système d'exploitation non-libre Windows.  

Manipulations effectuées sous  
OS : Linux  
Distribution : Debian 9 Stretch  

## I. Installer php7 sur Linux <a name="install_php7_linux"></a>

- Télécharger la dernière tarball de php 7, décompressez-la dans un répertoire de votre Home
- Au préalable vérifier que certaines librairies sont bien installées:

```bash
$ sudo apt-get install autoconf build-essential curl libtool \
  libssl-dev libcurl4-openssl-dev libxml2-dev libreadline7 \
  libreadline-dev libzip-dev libzip4 nginx openssl \
  pkg-config zlib1g-dev
```

- Vous pouvez configurer le build dans un répertoire pour faire les choses proprement, sinon ignorer ci-dessous "--prefix=<INSERER UN REPERTOIRE POUR LE BUILD>"
  
```
$./configure  --prefix=<INSERER UN REPERTOIRE POUR LE BUILD> --enable-mysqlnd     --with-pdo-mysql     --with-pdo-mysql=mysqlnd     --with-pdo-pgsql=/usr/bin/pg_config     --enable-bcmath     --enable-fpm     --with-fpm-user=www-data     --with-fpm-group=www-data     --enable-mbstring     --enable-phpdbg     --enable-shmop     --enable-sockets     --enable-sysvmsg     --enable-sysvsem     --enable-sysvshm     --enable-zip     --with-libzip    --with-zlib     --with-curl     --with-pear     --with-openssl     --enable-pcntl     --with-readline
```

```
$make -jX (remplacer x par le nombre de processeurs de votre machine -car c'est assez long)
Build complete.
```

```
$ make install
Wrote PEAR system config file at: /usr/local/etc/pear.conf
You may want to add: /usr/local/lib/php to your php.ini include_path
/home/erwan/Téléchargements/php-7.2.10/build/shtool install -c ext/phar/phar.phar /usr/local/bin
ln -s -f phar.phar /usr/local/bin/phar
Installing PDO headers:           /usr/local/include/php/ext/pdo/
```

- NB : Vous pouvez faire `make test` MAIS si vous avez Debian en français alors le test va s'interrompre en lisant la date actuelle, car un des tests évalue la date fournie par votre machine, or le test en question utilise une fonction qui ne lit la date qu'au format anglophone.

## II. Installer composer <a name="install_composer"></a>

- Si vous avez installé php de la manière précédente, il vous suffit de faire :
$ curl -sS https://getcomposer.org/installer | php

- Ou bien si vous aviez déjà installé php avec une version récente de XAMPP par exemple :
$ curl -sS https://getcomposer.org/installer | /opt/lampp/bin/php


- Cela crée un fichier composer.phar dans le répertoire courant, vous pouvez le copier et ou le déplacer de la façon suivante `mv composer.phar /usr/local/bin/composer` ceci vous permettra d'utiliser composer dans n'importe quel autre répertoire

```
All settings correct for using Composer
Downloading...

Composer (version 1.7.2) successfully installed to: /home/erwan/Téléchargements/php-7.2.10/composer.phar
Use it: php composer.phar
```

## III. Accéder au projet <a name="acces_projet"></a>
```
$ git clone https://github.com/Kr4unos/MeteoRando.git
$ cd MeteoRando
```

- Avant de lancer la commande suivante il faut au préalable avoir créé une session utilisateur mysql (installer les packages mysql-client et mysql-server). Il faudra alors se mettre en mode root avant de commencer la manipulation et lancer mysql. Si c'est une première connexion avec mysql, créer mot de passe : 
```
$ mysqladmin -u root password <nouveau mot de passe >
```

(Source : https://www.howtoforge.com/setting-changing-resetting-mysql-root-passwords#method-set-up-root-password-for-the-first-time)
 
- Sinon :
`$ mysql -u root -p`
Et entrez votre mot de passe si vous en avez déjà configuré un.

- Une fois dans l'interface mysql du terminal, on va créer une session avec le nom de l'utilisateur sous le répertoire home (pour éviter de lancer le projet en root), entrer : (notez qu'il faudra laisser les guillemets)
``` 
> GRANT ALL PRIVILEGES ON *.* TO '<INSERER NOM DE L UTILISATEUR SOUS LE REPERTOIRE HOME>'@'127.0.0.1' IDENTIFIED BY '<INSERER MOT DE PASSE>'
> Ctrl + c #ou quit pour sortir
```  

- Bien sortir du mode root si vous aviez auparavant fait une commande du type "su" ou "sudo -s": `$ exit`

```
$ composer install #ou composer.phar install
```

- Des lignes de ce genre vont apparaître :
```Installing swiftmailer/swiftmailer (v5.4.12): Loading from cache```
Patienter un peu, ensuite il faut entrer des informations. Pour les champs non-complétés ci-dessous il faudra appuyer sur Entrée.

```
database_host (127.0.0.1):
database_port (null): 
database_name (symfony): METTRE ICI LE NOM DE BASE DE DONNÉES SOUHAITÉ (NON-EXISTANT, SYMFONY LE CRÉERA)
database_user (root): INSERER LE NOM D'UTILISATEUR INSCRIT LORS DE LA CONFIGURATION MYSQL
database_password (null): INSERER LE MDP CHOISI DE L'UTILISATEUR
mailer_transport (smtp): 
mailer_host (127.0.0.1): 
mailer_user (null): 
mailer_password (null): 
secret (ThisTokenIsNotSoSecretChangeIt): 
```

- Création de la base de données:
```
$ php bin/console doctrine:database:create 
Created database `symfony` for connection named default
```

```
- Création du schéma de base de données en fonction des classes PHP:
$ php bin/console doctrine:schema:create
Creating database schema...
Database schema created successfully!
```

- Lancement du serveur
```
$ php bin/console server:run
[OK] Server listening on http://127.0.0.1:8000
```

- Lancer votre navigateur web favori. Dans la barre d'adresse écrire `http://127.0.0.1:8000`. Vous arrivez sur la page web de MétéoRando

## Aparté : Creer un projet Symfony <a name="aparte"></a>

- Le projet a été crée à partir du framework Symfony, voici donc une brève explication de comment démarrer un projet avec Symfony.  

- Installer Symfony:
```
$ curl -LsS https://symfony.com/installer -o /usr/local/bin/symfony
$ chmod a+x /usr/local/bin/symfony
```

- Créer un projet nommé test: `$ symfony new test 3.4`

```
 Downloading Symfony...

    6.2 MiB/6.2 MiB ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓  100%

 Preparing project...

 ✔  Symfony 3.4.17 was successfully installed. Now you can:

    * Change your current directory to /home/erwan/GL/test

    * Configure your application in app/config/parameters.yml file.

    * Run your application:
        1. Execute the php bin/console server:start command.
        2. Browse to the http://localhost:8000 URL.

    * Read the documentation at https://symfony.com/doc

$ ls test
app  bin  composer.json  composer.lock  phpunit.xml.dist  README.md  src  tests  var  vendor  web
...
```

## Sous Windows <a name="msdos"></a>

- Installer php : http://php.net/manual/fr/install.windows.php

- Ajouter le chemin de l'éxécutable PHP7 à la variable d'environnement PATH

- Installer Composer depuis le site web : https://getcomposer.org/download/ (En l'ajoutant bien à votre PATH)

- Dans l'invite de commande Windows, exécuter `composer install` à la racine du projet (notamment il faudra peut-être ouvrir l'invite de commande en tant qu'administrateur)

- Modifier le fichier "/app/parameters.yml" pour mettre les données de connexion de votre BD locale (Obligatoirement MySQL)

- Executer `php bin/console doctrine:database:create` à la racine du projet

- Ensuite executer `php bin/console doctrine:schema:create` à la racine du projet

- Enfin, vous pouvez lancer un serveur php local avec `php bin/console server:run`

## Documentation <a name="doc"></a>

La documentation  est disponible dans le répertoire `docs/MeteoRando` depuis la racine du projet.
Elle est visualisable au format html, pour cela il faut ouvrir le fichier `index.html` dans un navigateur web.
