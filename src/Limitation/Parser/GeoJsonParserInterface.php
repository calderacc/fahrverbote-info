<?php declare(strict_types=1);

namespace App\Limitation\Parser;

use App\Limitation\Entity\City;

interface GeoJsonParserInterface
{
    public function loadFromFile(string $filename): GeoJsonParserInterface;

    public function loadFromString(string $content): GeoJsonParserInterface;

    public function parse(): GeoJsonParserInterface;

    public function getCity(): City;
}
