<?php declare(strict_types=1);

namespace App\Limitation\Entity;

class Limitation
{
    /** @var string $title */
    protected $title;

    /** @var string $description */
    protected $description;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): Limitation
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): Limitation
    {
        $this->description = $description;

        return $this;
    }
}
