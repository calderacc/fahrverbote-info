<?php declare(strict_types=1);

namespace App\Command;

use App\Limitation\Entity\City;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListCitiesCommand extends AbstractCityCommand
{
    /** @var CacheInterface $cache */
    protected $cache;

    public function __construct(?string $name = null, CacheInterface $cache)
    {
        $this->cache = $cache;

        parent::__construct($name);
    }

    public function configure(): void
    {
        $this->setName('verbot:list-cities');
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $cities = $this->cache->get('cities');

        if (!is_array($cities)) {
            return;
        }

        $table = new Table($output);
        $this->addDefaultCityHeader($table);

        /** @var City $city */
        foreach ($cities as $city) {
            $this->addCityTableRow($table, $city);
        }

        $table->render();
    }
}
