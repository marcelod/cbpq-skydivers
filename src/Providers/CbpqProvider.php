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
        // $response = $client->get('http://localhost/html/cbpq/html_consulta_cbpq' . $cbpq . '.html');

        if (!is_null($response)) {
            $crawler = new Crawler($response);

            $data['error']      = false;
            $data['numberCbpq'] = $cbpq;

            $countError = $crawler->filter('.cbpq-consulta-error')->count();
            if ($countError) {
                $data['error'] = true;
                $data['status'] = $crawler->filter('span.cbpq-consulta-error')->text();
            }
            else{
                $base = "body > div.wrapper > div.container > div > div.col-sm-8 > div:nth-child(3)";

                $base_dados = $base . " > div.col-md-8";

                $status     = $base_dados . " > div:nth-child(1) > div > div > p > span";
                $num_cbpq   = $base_dados . " > div:nth-child(2) > div > div > p";
                $categoria  = $base_dados . " > div:nth-child(3) > div > div > p";
                $atleta     = $base_dados . " > div:nth-child(4) > div > div > p";
                $clube      = $base_dados . " > div:nth-child(5) > div > div > p";
                $federacao  = $base_dados . " > div:nth-child(6) > div > div > p";
                $habilitacao= $base_dados . " > div:nth-child(7) > div > div > p";
                $filiacao   = $base_dados . " > div:nth-child(8) > div > div > p";
                $validade   = $base_dados . " > div:nth-child(9) > div > div > p";

                $image   = $base . " > div.col-md-4 > div > div > img";

                $data['status']        = $crawler->filter($status)->text();
                // $data['numberCbpq']    = $crawler->filter($num_cbpq)->text();
                $data['category']      = $crawler->filter($categoria)->text();
                $data['name']          = $crawler->filter($atleta)->text();
                $data['club']          = $crawler->filter($clube)->text();
                $data['federation']    = $crawler->filter($federacao)->text();
                $data['license']       = $crawler->filter($habilitacao)->text();
                $data['affiliation']   = $crawler->filter($filiacao)->text();
                $data['expiration']    = $crawler->filter($validade)->text();


                $posInitNickname = strpos($data['name'], '(');
                if ($posInitNickname !== false) {
                    $initNickname = $posInitNickname + 1;
                    $nickname = substr($data['name'], $initNickname, -1);

                    $data['nickname'] = $nickname;

                    $endName = -(strlen($data['nickname']) + 2);

                    $name = trim(substr($data['name'], 0, $endName));
                    $data['name'] = $name;
                }

                $data['src_image']     = $crawler->filter($image)->attr('src');
                // $data['image']         = $crawler->filter($image)->image();
            }

            return Affiliated::create(array_map(function ($item) {
                    return urldecode(str_replace('%C2%A0', '', urlencode($item)));
                }, $data));
        }
    }
}
