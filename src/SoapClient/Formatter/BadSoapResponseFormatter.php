<?php

declare(strict_types=1);

namespace S3bul\SoapClient\Formatter;

use S3bul\SoapClient\SoapXmlElement;

/**
 * Class BadSoapResponseFormatter
 *
 * @author Sebastian Korzeniecki <sebastian.korzeniecki@sprint.pl>
 * @package S3bul\SoapClient\Formatter
 */
class BadSoapResponseFormatter implements FormatterInterface
{
    /**
     * @var SoapXmlElementFormatter
     */
    private SoapXmlElementFormatter $soapXmlElementFormatter;

    /**
     * @param SoapXmlElementFormatter $soapXmlElementFormatter
     */
    public function __construct(SoapXmlElementFormatter $soapXmlElementFormatter)
    {
        $this->soapXmlElementFormatter = $soapXmlElementFormatter;
    }

    /**
     * @param string $data
     * @return SoapXmlElement
     */
    public function format(string $data): SoapXmlElement
    {
        //TODO: add regex replace
        $result = $data;
        return $this->soapXmlElementFormatter->format($result);
    }

}