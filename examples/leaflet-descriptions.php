<?php
/*
 * Exemple : carte OpenStreetMap / Leaflet 1.9.4 autohébergé, chaque point ouvre une bulle de description
 * et la liste latérale ouvre la bulle du point choisi.
 * Lancement : php -S localhost:8000 -t .   (depuis la racine du dépôt)
 * puis http://localhost:8000/examples/leaflet-descriptions.php
 * Licence GPL-3.0-or-later.
 */
require_once __DIR__ . '/../config/openstreetmap-parameters.php';

$points = require __DIR__ . '/donnees/points-besancon.php';

$json = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE;
$marqueurs = array_map(static function (array $point): array {
    $html = '<strong>' . htmlspecialchars($point['titre']) . '</strong>'
        . '<br>' . htmlspecialchars($point['description'])
        . '<br><em>' . htmlspecialchars($point['adresse']) . '</em>';
    if ($point['lien'] !== '') {
        $html .= '<br><a href="' . htmlspecialchars($point['lien']) . '" target="_blank" rel="noopener">Site web</a>';
    }
    return ['lat' => $point['lat'], 'lon' => $point['lon'], 'titre' => $point['titre'], 'html' => $html];
}, $points);
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Leaflet avec descriptions</title>
    <link rel="stylesheet" href="<?= OSMPNL_URL_LEAFLET ?>/leaflet.css">
    <script src="<?= OSMPNL_URL_LEAFLET ?>/leaflet.js"></script>
    <style>
        body { font-family: system-ui, sans-serif; margin: 16px; color: #222; background: #fff; }
        .disposition { display: grid; grid-template-columns: 1fr 240px; gap: 16px; }
        #carte { width: 100%; height: 480px; }
        #liste { list-style: none; margin: 0; padding: 0; }
        #liste button { display: block; width: 100%; padding: 6px 8px; text-align: left; font: inherit;
            background: none; border: 0; border-bottom: 1px solid #ddd; cursor: pointer; }
        #liste button:hover, #liste button:focus-visible { background: #eef3fb; }
        @media (max-width: 700px) { .disposition { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<h1>OpenStreetMap / Leaflet avec descriptions</h1>
<div class="disposition">
    <div id="carte"></div>
    <nav aria-label="Points de la carte"><ul id="liste"></ul></nav>
</div>
<script>
(function () {
    var marqueurs = <?= json_encode($marqueurs, $json) ?>;

    // Icône par défaut servie depuis la copie locale de Leaflet.
    L.Icon.Default.imagePath = <?= json_encode(OSMPNL_URL_LEAFLET . '/images/', $json) ?>;

    var carte = L.map('carte');
    L.tileLayer(<?= json_encode(OSMPNL_URL_TUILES_OSM, $json) ?>, {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">contributeurs OpenStreetMap</a>'
    }).addTo(carte);

    var liste = document.getElementById('liste');
    var limites = [];
    marqueurs.forEach(function (m) {
        var marqueur = L.marker([m.lat, m.lon], {title: m.titre, alt: m.titre})
            .bindPopup(m.html, {maxWidth: 260})
            .addTo(carte);
        limites.push([m.lat, m.lon]);

        var bouton = document.createElement('button');
        bouton.type = 'button';
        bouton.textContent = m.titre;
        bouton.addEventListener('click', function () {
            carte.setView([m.lat, m.lon], Math.max(carte.getZoom(), 16));
            marqueur.openPopup();
        });
        var item = document.createElement('li');
        item.appendChild(bouton);
        liste.appendChild(item);
    });
    carte.fitBounds(limites, {padding: [40, 40]});
})();
</script>
</body>
</html>
