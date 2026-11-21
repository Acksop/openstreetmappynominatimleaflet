<?php
/*
 * Constantes d'openstreetmappynominatimleaflet, sorties de GoogleMap.php (21/11/2026).
 * Chaque constante peut être définie par le site avant l'inclusion de ce fichier.
 * Licence GPL-3.0-or-later.
 */
$osmpnl = [
    'OSMPNL_LOOKUP_SERVICE'    => 'NOMINATIM',
    'OSMPNL_SERVEUR_GOOGLE'    => 'maps.google.com',
    'OSMPNL_SERVEUR_YAHOO'     => 'api.local.yahoo.com',
    'OSMPNL_SERVEUR_NOMINATIM' => 'nominatim.openstreetmap.org',
    'OSMPNL_URL_NOMINATIM'     => 'https://nominatim.openstreetmap.org/',
    'OSMPNL_URL_GOOGLE_GEOCODE'=> 'https://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false&key=%s',
    'OSMPNL_GOOGLE_API_KEY'    => defined('B25_GOOGLEMAP_API_KEY') ? B25_GOOGLEMAP_API_KEY : '',
    'OSMPNL_PAYS'              => 'france',
    'OSMPNL_USER_AGENT'        => 'Besancon25/1.0 (administrateur@besancon25.fr)',
    'OSMPNL_USER_AGENT_FETCH'  => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13',
    'OSMPNL_CURL_CONNEXION'    => 3,
    'OSMPNL_CURL_TOTAL'        => 5,
    'OSMPNL_ZOOM'              => 16,
    'OSMPNL_LARGEUR'           => '500px',
    'OSMPNL_HAUTEUR'           => '500px',
    'OSMPNL_TABLE_CACHE'       => 'geocodes',
    'OSMPNL_MARKER_CLUSTERER'  => '/MarkerClusterer-1.0/markerclusterer_compiled.js',
    'OSMPNL_PEAR_DB'           => defined('B25_SCRIPTPHP_PATH')
        ? B25_SCRIPTPHP_PATH . DIRECTORY_SEPARATOR . 'Pear/DB-1.7.14/DB.php' : '',
];
foreach ($osmpnl as $nom => $valeur) {
    if (!defined($nom)) {
        define($nom, $valeur);
    }
}
unset($osmpnl, $nom, $valeur);
