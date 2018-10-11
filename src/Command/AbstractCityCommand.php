<?php declare(strict_types=1);

namespace App\Command;

use App\Limitation\Entity\City;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;

abstract class AbstractCityCommand extends Command
{
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
