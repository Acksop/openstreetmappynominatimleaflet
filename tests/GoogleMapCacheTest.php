<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/** Cache remplaçable et constantes de configuration (aucun appel réseau). */
final class GoogleMapCacheTest extends TestCase
{
    public function testLesConstantesSontDefinies(): void
    {
        $this->assertSame('NOMINATIM', OSMPNL_LOOKUP_SERVICE);
        $this->assertSame(3, OSMPNL_CURL_CONNEXION);
        $this->assertSame(5, OSMPNL_CURL_TOTAL);
        $this->assertSame('geocodes', OSMPNL_TABLE_CACHE);
    }

    public function testLaCarteLitSesValeursParDefautDansLaConfiguration(): void
    {
        $carte = new GoogleMapAPI();
        $this->assertSame(OSMPNL_ZOOM, $carte->zoom);
        $this->assertSame(OSMPNL_LOOKUP_SERVICE, $carte->lookup_service);
    }

    public function testUnCacheFourniEstLuAvantToutGeocodage(): void
    {
        $cache = new class {
            public array $ecrits = [];
            public function lire(string $adresse) { return $adresse === 'connue' ? ['lon' => '6.02', 'lat' => '47.24'] : false; }
            public function ecrire(string $adresse, string $lon, string $lat): void { $this->ecrits[] = [$adresse, $lon, $lat]; }
        };
        $carte = new GoogleMapAPI();
        $carte->setCache($cache);
        $this->assertSame(['lon' => '6.02', 'lat' => '47.24'], $carte->getGeocode('connue'));
        $this->assertSame([], $cache->ecrits);
    }

    public function testUnCacheFourniRecoitLeResultatDuGeocodage(): void
    {
        $cache = new class {
            public array $ecrits = [];
            public function lire(string $adresse) { return false; }
            public function ecrire(string $adresse, string $lon, string $lat): void { $this->ecrits[] = [$adresse, $lon, $lat]; }
        };
        $carte = new class extends GoogleMapAPI {
            function geoGetCoords($address, $depth = 0, $adresse = '', $cp = '', $ville = '') { return ['lat' => '47.1', 'lon' => '6.1']; }
        };
        $carte->setCache($cache);
        $this->assertSame(['lat' => '47.1', 'lon' => '6.1'], $carte->getGeocode('nouvelle'));
        $this->assertSame([['nouvelle', '6.1', '47.1']], $cache->ecrits);
    }
}
