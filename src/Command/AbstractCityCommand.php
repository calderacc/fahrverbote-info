<?php declare(strict_types=1);

namespace App\Command;

use App\Limitation\Entity\City;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;

abstract class AbstractCityCommand extends Command
{
    /** @var CacheInterface $cache */
    protected $cache;

    public function __construct(?string $name = null, CacheInterface $cache)
    {
        $this->cache = $cache;

        parent::__construct($name);
    }

    protected function getCitiesFromCache(): ?array
    {
        return $this->cache->get('cities');
    }

    protected function storeCitiesInCache(array $cities): void
    {
        $this->cache->set('cities', $cities);
    }

    protected function addDefaultCityHeader(Table $table): void
    {
        $table->setHeaders([
            'citySlug',
            'Name',
            'Description',
            'Limitations',
        ]);
    }

    protected function addCityTableRow(Table $table, City $city): void
    {
        $table->addRow([
            strtolower($city->getName()),
            $city->getName(),
            $city->getDescription(),
            count($city->getLimitations()),
        ]);
    }
}
