<?php declare(strict_types=1);

namespace App\Command;

use App\Limitation\Entity\City;
use App\Limitation\Entity\Limitation;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListLimitationsCommand extends AbstractCityCommand
{
    public function configure(): void
    {
        $this
            ->setName('verbot:list-limitations')
            ->addArgument('citySlug', InputArgument::REQUIRED, 'Slug of city to show');
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $citySlug = $input->getArgument('citySlug');

        $cities = $this->getCitiesFromCache();

        if (!is_array($cities) || !array_key_exists($citySlug, $cities)) {
            return;
        }

        $city = $cities[$citySlug];

        $table = new Table($output);
        $table->setHeaders([
            'Titel',
            'Description',
        ]);

        /** @var Limitation $limitation */
        foreach ($city->getLimitations() as $limitation) {
            $table->addRow([
                $limitation->getTitle(),
                substr($limitation->getDescription(), 0, 80),
            ]);
        }

        $table->render();
    }
}
