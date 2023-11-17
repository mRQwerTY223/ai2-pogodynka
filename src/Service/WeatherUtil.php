<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Location;
use App\Entity\Forecast;
use App\Repository\ForecastRepository;
use App\Repository\LocationRepository;

class WeatherUtil
{
    public function __construct(
        private readonly LocationRepository $locationRepository,
        private readonly ForecastRepository $forecastRepository
    )
    {
    }

    /**
     * @return Forecast[]
     */
    public function getWeatherForLocation(Location $location): array
    {
        return $this->forecastRepository->findByLocation($location);
    }

    /**
     * @return Forecast[]
     */
    public function getWeatherForCountryAndCity(string $cityName, string $countryCode): array
    {
        $location = $this->locationRepository->findByCityNameAndCountry($cityName, $countryCode);

        return $this->getWeatherForLocation($location);
    }
}
