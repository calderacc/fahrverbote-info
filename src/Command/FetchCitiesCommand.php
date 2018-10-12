<?php declare(strict_types=1);

namespace App\Command;

use App\Limitation\Parser\GeoJsonParserInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FetchCitiesCommand extends AbstractCityCommand
{
    protected const DEFAULT_VENDOR = 'maltehuebner';
    protected const DEFAULT_REPOSITORY = 'fahrverbote';
    protected const DEFAULT_BRANCH = 'master';

    /** @var GeoJsonParserInterface $geoJsonParser */
    protected $geoJsonParser;

    public function __construct(?string $name = null, CacheInterface $cache, GeoJsonParserInterface $geoJsonParser)
    {
        $this->geoJsonParser = $geoJsonParser;

        parent::__construct($name, $cache);
    }

    public function configure(): void
    {
        $this
            ->setName('verbot:fetch-cities')
            ->addOption('vendor', null, InputOption::VALUE_OPTIONAL, 'Vendor', self::DEFAULT_VENDOR)
            ->addOption('repository', null, InputOption::VALUE_OPTIONAL, 'Repository', self::DEFAULT_REPOSITORY)
            ->addOption('branch', null, InputOption::VALUE_OPTIONAL, 'Branch', self::DEFAULT_BRANCH);
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $client = new \Github\Client();

        $vendor = $input->getOption('vendor');
        $repository = $input->getOption('repository');
        $branch = $input->getOption('branch');

        $files = $client->api('repo')->contents()->show($vendor, $repository, '.', $branch);

        $cities = [];

        $table = new Table($output);
        $this->addDefaultCityHeader($table);

        foreach ($files as $file) {
            $citySlug = substr($file['name'], 0, -8);
            $filename = $file['name'];

            $content = $client->api('repo')->contents()->download($vendor, $repository, $filename, $branch);

            $city = $this->geoJsonParser
                ->loadFromString($content)
                ->parse()
                ->getCity();
            
            $cities[$citySlug] = $city;

            $this->addCityTableRow($table, $city);
        }

        ksort($cities);

        $this->storeCitiesInCache($cities);

        $table->render();
    }
}
