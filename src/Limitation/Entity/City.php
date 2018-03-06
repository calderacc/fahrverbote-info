<?php declare(strict_types=1);

namespace App\Limitation\Entity;

class City
{
    /** @var string $name */
    private $name;

    /** @var string $desription */
    private $desription;

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
}
