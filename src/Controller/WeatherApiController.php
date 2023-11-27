<?php

namespace App\Controller;

use App\Entity\Forecast;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api')]
    public function index(WeatherUtil                            $weatherUtil,
                          #[MapQueryParameter('country')] string $country,
                          #[MapQueryParameter('city')] string    $city,
                          #[MapQueryParameter('format')] string  $format = 'json',
                          #[MapQueryParameter('twig')] bool $twig = false): Response
    {
        $forecast = $weatherUtil->getWeatherForCountryAndCity($city, $country);

        if($twig) {
            if($format === 'json') {
                return $this->render('weather_api/index.json.twig', [
                    'city' => $city,
                    'country' => $country,
                    'forecast' => $forecast,
                ]);
            }else {
                return $this->render('weather_api/index.csv.twig', [
                    'city' => $city,
                    'country' => $country,
                    'forecast' => $forecast,
                ]);
            }
        }

        if ($format === 'csv') {
            $csv = "city,country,date,temperature,temperature_fahrenheit,cloud,atmospheric_pressure\n";
            $csv .= implode("\n",
                array_map(fn(Forecast $f) => sprintf("%s,%s,%s,%s,%s,%s,%s",
                    $city,
                    $country,
                    $f->getDate()->format('Y-m-d'),
                    $f->getTemperature(),
                    $f->getFahrenheit(),
                    $f->getCloud(),
                    $f->getAtmosphericPressure()),
                    $forecast));

            return new Response($csv, 200);
        }
        return $this->json([
            'city' => $city,
            'country' => $country,
            'forecast' => array_map(fn(Forecast $f) => [
                'date' => $f->getDate()->format('Y-m-d'),
                'temperature' => $f->getTemperature(),
                'temperature_fahrenheit' => $f->getFahrenheit(),
                'cloud' => $f->getCloud(),
                'atmospheric_pressure' => $f->getAtmosphericPressure(),
            ], $forecast),
        ]);

    }
}
