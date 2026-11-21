<?php
/*
 * Exemple : carte Google Map dont chaque point porte le picto de sa catégorie.
 * Lancement : OSMPNL_GOOGLE_API_KEY=<clé> php -S localhost:8000 -t .   (depuis la racine du dépôt)
 * puis http://localhost:8000/examples/googlemap-pictos.php
 * La clé n'est jamais écrite dans le code : variable d'environnement ou constante B25_GOOGLEMAP_API_KEY.
 * Licence GPL-3.0-or-later.
 */
require_once __DIR__ . '/../config/openstreetmap-parameters.php';
require_once __DIR__ . '/../src/GoogleMap.php';

// createMarkerIcon() lit la taille du picto sur disque : DOCUMENT_ROOT . URL du picto.
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $_SERVER['DOCUMENT_ROOT'] = dirname(__DIR__);
}

$points = require __DIR__ . '/donnees/points-besancon.php';
$libelles = ['culture' => 'Culture', 'patrimoine' => 'Patrimoine', 'nature' => 'Nature', 'transport' => 'Transport'];

$carte = new GoogleMapAPI('carte_pictos');
$carte->api_options = ['key' => OSMPNL_GOOGLE_API_KEY, 'language' => 'fr'];
$carte->_minify_js = false;
$carte->setWidth('100%');
$carte->setHeight('480px');
$carte->disableSidebar();

// Un picto par catégorie : 32 × 44 px, pointe en bas au centre (ancre 16, 44).
foreach (array_keys($libelles) as $categorie) {
    $carte->addIcon(OSMPNL_URL_PICTOS . "/$categorie.png", '', 16, 44, 16, 0);
}
foreach ($points as $point) {
    $carte->addMarkerByCoords(
        $point['lon'],
        $point['lat'],
        $point['titre'],
        '<strong>' . htmlspecialchars($point['titre']) . '</strong>',
        '',
        OSMPNL_URL_PICTOS . '/' . $point['categorie'] . '.png'
    );
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Google Map avec pictos</title>
    <style>
        body { font-family: system-ui, sans-serif; margin: 16px; color: #222; background: #fff; }
        .legende { display: flex; flex-wrap: wrap; gap: 12px; padding: 0; list-style: none; }
        .legende img { width: 16px; height: 22px; vertical-align: middle; margin-right: 4px; }
    </style>
    <?= OSMPNL_GOOGLE_API_KEY !== '' ? $carte->getHeaderJS() . $carte->getMapJS() : '' ?>
</head>
<body>
<h1>Google Map avec pictos</h1>
<?php if (OSMPNL_GOOGLE_API_KEY === '') : ?>
    <p>Définissez la variable d'environnement <code>OSMPNL_GOOGLE_API_KEY</code> pour afficher la carte.</p>
<?php else : ?>
    <?= $carte->getMap() ?>
    <?= $carte->getOnLoad() ?>
<?php endif; ?>
<ul class="legende">
    <?php foreach ($libelles as $categorie => $libelle) : ?>
        <li><img src="<?= OSMPNL_URL_PICTOS . "/$categorie.png" ?>" alt=""><?= $libelle ?></li>
    <?php endforeach; ?>
</ul>
</body>
</html>
