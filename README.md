# cot2html



A propos des champs de l'URL
"http://127.0.0.1/dist/index.php?file=cotcot%2Ftst2.cotcot&item=poudet&tabStart=256&tabEnd=16&zoom=0.6&scroll=1"

<b>file=cotcot%2Ftst2.cotcot</b> (Sélectionne un cotcot, dans cet exemple <b>cotcot/tst2.cotcot</b>)

<b>item=lst</b> (liste d'inscription)

<b>item =pou</b> (poules vue simple)

<b>item =poudet</b> (poules vue complète)

<b>item =clapou</b> (plus que le classement des poules. Une vue listing utile avant, durant et après les poules, avec heures et pistes). Note que le contenu exact change selon l'avancement des phases dans le cotcot. Donne aussi un classement dynamique durant la phase de poule.

<b>item =tab</b> (la vue du tableau, réglable avec tabStart et tabEnd (en puissance de 2: 256,128,64,32)

<b>item =clatab</b> (plus que le classement final, une vue qui liste le déroulement du tableau, avec heures et pistes et classement). Note que le contenu exact change selon l'avancement des phases dans le cotcot.

zoom une variable qui influence la tailles des éléments du CSS

<b>scroll=1</b> (active le scrolling et le reload automatique de la page

<b>ABC=1</b>  force un ordre alphabétique dans la vue classement des poules (item=clapou)

Un click de souris dans la barre de titre donne accès à un mini GUI, avec un curseur pour gérer le niveau de zoom, et une navigation vers les autres vue. C'est utile pour piloter les pages sans un clavier.


 A implementer plus tard dans une prochaine version : 
## Gestion des bannières sur la page d'accueil

Les bannières sont stockées dans le dossier `banner/` à la racine du site et sont gérées de manière responsive.

### Format des fichiers
- Les fichiers doivent être au format PNG
- Nomenclature : `banner_[largeur].png`
  Exemple : `banner_1920.png`

### Résolutions supportées
- 1920px : banner_1920.png
- 1440px : banner_1440.png
- 1024px : banner_1024.png
- 768px : banner_768.png
- 480px : banner_480.png
- 320px : banner_320.png


Version 2.0.0 (2024)
-------------------

SÉCURITÉ
- Ajout du support HTTPS avec redirection automatique
- Ajout des headers de sécurité HSTS
- Mise à jour des URLs pour être protocole-agnostiques
- Protection contre les attaques XSS dans les formulaires

INTERFACE
- Nouvelle barre d'outils responsive avec icônes FontAwesome
- Ajout d'une interface de sélection des compétitions modernisée
- Support dynamique d'une image de bannière (banniere.png)
- Amélioration du design responsive pour mobiles et tablettes

FONCTIONNALITÉS
- Ajout d'un classement général accessible depuis la barre d'outils
- Le classement général affiche maintenant :
  * Place
  * Nation (avec drapeau)
  * Nom complet du tireur
  * Club
  * Département 
  * Région
- Navigation par onglets dans les tableaux d'élimination
- Redirection automatique vers la liste des tireurs après sélection d'une compétition
- Meilleure gestion des erreurs et des cas limites
- recherche par nom de tireur et affichage automatique de la page / ligne lors du rechargement


PERFORMANCES
- Optimisation du chargement des ressources
- Amélioration du temps de réponse des tableaux
- Mise en cache optimisée des données

CORRECTIONS
- Correction de l'affichage des drapeaux dans les classements
- Amélioration de la gestion des caractères spéciaux
- Correction des problèmes d'affichage sur Internet Explorer
- Correction des problèmes de tri dans les classements

TECHNIQUE
- Refactorisation du code pour une meilleure maintenabilité
- Amélioration de la structure des fichiers
- Standardisation des noms de variables et des commentaires
- Migration vers des URLs relatives pour supporter HTTPS
- compatibilité avec webkit pour IOS apple

Note: Cette version nécessite PHP 7.0 ou supérieur et un serveur web avec support SSL pour activer HTTPS.

Version 2.0.1 (2024)
-------------------

INTERFACE
- Ajout d'un bouton de rafraîchissement flottant rouge avec animation
- Le bouton est centré en bas de chaque page (sauf menu principal)
- Animation de rotation pendant le rafraîchissement
- Correction majeure de l'affichage des tableaux éliminatoires :
  * Meilleure gestion de l'espace d'affichage
  * Correction des problèmes de superposition des lignes
  * Amélioration de la lisibilité des noms des tireurs
  * Correction de l'alignement des scores
  * Support correct des grands tableaux (256+)
## Journal des modifications

### Version 2.0.2 (2025)

#### Interface utilisateur
- Ajout d'une barre d'outils fixe en haut de l'écran
- Ajout d'un bandeau d'informations sur la compétition
- Ajout d'un bouton de rafraîchissement en bas a gauche
- Implémentation d'onglets pour la navigation entre les tableaux

#### Responsive Design
- Optimisation de l'affichage pour les appareils mobiles
- Support spécifique pour les modes portrait et paysage
- Ajustement automatique des tailles de texte et espacements
- Meilleure gestion de l'espace sur petits écrans

#### Fonctionnalités
- Défilement automatique du bandeau d'informations quand le contenu dépasse
- Animation du bouton de rafraîchissement
- Sauvegarde du niveau de zoom entre les pages
- Meilleure gestion des URLs relatives

#### Corrections et améliorations
- Correction de la superposition du bandeau d'information en mode portrait
- Optimisation des performances sur mobile
- Amélioration de la lisibilité des tableaux sur petit écran
- Correction des marges et espacements pour éviter les chevauchements

#### Accessibilité
- Amélioration du contraste des couleurs
- Ajout d'icônes pour une meilleure compréhension
- Textes redimensionnables
- Support tactile optimisé

#### Navigation
- Nouveau menu de navigation principal
- Accès rapide aux différentes sections
- Indication visuelle de la page active
- Retour facile à la page d'accueil
- ajout d'un menu déroulant pour l'affichage optimisé des tableaux éliminatoires

#### Optimisations techniques
- Refactorisation du code CSS
- Organisation en variables CSS pour une maintenance facilitée
- Amélioration de la gestion des événements JavaScript
- Support du mode sombre du système

### Comportement responsive
- Le système sélectionne automatiquement la bannière appropriée selon la largeur de l'écran
- La hauteur est automatiquement ajustée à 10% de la hauteur de la fenêtre
- Sur mobile, la hauteur est réduite à 8% (écrans < 768px) et 6% (écrans < 480px)
- Si une taille spécifique n'est pas trouvée, le système utilisera la première bannière disponible
