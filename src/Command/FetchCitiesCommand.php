<?php declare(strict_types=1);

namespace App\Command;

use App\Limitation\Parser\GeoJsonParserInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FetchCitiesCommand extends Command
{
    /** @var CacheInterface $cache */
    protected $cache;

    /** @var GeoJsonParserInterface $geoJsonParser */
    protected $geoJsonParser;

    public function __construct(?string $name = null, CacheInterface $cache, GeoJsonParserInterface $geoJsonParser)
    {
        $this->cache = $cache;

        $this->geoJsonParser = $geoJsonParser;

        parent::__construct($name);
    }

    public function configure(): void
    {
        $this->setName('verbot:fetch-cities');
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $client = new \Github\Client();

        $files = $client->api('repo')->contents()->show('maltehuebner', 'fahrverbote', '.');

        $cities = [];

        foreach ($files as $file) {
            $citySlug = substr($file['name'], 0, -8);

            $content = $client->api('repo')->contents()->download('maltehuebner', 'fahrverbote', $file['name']);

            $city = $this->geoJsonParser
                ->loadFromString($content)
                ->parse()
                ->getCity();

            $output->writeln(sprintf('Added city <comment>%s</comment>', $citySlug));

            $cities[$citySlug] = $city;
        }

        ksort($cities);

        $this->cache->set('cities', $cities);
    }
}
