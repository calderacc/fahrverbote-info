<?php declare(strict_types=1);

namespace App\Controller;

use App\Limitation\Parser\GeoJsonParser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class MapController extends Controller
{
    public function index(): Response
    {
        return $this->render('map/index.html.twig');
    }

    public function city(GeoJsonParser $parser, string $citySlug): Response
    {
        $geoJsonUrl = sprintf('https://raw.githubusercontent.com/maltehuebner/fahrverbote/master/%s.geojson', $citySlug);

        $parser->loadFromFile($geoJsonUrl)->parse();

        return $this->render('map/city.html.twig', [
            'geoJsonUrl' => $geoJsonUrl,
            'city' => $parser->getCity(),
        ]);
    }
}
