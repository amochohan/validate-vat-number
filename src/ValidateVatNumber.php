<?php

namespace DrawMyAttention\ValidateVatNumber;

use SoapFault;
use SoapClient;
use DrawMyAttention\ValidateVatNumber\Exceptions\VatValidatorServiceUnavailableException;

class ValidateVatNumber
{
    /**
     * @var string
     */
    protected $serviceUrl = 'http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl';

    /**
     * @var SoapClient
     */
    private $client;

    /**
     * @var null
     */
    private $response = null;

    /**
     * @param $vatNumber
     * @return bool
     * @throws VatValidatorServiceUnavailableException
     */
    public function isValid($vatNumber)
    {
        try {
            $this->client = new SoapClient($this->serviceUrl);
            $vatNumber = $this->cleanVatNumber($vatNumber);

            $countryCode = substr($vatNumber, 0, 2);
            $vatNumber = substr($vatNumber, 2);

            $this->response = $this->client->checkVat([
                'countryCode' => $countryCode,
                'vatNumber'   => $vatNumber,
            ]);

            return $this->response->valid;

        } catch (SoapFault $e) {
            throw new VatValidatorServiceUnavailableException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Return the full response received from the api.
     *
     * @return null
     */
    public function response()
    {
        return $this->response;
    }

    /**
     * Get the country code the company is registered in.
     *
     * @return string
     */
    public function countryCode()
    {
        return $this->response->countryCode;
    }

    /**
     * Get the company name.
     *
     * @return string
     */
    public function company()
    {
        return ucwords(strtolower($this->response->name));
    }

    /**
     * Get the company address.
     *
     * @return array
     */
    public function address()
    {
        return explode("\n", ucwords(strtolower($this->response->address)));
    }

    /**
     * Clean up the vat number provided.
     * 
     * @param string $vatNumber
     * @return string mixed
     */
    public function cleanVatNumber($vatNumber)
    {
        return str_replace([' ', '-', '.', ','], '', trim($vatNumber));
    }
}
