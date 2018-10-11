<?php declare(strict_types=1);

namespace App\Limitation\Entity;

class City
{
    /** @var string $name */
    private $name;

    /** @var string $desription */
    private $desription;

    /** @var array $limitations */
    private $limitations = [];

    /** @var string $geoJson */
    protected $geoJson;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): City
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->desription;
    }

    public function setDescription(string $description): City
    {
        $this->desription = $description;

        return $this;
    }

    public function addLimitation(Limitation $limitation): City
    {
        $this->limitations[] = $limitation;

        return $this;
    }

    public function getLimitations(): array
    {
        return $this->limitations;
    }

    public function getGeoJson(): ?string
    {
        return $this->geoJson;
    }

    public function setGeoJson(string $geoJson): City
    {
        $this->geoJson = $geoJson;

        return $this;
    }
}
