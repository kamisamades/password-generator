# Password Generator

Application web PHP simple permettant de générer un ou plusieurs mots de passe personnalisables. La génération peut combiner des caractères aléatoires et des mots français sélectionnés dans une liste locale.

## Fonctionnalités

- Génération de 1 à 100 mots de passe en une seule demande.
- Longueur des caractères aléatoires réglable de 8 à 128 caractères.
- Inclusion optionnelle de :
  - lettres majuscules ;
  - lettres minuscules ;
  - chiffres ;
  - symboles.
- Génération à partir de mots français.
- Exclusion des mots présents dans la liste des mots de passe à éviter.
- Sélection de mots sans doublon au sein d'un même mot de passe.
- Modèle de composition personnalisable avec les marqueurs `{words}` et `{random}`.
- Interface web responsive basée sur Bootstrap 5.
- Mode de débogage permettant d'afficher la trace interne de l'application.

## Prérequis

- PHP 7.4 ou version ultérieure, avec la fonction `random_int()` disponible.
- Un serveur web capable d'exécuter PHP, par exemple Apache, Nginx ou le serveur intégré de PHP.
- Un navigateur récent.
- Une connexion Internet pour charger Bootstrap et jQuery depuis leur CDN, sauf si ces dépendances sont remplacées par des fichiers locaux.

Aucune dépendance Composer n'est nécessaire.

## Installation

1. Cloner ou copier le projet dans le répertoire servi par le serveur web.
2. Vérifier que PHP peut lire les fichiers du projet et les fichiers du dossier `datas/`.
3. Ouvrir `index.php` depuis le navigateur.

Avec le serveur PHP intégré, depuis la racine du projet :

```bash
php -S localhost:8000
```

Puis ouvrir [http://localhost:8000](http://localhost:8000).

## Structure du projet

```text
.
├── config/
│   └── config.php                  # Constantes et paramètres de l'application
├── datas/
│   ├── liste-mots-francais.txt     # Liste des mots disponibles
│   └── motdepasses-a-eviter.txt    # Mots à exclure
├── includes/
│   └── functions.php               # Fonctions de génération
├── index.php                        # Interface et traitement du formulaire
├── style.css                        # Styles de l'interface
└── README.md                        # Documentation du projet
```

## Fonctionnement

### 1. Soumission du formulaire

`index.php` récupère les paramètres envoyés en POST : longueur, nombre de mots de passe, types de caractères, nombre de mots et modèle de composition.

Les valeurs sont ensuite limitées aux bornes prévues dans `config/config.php` :

- longueur : 8 à 128 caractères aléatoires ;
- nombre de mots de passe : 1 à 100.

### 2. Génération des caractères aléatoires

La fonction `generatePassword()` construit un alphabet à partir des options sélectionnées, puis utilise `random_int()` pour choisir chaque caractère de façon cryptographiquement plus sûre que la fonction `rand()`.

Les alphabets disponibles sont définis par les constantes suivantes :

- `APP_PASSWORD_UPPERCASE`
- `APP_PASSWORD_LOWERCASE`
- `APP_PASSWORD_NUMBERS`
- `APP_PASSWORD_SYMBOLS`

### 3. Génération des mots

Lorsque l'option de génération par mots est active, `generateWords()` :

1. charge `datas/liste-mots-francais.txt` ;
2. charge `datas/motdepasses-a-eviter.txt` ;
3. retire les mots interdits ;
4. sélectionne le nombre de mots demandé ;
5. empêche les doublons dans une même sélection ;
6. assemble les mots avec un tiret par défaut.

Le nombre demandé est automatiquement limité au nombre de mots disponibles après filtrage.

### 4. Modèle du mot de passe

Le modèle est défini par `APP_PASSWORD_PATTERN` et peut être modifié dans le formulaire.

Marqueurs disponibles :

| Marqueur | Remplacement |
| --- | --- |
| `{words}` | Mots sélectionnés, séparés par un tiret |
| `{random}` | Caractères aléatoires générés |

Exemple de modèle :

```text
{words}-{random}
```

Avec deux mots et une partie aléatoire, le résultat peut prendre la forme suivante :

```text
montagne-lilas-A7!k2P
```

## Configuration

Les paramètres principaux se trouvent dans [config/config.php](config/config.php).

| Constante | Rôle | Valeur par défaut |
| --- | --- | --- |
| `APP_PASSWORD_MIN_LENGTH` | Longueur minimale de la partie aléatoire | `8` |
| `APP_PASSWORD_MAX_LENGTH` | Longueur maximale de la partie aléatoire | `128` |
| `APP_PASSWORD_DEFAULT_LENGTH` | Longueur utilisée par défaut | `12` |
| `APP_PASSWORD_MAX_COUNT` | Nombre maximal de mots de passe produits | `100` |
| `APP_PASSWORD_WORDS_FILE` | Fichier contenant les mots disponibles | `datas/liste-mots-francais.txt` |
| `APP_PASSWORD_WORDS_BANNED_FILE` | Fichier contenant les mots exclus | `datas/motdepasses-a-eviter.txt` |
| `APP_PASSWORD_PATTERN` | Modèle de composition par défaut | `{words}-{random}` |
| `DEBUG_MODE` | Affichage de la trace de débogage | `true` |

Les fichiers texte de mots doivent contenir un mot par ligne. Ils doivent rester lisibles par le processus PHP.

## Sécurité et bonnes pratiques

- La génération des caractères utilise `random_int()`.
- Les mots à éviter sont filtrés avant la sélection.
- Les mots de passe affichés dans la page sont échappés avec `htmlspecialchars()`.
- Pour un environnement de production, désactiver `DEBUG_MODE` afin de ne pas exposer d'informations internes.
- Utiliser HTTPS lorsque l'application est accessible sur un réseau.
- Éviter de stocker les mots de passe générés dans des journaux ou dans une base de données sans besoin explicite.
- Les valeurs reçues du formulaire doivent continuer à être validées côté serveur, même si des limites sont présentes dans les champs HTML.

## Développement

Les fonctions principales sont regroupées dans `includes/functions.php` :

- `generatePassword(...)` génère la partie aléatoire et assemble le résultat ;
- `generateWords(...)` sélectionne les mots autorisés.

Pour tester rapidement la syntaxe PHP lorsqu'un interpréteur est installé :

```bash
php -l index.php
php -l config/config.php
php -l includes/functions.php
```

## Changelog

- Documentation du projet ajoutée dans `README.md`.

### [1.0.6] - 2026-09-16

- Correction sur l'encodage des mots du dictionnaire

### [1.0.5] - 2026-09-15

- Création de l'interface web de génération de mots de passe.
- Ajout de la génération de caractères aléatoires avec `random_int()`.
- Ajout de la génération à partir d'une liste de mots français.
- Ajout d'une liste de mots de passe à éviter.
- Correction du chemin vers `datas/motdepasses-a-eviter.txt`.
- Correction de la sélection des mots pour les demandes courantes d'un ou plusieurs mots.
- Ajout de la limitation du nombre de mots sélectionnés au volume disponible.
- Ajout de la génération de plusieurs mots de passe.
- Ajout des modèles `{words}` et `{random}`.

## Licence

Ce projet est distribué sous licence MIT. Voir la constante `APP_LICENSE` dans `config/config.php` pour les informations actuellement configurées.

## Auteur

Maurice LECON — [lebrun.dev](https://lebrun.dev)
