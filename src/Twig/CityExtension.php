<?php declare(strict_types=1);

namespace App\Twig;

use Psr\SimpleCache\CacheInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CityExtension extends AbstractExtension
{
    /** @var CacheInterface $cache */
    protected $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_cities', [$this, 'getCities']),
        ];
    }

    public function getCities(): array
    {
        $cities = $this->cache->get('cities');

        if (is_array($cities)) {
            return $cities;
        }

        return [];
    }
}
