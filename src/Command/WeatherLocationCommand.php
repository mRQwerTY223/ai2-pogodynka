<?php

namespace App\Command;

use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Repository\LocationRepository;

#[AsCommand(
    name: 'weather:location',
    description: 'Display forecast for specific location',
)]
class WeatherLocationCommand extends Command
{
    public function __construct(private readonly LocationRepository $locationRepository,
                                private readonly WeatherUtil        $weatherUtil
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('id', InputArgument::REQUIRED, 'Location ID');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $locationId = $input->getArgument('id');
        $location = $this->locationRepository->find($locationId);

        $forecast = $this->weatherUtil->getWeatherForLocation($location);
        $io->writeln(sprintf('City: %s, country code %s.', $location->getCity(), $location->getCountry()));
        $table = new Table($io);
        $table->setHeaders(['Date', 'Temperature', 'Cloud', 'Atmospheric Pressure']);

        foreach ($forecast as $entry) {
            $table->addRow([
                $entry->getDate()->format('Y-m-d'),
                $entry->getTemperature(),
                $entry->getCloud(),
                $entry->getAtmosphericPressure()
            ]);
        }

        $table->render();

        return Command::SUCCESS;
    }
}
