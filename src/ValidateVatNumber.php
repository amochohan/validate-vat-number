<?php

namespace DrawMyAttention\ValidateVatNumber;

use SoapClient;
use SoapFault;

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
     * ValidateVatNumber constructor.
     */
    public function __construct()
    {
        $this->client = new SoapClient($this->serviceUrl);
    }

    /**
     * @param $vatNumber
     * @return bool
     * @throws SoapFault
     */
    public function validate($vatNumber)
    {
        $vatNumber = $this->cleanVatNumber($vatNumber);

        $countryCode = substr($vatNumber, 0, 2);
        $vatNumber = substr($vatNumber, 2);

        try {

            $this->response = $result = $this->client->checkVat([
                'countryCode' => $countryCode,
                'vatNumber'   => $vatNumber,
            ]);

            return $this;

        } catch (SoapFault $e) {
            throw $e;
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
     * Is the VAT Number provided valid?
     *
     * @return bool
     */
    public function isValid()
    {
        return $this->response->valid;
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
