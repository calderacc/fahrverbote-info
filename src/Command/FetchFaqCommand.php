<?php declare(strict_types=1);

namespace App\Command;

use App\Limitation\Parser\GeoJsonParserInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Feed\Reader\Entry\Rss;

class FetchFaqCommand extends AbstractCityCommand
{
    protected const RSS_FEED_URL = 'https://radverkehrsforum.de/lexicon/lexicon-feed/';

    public function __construct(?string $name = null, CacheInterface $cache, GeoJsonParserInterface $geoJsonParser)
    {
        parent::__construct($name, $cache);
    }

    public function configure(): void
    {
        $this->setName('verbot:fetch-faq');
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $channel = \Zend\Feed\Reader\Reader::import(self::RSS_FEED_URL);

        /** @var Rss $item */
        foreach ($channel as $item) {
            echo $item->getTitle();
        }
    }
}
