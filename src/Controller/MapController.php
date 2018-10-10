<?php declare(strict_types=1);

namespace App\Controller;

use App\Limitation\Entity\City;
use App\Limitation\Parser\GeoJsonParser;
use Psr\SimpleCache\CacheInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class MapController extends Controller
{
    public function city(CacheInterface $cache, GeoJsonParser $parser, string $citySlug): Response
    {
        $city = $this->getCityFromCache($cache, $citySlug);

        if (!$city) {
            throw $this->createNotFoundException(sprintf('City %s not found', $citySlug));
        }

        $geoJsonUrl = sprintf('https://raw.githubusercontent.com/maltehuebner/fahrverbote/master/%s.geojson', $citySlug);

        return $this->render('map/city.html.twig', [
            'geoJsonUrl' => $geoJsonUrl,
            'city' => $city,
        ]);
    }

    protected function getCityFromCache(CacheInterface $cache, string $citySlug): ?City
    {
        $cities = $cache->get('cities');

        if (!is_array($cities) || !array_key_exists($citySlug, $cities)) {
            return null;
        }

        return $cities[$citySlug];
    }
}
