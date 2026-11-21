<?php
/*
 * Exemple : carte OpenStreetMap / Leaflet 1.9.4 autohébergé, un picto par catégorie et une légende.
 * Lancement : php -S localhost:8000 -t .   (depuis la racine du dépôt)
 * puis http://localhost:8000/examples/leaflet-pictos.php
 * Licence GPL-3.0-or-later.
 */
require_once __DIR__ . '/../config/openstreetmap-parameters.php';

$points = require __DIR__ . '/donnees/points-besancon.php';
$libelles = ['culture' => 'Culture', 'patrimoine' => 'Patrimoine', 'nature' => 'Nature', 'transport' => 'Transport'];

$json = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE;
$marqueurs = array_map(static function (array $point): array {
    return ['lat' => $point['lat'], 'lon' => $point['lon'], 'titre' => $point['titre'], 'categorie' => $point['categorie']];
}, $points);
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Leaflet avec pictos</title>
    <link rel="stylesheet" href="<?= OSMPNL_URL_LEAFLET ?>/leaflet.css">
    <script src="<?= OSMPNL_URL_LEAFLET ?>/leaflet.js"></script>
    <style>
        body { font-family: system-ui, sans-serif; margin: 16px; color: #222; background: #fff; }
        #carte { width: 100%; height: 480px; }
        .legende { background: #fff; padding: 6px 10px; border-radius: 4px; line-height: 24px; }
        .legende img { width: 16px; height: 22px; vertical-align: middle; margin-right: 4px; }
    </style>
</head>
<body>
<h1>OpenStreetMap / Leaflet avec pictos</h1>
<div id="carte"></div>
<script>
(function () {
    var marqueurs = <?= json_encode($marqueurs, $json) ?>;
    var libelles = <?= json_encode($libelles, $json) ?>;
    var urlPictos = <?= json_encode(OSMPNL_URL_PICTOS, $json) ?>;
    var urlLeaflet = <?= json_encode(OSMPNL_URL_LEAFLET, $json) ?>;

    var carte = L.map('carte');
    L.tileLayer(<?= json_encode(OSMPNL_URL_TUILES_OSM, $json) ?>, {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">contributeurs OpenStreetMap</a>'
    }).addTo(carte);

    // Picto 32 × 44 px (64 × 88 en haute densité), pointe en bas au centre.
    var pictos = {};
    Object.keys(libelles).forEach(function (categorie) {
        pictos[categorie] = L.icon({
            iconUrl: urlPictos + '/' + categorie + '.png',
            iconRetinaUrl: urlPictos + '/' + categorie + '-2x.png',
            iconSize: [32, 44],
            iconAnchor: [16, 44],
            popupAnchor: [0, -40],
            tooltipAnchor: [16, -24],
            shadowUrl: urlLeaflet + '/images/marker-shadow.png',
            shadowSize: [41, 41],
            shadowAnchor: [13, 41]
        });
    });

    var limites = [];
    marqueurs.forEach(function (m) {
        L.marker([m.lat, m.lon], {icon: pictos[m.categorie], title: m.titre, alt: m.titre})
            .bindTooltip(m.titre)
            .addTo(carte);
        limites.push([m.lat, m.lon]);
    });
    carte.fitBounds(limites, {padding: [40, 40]});

    var legende = L.control({position: 'bottomright'});
    legende.onAdd = function () {
        var div = L.DomUtil.create('div', 'legende');
        Object.keys(libelles).forEach(function (categorie) {
            var ligne = L.DomUtil.create('div', '', div);
            var img = L.DomUtil.create('img', '', ligne);
            img.src = urlPictos + '/' + categorie + '.png';
            img.alt = '';
            ligne.appendChild(document.createTextNode(libelles[categorie]));
        });
        return div;
    };
    legende.addTo(carte);
})();
</script>
</body>
</html>
