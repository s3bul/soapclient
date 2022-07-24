<?php

declare(strict_types=1);

namespace S3bul\SoapClient\Formatter;

use S3bul\SoapClient\SoapXmlElement;

/**
 * Class SoapXmlElementFormatter
 *
 * @author Sebastian Korzeniecki <seba5zer@gmail.com>
 * @package S3bul\SoapClient\Formatter
 */
class SoapXmlElementFormatter implements FormatterInterface
{
    const DEFAULT_SOAP_XML_OPTIONS = 0;

    /**
     * @var int
     */
    private int $soapXmlOptions = self::DEFAULT_SOAP_XML_OPTIONS;

    /**
     * @return $this
     */
    public function reset(): self
    {
        $this->soapXmlOptions = self::DEFAULT_SOAP_XML_OPTIONS;

        return $this;
    }

    /**
     * @return int
     */
    public function getSoapXmlOptions(): int
    {
        return $this->soapXmlOptions;
    }

    /**
     * @param int $soapXmlOptions
     * @return $this
     */
    public function setSoapXmlOptions(int $soapXmlOptions): self
    {
        $this->soapXmlOptions = $soapXmlOptions;
        return $this;
    }

    /**
     * @param int $soapXmlOption
     * @return $this
     */
    public function addSoapXmlOption(int $soapXmlOption): self
    {
        $this->soapXmlOptions |= $soapXmlOption;
        return $this;
    }

    /**
     * @param int $soapXmlOption
     * @return $this
     */
    public function removeSoapXmlOption(int $soapXmlOption): self
    {
        $this->soapXmlOptions ^= $soapXmlOption;
        return $this;
    }

    /**
     * @param string $data
     * @return SoapXmlElement
     */
    public function format(string $data): SoapXmlElement
    {
        return new SoapXmlElement($data, $this->soapXmlOptions);
    }

}
