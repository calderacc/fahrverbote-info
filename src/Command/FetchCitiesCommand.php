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
    protected const DEFAULT_REF = 'master';

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
            ->addOption('ref', null, InputOption::VALUE_OPTIONAL, 'Reference', self::DEFAULT_REF);
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $client = new \Github\Client();

        $vendor = $input->getOption('vendor');
        $repository = $input->getOption('repository');
        $ref = $input->getOption('ref');

        $files = $client->api('repo')->contents()->show($vendor, $repository, '.', $ref);

        $cities = [];

        $table = new Table($output);
        $this->addDefaultCityHeader($table);

        foreach ($files as $file) {
            if (strpos($file['name'], '.geojson') !== strlen($file['name']) - 8) {
                $output->writeln(sprintf('Skipping <comment>%s</comment> as it is not a geojson file', $file['name']));

                continue;
            }

            $citySlug = substr($file['name'], 0, -8);
            $filename = $file['name'];

            $content = $client->api('repo')->contents()->download($vendor, $repository, $filename, $ref);

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
