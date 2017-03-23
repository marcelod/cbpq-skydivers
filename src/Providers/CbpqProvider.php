<?php

namespace MarceloD\CbpqSkydivers\Providers;

use MarceloD\CbpqSkydivers\Affiliated;
use MarceloD\CbpqSkydivers\Contracts\HttpClientContract;
use MarceloD\CbpqSkydivers\Contracts\ProviderContract;
use Symfony\Component\DomCrawler\Crawler;

class CbpqProvider implements ProviderContract
{
    /**
     * @return Affiliated
     */
    public function getAffiliated($cbpq, HttpClientContract $client)
    {
        $response = $client->get('https://www.cbpq.org.br/site/filiados/consulta-licenca?cbpq=' . $cbpq);

        if (!is_null($response)) {
            $crawler = new Crawler($response);
            var_dump($crawler);
            die();

            /*$message = $crawler->filter('div.ctrlcontent p')->html();

            if ($message == 'DADOS ENCONTRADOS COM SUCESSO.') {
                $tr = $crawler->filter('table.tmptabela tr:nth-child(2)');

                $params['zipcode'] = $cep;
                $params['street'] = $tr->filter('td:nth-child(1)')->html();
                $params['neighborhood'] = $tr->filter('td:nth-child(2)')->html();

                $aux = explode('/', $tr->filter('td:nth-child(3)')->html());
                $params['city'] = $aux[0];
                $params['state'] = $aux[1];

                $aux = explode(' - ', $params['street']);
                $params['street'] = (count($aux) == 2) ? $aux[0] : $params['street'];

                return Affiliated::create(array_map(function ($item) {
                    return urldecode(str_replace('%C2%A0', '', urlencode($item)));
                }, $params));
            }*/
        }
    }
}
