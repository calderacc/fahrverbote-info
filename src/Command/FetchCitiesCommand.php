<?php declare(strict_types=1);

namespace App\Command;

use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FetchCitiesCommand extends Command
{
    /** @var CacheInterface $cache */
    protected $cache;

    public function __construct(?string $name = null, CacheInterface $cache)
    {
        $this->cache = $cache;

        parent::__construct($name);
    }

    public function configure()
    {
        $this->setName('verbot:fetch-cities');
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $client = new \Github\Client();

        $files = $client->api('repo')->contents()->show('maltehuebner', 'fahrverbote', '.');

        $cities = [];

        foreach ($files as $file) {
            $cities[] = substr($file['name'], 0, -8);
        }

        $this->cache->set('cities', $cities);
    }
}