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
     * @var string|SoapXmlElement|null
     */
    private $xml;

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
     * @return string|SoapXmlElement|null
     */
    public function getXml()
    {
        return $this->xml;
    }

    /**
     * @param string|SoapXmlElement|null $xml
     * @return $this
     */
    public function setXml($xml): self
    {
        $this->xml = $xml;
        return $this;
    }

}