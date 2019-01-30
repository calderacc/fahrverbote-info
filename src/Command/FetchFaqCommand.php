<?php declare(strict_types=1);

namespace App\Command;

use App\Faq\Entity\Entry;
use App\Limitation\Parser\GeoJsonParserInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Feed\Reader\Entry\Rss;

class FetchFaqCommand extends AbstractCityCommand
{
    protected const RSS_FEED_URL = 'https://radverkehrsforum.de/lexicon/lexicon-feed/';
    protected const CATEGORY_LABEL = 'Luftschadstoffe und Grenzwerte';

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
            if ($this->checkCategory($item)) {
                $entry = new Entry();
                $entry
                    ->setTitle($item->getTitle())
                    ->setDesription($item->getDescription())
                    ->setText($item->getContent());

                var_dump($entry);
            }
        }
    }

    protected function checkCategory(Rss $item): bool
    {
        /** @var array $category */
        foreach ($item->getCategories() as $category) {
            if (self::CATEGORY_LABEL === $category['label']) {
                return true;
            }
        }

        return false;
    }
}
