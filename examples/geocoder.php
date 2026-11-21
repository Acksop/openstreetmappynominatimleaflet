<?php
// Exemple : géocodage d'une adresse française par Nominatim, sans cache (php examples/geocoder.php).
require_once __DIR__ . '/../config/openstreetmap-parameters.php';
require_once __DIR__ . '/../src/GoogleMap.php';

$carte = new GoogleMapAPI('carte');
$point = $carte->getGeocode('Place de la Révolution 25000 Besançon', 'Place de la Révolution', '25000', 'Besançon');
echo $point === false ? "Adresse introuvable\n" : "lat {$point['lat']}, lon {$point['lon']}\n";
