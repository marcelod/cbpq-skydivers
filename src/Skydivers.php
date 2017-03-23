<?php

namespace MarceloD\CbpqSkydivers;

use MarceloD\CbpqSkydivers\Clients\CurlHttpClient;
use MarceloD\CbpqSkydivers\Contracts\HttpClientContract;
use MarceloD\CbpqSkydivers\Contracts\ProviderContract;
use MarceloD\CbpqSkydivers\Exceptions\CbpqSkydiversInvalidParameterException;
use MarceloD\CbpqSkydivers\Exceptions\CbpqSkydiversTimeoutException;
use MarceloD\CbpqSkydivers\Providers\CbpqProvider;

/**
 * Class to query CEP.
 */
class Skydivers
{
    /**
     * @var HttpClientContract
     */
    private $client;

    /**
     * @var ProviderContract[]
     */
    private $providers = [];

    /**
     * @var int
     */
    private $timeout = 5;

    /**
     * CepGratis constructor.
     */
    public function __construct()
    {
        $this->client = new CurlHttpClient();
    }

    /**
     * Search CEP on all providers.
     *
     * @param string $cbpq CEP
     *
     * @return Affiliated
     */
    public static function search($cbpq)
    {
        $cbpq = new self();
        $cbpq->addProvider(new CbpqProvider());

        $affiliated = $cbpq->resolve($cbpq);

        return $affiliated;
    }

    /**
     * Performs provider CEP search.
     *
     * @param string $cbpq CEP
     *
     * @return Affiliated
     */
    public function resolve($cbpq)
    {
        if (strlen($cbpq) == 0 && filter_var($cbpq, FILTER_VALIDATE_INT) === false) {
            throw new CbpqSkydiversInvalidParameterException('CBPQ is invalid');
        }

        if (count($this->providers) == 0) {
            throw new CbpqSkydiversInvalidParameterException('No providers were informed');
        }

        /*
         * Execute
         */
        $time = time();

        do {
            foreach ($this->providers as $provider) {
                $affiliated = $provider->getAffiliated($cbpq, $this->client);
            }

            if ((time() - $time) >= $this->timeout) {
                throw new CbpqSkydiversTimeoutException("Maximum execution time of $this->timeout seconds exceeded in PHP");
            }
        } while (is_null($affiliated));

        /*
         * Return
         */
        return $affiliated;
    }

    /**
     * Set client http.
     *
     * @param HttpClientContract $client
     */
    public function setClient(HttpClientContract $client)
    {
        $this->client = $client;
    }

    /**
     * Set array providers.
     *
     * @param HttpClientContract $client
     */
    public function addProvider(ProviderContract $provider)
    {
        $this->providers[] = $provider;
    }

    /**
     * Set timeout.
     *
     * @param int $timeout
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }
}
