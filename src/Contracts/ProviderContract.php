<?php

namespace MarceloD\CbpqSkydivers\Contracts;

interface ProviderContract
{
    /**
     * @return Address
     */
    public function getAffiliated($cbpq, HttpClientContract $client);
}
