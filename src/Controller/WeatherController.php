<?php

namespace App\Controller;

use App\Repository\ForecastRepository;
use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{
    #[Route('/weather/{cityName}/{countryCode?}', name: 'app_weather')]
    public function city(string $cityName, LocationRepository $locationRepository, ForecastRepository $forecastRepository, ?string $countryCode = null): Response
    {
        if ($countryCode === null) {
            $countryCode = 'PL';
        }
        $location = $locationRepository->findByCityNameAndCountry($cityName, $countryCode);
        $forecast = $forecastRepository->findByLocation($location);

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'forecast' => $forecast,
        ]);
    }

}
