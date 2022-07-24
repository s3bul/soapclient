<?php

declare(strict_types=1);

namespace S3bul\SoapClient;

/**
 * Class SoapResponse
 *
 * @author Sebastian Korzeniecki <seba5zer@gmail.com>
 * @package S3bul\SoapClient
 */
class SoapResponse
{
    /**
     * @var mixed
     */
    private $data;

    /**
     * @var mixed
     */
    private $response;

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     * @return $this
     */
    public function setData($data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return mixed
     * @deprecated Use {@see getResponse}
     */
    public function getXml()
    {
        return $this->getResponse();
    }

    /**
     * @param mixed $xml
     * @return $this
     * @deprecated Use {@see setResponse}
     */
    public function setXml($xml): self
    {
        return $this->setResponse($xml);
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param mixed $response
     * @return $this
     */
    public function setResponse($response): self
    {
        $this->response = $response;
        return $this;
    }

}
