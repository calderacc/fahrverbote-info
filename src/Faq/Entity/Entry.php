<?php declare(strict_types=1);

namespace App\Faq\Entity;

class Entry
{
    /** @var string $title */
    protected $title;

    /** @var string $desription */
    protected $desription;

    /** @var string $text */
    protected $text;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Entry
    {
        $this->title = $title;

        return $this;
    }

    public function getDesription(): string
    {
        return $this->desription;
    }

    public function setDesription(string $desription): Entry
    {
        $this->desription = $desription;

        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): Entry
    {
        $this->text = $text;

        return $this;
    }
}
