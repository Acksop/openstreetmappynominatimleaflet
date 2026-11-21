# openstreetmappynominatimleaflet

<!-- ALL-CONTRIBUTORS-BADGE:START - Do not remove or modify this section -->
[![All Contributors](https://img.shields.io/badge/all_contributors-1-orange.svg?style=flat-square)](#contributeurs)
<!-- ALL-CONTRIBUTORS-BADGE:END -->
[![Licence : GPL v3 ou ultérieure](https://img.shields.io/badge/licence-GPL--3.0--or--later-blue.svg?style=flat-square)](LICENSE)

Bibliothèque PHP de géocodage et de cartes, issue de *easyGoogleMap* (GoogleMapAPI v3) telle qu'elle vit dans la
plate-forme Besançon 25 : géocodage par **Nominatim** (OpenStreetMap) par défaut, Google et Yahoo en option, cache
des coordonnées remplaçable, affichage des points sur une carte **Leaflet**.

> Vieux script remis à jour : délais cURL bornés, une seule exécution de chaque requête, constantes sorties du code
> dans `config/`.

## Contenu

| Chemin | Rôle |
|--------|------|
| `src/GoogleMap.php` | classe `GoogleMapAPI` (géocodage, cache, génération de carte) |
| `config/` | constantes de la bibliothèque (serveurs de géocodage, délais, dimensions, icônes, cache) |
| `sql/` | schéma d'exemple pour le cache MySQL |
| `examples/` | exemples d'utilisation, sans identifiants |
| `public/leaflet-1.9.4/` | Leaflet 1.9.4 autohébergé (copie de B25_FR, licence BSD-2-Clause dans son dossier) |
| `public/pictos/` | pictos de marqueurs 32 × 44 px (et `-2x` en 64 × 88) par catégorie |

## Installation

```bash
git clone https://github.com/Acksop/openstreetmappynominatimleaflet.git
```

```php
require_once __DIR__ . '/openstreetmappynominatimleaflet/config/openstreetmap-parameters.php';
require_once __DIR__ . '/openstreetmappynominatimleaflet/src/GoogleMap.php';

$carte = new GoogleMapAPI('carte');
$coordonnees = $carte->getGeocode('1 place de la Révolution 25000 Besançon');
```

Ne versionnez jamais d'identifiants (DSN, clés d'API) : passez-les par vos propres fichiers de configuration.

## Exemples de cartes

| Fichier | Carte |
|---------|-------|
| `examples/googlemap-pictos.php` | Google Map, un picto par catégorie, légende |
| `examples/googlemap-descriptions.php` | Google Map, infobulles de description et liste latérale |
| `examples/leaflet-pictos.php` | OpenStreetMap / Leaflet autohébergé, un picto par catégorie, légende |
| `examples/leaflet-descriptions.php` | OpenStreetMap / Leaflet autohébergé, bulles de description et liste latérale |

Les quatre pages partagent les points de `examples/donnees/points-besancon.php` (coordonnées fixes, aucun appel à
Nominatim à l'affichage). Depuis la racine du dépôt :

```bash
php -S localhost:8000 -t .
# Google Map : la clé passe par l'environnement, jamais par le code
OSMPNL_GOOGLE_API_KEY=votre_cle php -S localhost:8000 -t .
```

puis `http://localhost:8000/examples/leaflet-pictos.php`, etc. Sur un site où Leaflet et les pictos sont servis
ailleurs, définissez `OSMPNL_URL_LEAFLET` et `OSMPNL_URL_PICTOS` avant d'inclure la configuration (sur B25_FR :
`/scriptJS/leaflet-1.9.4`).

## Usage de Nominatim

Le service public `nominatim.openstreetmap.org` impose une
[politique d'usage](https://operations.osmfoundation.org/policies/nominatim/) : une requête par seconde au plus,
un User-Agent identifiant votre application, pas d'autocomplétion pendant la frappe, et un cache des résultats.
Gardez le cache activé.

## Licence

Distribué sous **GNU GPL version 3 ou ultérieure** (`GPL-3.0-or-later`), voir [LICENSE](LICENSE).

Le code d'origine est sous GNU LGPL 2.1 ou ultérieure (texte dans [COPYING.LESSER-2.1](COPYING.LESSER-2.1)), dont
l'article 3 permet de le redistribuer sous la GPL. Les mentions d'origine sont conservées en tête de
`src/GoogleMap.php` :

- GoogleMapAPI 2.5 : © 2005 New Digital Group, Inc., auteur Monte Ohrt ;
- GoogleMapAPI v3 : © 2010-2012 Brad Wedell ;
- adaptations Nominatim / Leaflet : © 2026 Emmanuel ROY (Info ARTS Media).

`JSMin.php`, livré autrefois à côté, n'est pas repris : sa clause « The Software shall be used for Good, not Evil »
le rend incompatible avec la GPL. La minification du JavaScript reste facultative (`class_exists('JSMin')`).

## Contribuer

Les contributions de toute nature sont bienvenues (code, documentation, tests, idées, relecture). Ce dépôt suit la
spécification [all-contributors](https://allcontributors.org) : chaque contribution est reconnue ci-dessous.

## Contributeurs

<!-- ALL-CONTRIBUTORS-LIST:START - Do not remove or modify this section -->
<!-- prettier-ignore-start -->
<!-- markdownlint-disable -->
<table>
  <tbody>
    <tr>
      <td align="center" valign="top" width="14.28%"><a href="https://github.com/Acksop"><img src="https://avatars.githubusercontent.com/Acksop?s=100" width="100px;" alt="Emmanuel ROY"/><br /><sub><b>Emmanuel ROY</b></sub></a><br /><a href="https://github.com/Acksop/openstreetmappynominatimleaflet/commits?author=Acksop" title="Code">💻</a> <a href="#maintenance-Acksop" title="Maintenance">🚧</a> <a href="#ideas-Acksop" title="Idées et conception">🤔</a></td>
    </tr>
  </tbody>
</table>

<!-- markdownlint-restore -->
<!-- prettier-ignore-end -->

<!-- ALL-CONTRIBUTORS-LIST:END -->

Auteurs du code d'origine : Monte Ohrt (GoogleMapAPI) et Brad Wedell (GoogleMapAPI v3).
