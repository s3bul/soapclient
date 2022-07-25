<?php

declare(strict_types=1);

namespace S3bul\SoapClient\Formatter;

use S3bul\SoapClient\SoapXmlElement;

/**
 * Class BadSoapXmlElementFormatter
 *
 * @author Sebastian Korzeniecki <sebastian.korzeniecki@sprint.pl>
 * @package S3bul\SoapClient\Formatter
 */
class BadSoapXmlElementFormatter implements FormatterInterface
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
     * @param string $response
     * @param mixed $data
     * @return SoapXmlElement
     */
    public function format(string $response, $data): SoapXmlElement
    {
        $result = preg_replace('/(<\/?)(\w+):([^>]*>)/', '$1$2$3', $response);
        return $this->soapXmlElementFormatter->format($result, $data);
    }

}
