<?php declare(strict_types=1);

namespace App\Command;

use App\Faq\Entity\Entry;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Feed\Reader\Entry\Rss;

class FetchFaqCommand extends Command
{
    protected const RSS_FEED_URL = 'https://radverkehrsforum.de/lexicon/lexicon-feed/';
    protected const CATEGORY_LABEL = 'Luftschadstoffe und Grenzwerte';

    /** @var CacheInterface $cache */
    protected $cache;

    public function __construct(?string $name = null, CacheInterface $cache)
    {
        $this->cache = $cache;

        parent::__construct($name);
    }

    public function configure(): void
    {
        $this->setName('verbot:fetch-faq');
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $channel = \Zend\Feed\Reader\Reader::import(self::RSS_FEED_URL);

        $entryList = [];

        /** @var Rss $item */
        foreach ($channel as $item) {
            if ($this->checkCategory($item)) {
                $entry = new Entry();
                $entry
                    ->setTitle($item->getTitle())
                    ->setDesription($item->getDescription())
                    ->setText($item->getContent());

                array_unshift($entryList, $entry);
            }
        }

        $this->cache->set('faq', $entryList);
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
