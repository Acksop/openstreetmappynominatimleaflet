<?php
/*
 * Exemple : carte Google Map dont chaque point ouvre une infobulle de description,
 * avec la liste des points (sidebar) qui ouvre l'infobulle correspondante.
 * Lancement : OSMPNL_GOOGLE_API_KEY=<clé> php -S localhost:8000 -t .   (depuis la racine du dépôt)
 * puis http://localhost:8000/examples/googlemap-descriptions.php
 * Licence GPL-3.0-or-later.
 */
require_once __DIR__ . '/../config/openstreetmap-parameters.php';
require_once __DIR__ . '/../src/GoogleMap.php';

$points = require __DIR__ . '/donnees/points-besancon.php';

$carte = new GoogleMapAPI('carte_descriptions');
$carte->api_options = ['key' => OSMPNL_GOOGLE_API_KEY, 'language' => 'fr'];
$carte->_minify_js = false;
$carte->setWidth('100%');
$carte->setHeight('480px');
$carte->enableSidebar();
$carte->enableInfoWindow();

foreach ($points as $point) {
    $description = '<div class="infobulle"><strong>' . htmlspecialchars($point['titre']) . '</strong>'
        . '<br>' . htmlspecialchars($point['description'])
        . '<br><em>' . htmlspecialchars($point['adresse']) . '</em>';
    if ($point['lien'] !== '') {
        $description .= '<br><a href="' . htmlspecialchars($point['lien']) . '" target="_blank" rel="noopener">Site web</a>';
    }
    $description .= '</div>';
    $carte->addMarkerByCoords($point['lon'], $point['lat'], htmlspecialchars($point['titre']), $description);
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Google Map avec descriptions</title>
    <style>
        body { font-family: system-ui, sans-serif; margin: 16px; color: #222; background: #fff; }
        .disposition { display: grid; grid-template-columns: 1fr 240px; gap: 16px; }
        .sidebar_carte_descriptions a { display: block; padding: 6px 8px; cursor: pointer; border-bottom: 1px solid #ddd; }
        .infobulle { max-width: 240px; line-height: 1.4; }
        @media (max-width: 700px) { .disposition { grid-template-columns: 1fr; } }
    </style>
    <?= OSMPNL_GOOGLE_API_KEY !== '' ? $carte->getHeaderJS() . $carte->getMapJS() : '' ?>
</head>
<body>
<h1>Google Map avec descriptions</h1>
<?php if (OSMPNL_GOOGLE_API_KEY === '') : ?>
    <p>Définissez la variable d'environnement <code>OSMPNL_GOOGLE_API_KEY</code> pour afficher la carte.</p>
<?php else : ?>
    <div class="disposition">
        <div><?= $carte->getMap() ?></div>
        <nav aria-label="Points de la carte"><?= $carte->getSidebar() ?></nav>
    </div>
    <?= $carte->getOnLoad() ?>
<?php endif; ?>
</body>
</html>
