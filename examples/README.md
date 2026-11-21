# Exemples

Quatre cartes d'exemple et Leaflet 1.9.4 autohébergé (copie de celle de B25_FR).

## Les cartes

| Fichier | Contenu |
|---|---|
| `googlemap-pictos.php` | Google Map, un picto par catégorie, avec une légende |
| `googlemap-descriptions.php` | Google Map, une infobulle par point (titre, description, adresse, lien) et une liste à côté qui ouvre l'infobulle |
| `leaflet-pictos.php` | OpenStreetMap / Leaflet, un picto par catégorie, avec une légende |
| `leaflet-descriptions.php` | OpenStreetMap / Leaflet, une bulle par point et une liste à côté qui ouvre la bulle |
| `geocoder.php` | géocodage d'une adresse par Nominatim, en ligne de commande |

Les quatre cartes utilisent les mêmes points, dans `donnees/points-besancon.php` : sept lieux de Besançon avec des
coordonnées fixes, donc aucun appel à Nominatim à l'affichage.

## Lancement

Depuis la racine du dépôt :

```bash
OSMPNL_GOOGLE_API_KEY=votre_cle php -S localhost:8000 -t .
```

puis ouvrir par exemple `http://localhost:8000/examples/leaflet-pictos.php`. Les deux cartes Leaflet marchent sans
clé ; sans clé, les cartes Google affichent un message à la place de la carte. La clé n'est jamais écrite dans le
code : voir `.env.example` à la racine.

## Fichiers utilisés

- `public/leaflet-1.9.4/` : la copie de Leaflet, avec son `LICENSE`. Sa licence BSD-2-Clause est compatible avec la
  GPL.
- `public/pictos/` : quatre épingles PNG, avec une couleur et une lettre chacune : Culture, Patrimoine, Nature,
  Transport. Elles existent en 32 × 44 px et en version `-2x` (64 × 88 px). Elles sont en PNG parce que la classe a
  besoin de lire la taille de l'image, ce qu'elle ne sait pas faire avec un SVG. Les pictos de B25_FR n'ont pas été
  repris : ce sont des boutons d'interface, pas des marqueurs.
- `config/openstreetmap-parameters.php` : trois constantes pour les adresses de Leaflet (`OSMPNL_URL_LEAFLET`), des
  pictos (`OSMPNL_URL_PICTOS`) et des fonds de carte OpenStreetMap (`OSMPNL_URL_TUILES_OSM`). Sur B25_FR, définir
  `OSMPNL_URL_LEAFLET` à `/scriptJS/leaflet-1.9.4` avant d'inclure ce fichier. La clé Google est lue dans la variable
  d'environnement `OSMPNL_GOOGLE_API_KEY` quand la constante `B25_GOOGLEMAP_API_KEY` n'est pas définie.
- `src/GoogleMap.php` : le script Google Maps est chargé en `https://maps.googleapis.com` (il l'était en `http://`,
  ce que les navigateurs bloquent sur un site en https).

## Vérifications faites

- Les quatre pages s'affichent sans avertissement PHP et le serveur local sert bien toutes les URL (code 200).
- Le JavaScript des deux pages Leaflet a une syntaxe valide.
- Les tests PHPUnit passent (4 tests).
- Les cartes n'ont pas encore été ouvertes dans un navigateur ; les cartes Google n'ont été testées qu'avec une
  fausse clé.

## À savoir

La classe `GoogleMapAPI` utilise encore `google.maps.Marker` et `google.maps.MarkerImage`, que Google a déclarés
obsolètes. Si la carte Google à pictos n'affiche pas les pictos avec une vraie clé, c'est par là qu'il faudra
chercher.
