<?php

declare(strict_types=1);

namespace Tilda\Tasks;

use GeoIp2\Database\Reader;

final class ThirdTask implements TaskInterface
{
    private string $description = '
        Вы работаете в компании, присутствующей в нескольких городах РФ. 
        На сайте компании есть страница с контактной информацией. 
        Маркетолог поставил задачу и уехал, к его приезду задача должна быть реализована.
        На страницу контактов заходят люди из разных городов, нужно чтобы они видели телефон из своего города. 
        По умолчанию, в HTML-страницы прописан телефон 8-800-DIGITS. Телефон размещен в верху и внизу страницы.
        
        Вот и все что рассказал маркетолог прежде чем уехать.
    ';

    private const string HTML = '<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <title>Контакты</title>
</head>
<body>
    <div id="header">8-800-DIGITS</div>
    <div id="main"></div>
    <div id="footer">8-800-DIGITS</div>
</body>
</html>';

    private const array MAP_CITY_PHONE = ['Moscow' => '2000-700', 'Samara' => '2000-701', 'Kostroma' => '2000-702'];

    private const string DEFAULT_CITY = 'Moscow';
    private const string GEOIP_DB_PATH = './tasks/GeoLite2-City.mmdb';

    public function getDescription(): string
    {
        return $this->description;
    }

    public function execute(): void
    {
        $request = $this->generateRequestInput();

        $city = $request['query']['userCity'] ?? $request['cookies']['userCity'];
        if ($city === null) {
            try {
                $cityDbReader = new Reader(self::GEOIP_DB_PATH);
                $record = $cityDbReader->city($request['client']['ip']);
                $city = $record->city->name;
            } catch (\Throwable $e) {
            }
        }
        if ($city === null || !isset(self::MAP_CITY_PHONE[$city])) {
            $city = self::DEFAULT_CITY;
        }

        echo str_replace('8-800-DIGITS', '8-800-' . self::MAP_CITY_PHONE[$city], self::HTML);
    }

    private function generateRequestInput():array {
        $cities = [null, 'Moscow', 'Samara', 'Kostroma'];
        $ips = ['91.218.114.206', '188.162.37.245', '46.42.49.245'];

        return [
            'query' => ['userCity' => $cities[array_rand($cities)]],
            'cookies' => ['userCity' => $cities[array_rand($cities)]],
            'client' => ['ip' => $ips[array_rand($ips)]],
        ];
    }

}