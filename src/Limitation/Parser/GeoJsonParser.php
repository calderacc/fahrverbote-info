<?php declare(strict_types=1);

namespace App\Limitation\Parser;

use App\Limitation\Entity\City;
use App\Limitation\Entity\Limitation;

class GeoJsonParser
{
    /** @var City $city */
    protected $city;

    /** @var string $geoJson */
    protected $geoJson;

    public function loadFromFile(string $filename): GeoJsonParser
    {
        $this->geoJson = json_decode(file_get_contents($filename));

        return $this;
    }

    public function loadFromString(string $content): GeoJsonParser
    {
        $this->geoJson = json_decode($content);

        return $this;
    }

    public function parse(): GeoJsonParser
    {
        $this
            ->createCity()
            ->parseLimitations();

        return $this;
    }

    protected function createCity(): GeoJsonParser
    {
        $this->city = new City();
        $this->city
            ->setName($this->geoJson->city)
            ->setDescription($this->geoJson->description);

        return $this;
    }

    protected function parseLimitations(): GeoJsonParser
    {
        if (!$this->city) {
            return $this;
        }

        foreach ($this->geoJson->features as $feature) {
            $limitation = new Limitation();
            $limitation
                ->setTitle($feature->properties->name)
                ->setDescription($feature->properties->popupContent);

            $this->city->addLimitation($limitation);
        }

        return $this;
    }

    public function getCity(): City
    {
        return $this->city;
    }
}
