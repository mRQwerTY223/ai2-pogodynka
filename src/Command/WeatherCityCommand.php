<?php

namespace App\Command;

use App\Repository\LocationRepository;
use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:city',
    description: 'Display forecast for specific city in country',
)]
class WeatherCityCommand extends Command
{
    public function __construct(
        private readonly LocationRepository $locationRepository,
        private readonly WeatherUtil $weatherUtil
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('country_code', InputArgument::REQUIRED, 'Country code [eg. PL]')
            ->addArgument('city_name', InputArgument::REQUIRED, 'City name [eg. Szczecin]')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $countryCode = $input->getArgument('country_code');
        $cityName = $input->getArgument('city_name');

        $location = $this->locationRepository->findOneBy([
            'country' => $countryCode,
            'city' => $cityName,
        ]);

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
