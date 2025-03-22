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

### Comportement responsive
- Le système sélectionne automatiquement la bannière appropriée selon la largeur de l'écran
- La hauteur est automatiquement ajustée à 10% de la hauteur de la fenêtre
- Sur mobile, la hauteur est réduite à 8% (écrans < 768px) et 6% (écrans < 480px)
- Si une taille spécifique n'est pas trouvée, le système utilisera la première bannière disponible
