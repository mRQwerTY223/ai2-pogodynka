<?php

namespace App\Tests\Entity;

use App\Entity\Forecast;
use PHPUnit\Framework\TestCase;

class ForecastTest extends TestCase
{
    public function dataGetFahrenheit(): array
    {
        return [
            [29.27, 84.686],
            [-23.78, -10.804],
            [6.15, 43.07],
            [35.40, 95.72],
            [-28.29, -18.922],
            [12.55, 54.59],
            [22.17, 71.906],
            [33.70, 92.66],
            [23.66, 74.588],
            [-26.81, -16.258]
        ];

    }

    /**
     * @dataProvider dataGetFahrenheit
     */
    public function testGetFahrenheit($celsius, $expectedFahrenheit): void
    {
        $forecast = new Forecast();
        $forecast->setTemperature($celsius);
        $this->assertEquals($expectedFahrenheit, $forecast->getFahrenheit());
    }
}
