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
     * @var string|null
     */
    private ?string $responseName = null;

    /**
     * @var int
     */
    private int $soapXmlOptions = self::DEFAULT_SOAP_XML_OPTIONS;

    /**
     * @return $this
     */
    public function reset(): self
    {
        $this->responseName = null;
        $this->soapXmlOptions = self::DEFAULT_SOAP_XML_OPTIONS;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getResponseName(): ?string
    {
        return $this->responseName;
    }

    /**
     * @param string|null $responseName
     * @return $this
     */
    public function setResponseName(?string $responseName): self
    {
        $this->responseName = $responseName;
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
     * @param SoapXmlElement $element
     * @return SoapXmlElement
     */
    private function normalizeSoapXmlElement(SoapXmlElement $element): SoapXmlElement
    {
        $ucResponseName = ucfirst($this->responseName ?? '');
        $lcResponseName = lcfirst($this->responseName ?? '');

        return $this->responseName !== null ?
            ($element->{$this->responseName} ?? $element->{$ucResponseName} ?? $element->{$lcResponseName} ?? $element) :
            $element;
    }

    /**
     * @param string $data
     * @return SoapXmlElement
     */
    public function format(string $data): SoapXmlElement
    {
        $result = new SoapXmlElement($data, $this->soapXmlOptions);
        return $this->normalizeSoapXmlElement($result);
    }

}
