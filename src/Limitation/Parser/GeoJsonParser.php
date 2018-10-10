<?php declare(strict_types=1);

namespace App\Limitation\Parser;

use App\Limitation\Entity\City;
use App\Limitation\Entity\Limitation;

class GeoJsonParser implements GeoJsonParserInterface
{
    /** @var City $city */
    protected $city;

    /** @var string $geoJson */
    protected $geoJson;

    public function loadFromFile(string $filename): GeoJsonParserInterface
    {
        $this->geoJson = json_decode(file_get_contents($filename));

        return $this;
    }

    public function loadFromString(string $content): GeoJsonParserInterface
    {
        $this->geoJson = json_decode($content);

        return $this;
    }

    public function parse(): GeoJsonParserInterface
    {
        $this
            ->createCity()
            ->parseLimitations();

        return $this;
    }

    protected function createCity(): GeoJsonParserInterface
    {
        $this->city = new City();

        if (isset($this->geoJson->properties)) {
            $this->city
                ->setName($this->geoJson->properties->name)
                ->setDescription($this->geoJson->properties->description)
                ->setGeoJson(json_encode($this->geoJson));
        }

        return $this;
    }

    protected function parseLimitations(): GeoJsonParser
    {
        if (!$this->city) {
            return $this;
        }

        foreach ($this->geoJson->features as $feature) {
            $limitation = new Limitation();

            if (isset($feature->properties) && isset($feature->properties->name) && isset($feature->properties->description)) {
                $limitation
                    ->setTitle($feature->properties->name)
                    ->setDescription($feature->properties->description);
            }

            $this->city->addLimitation($limitation);
        }

        return $this;
    }

    public function getCity(): City
    {
        return $this->city;
    }
}
