<?php

declare(strict_types=1);

namespace S3bul\SoapClient;

use SimpleXMLElement;

/**
 * Class SoapXmlElement
 *
 * @author Sebastian Korzeniecki <seba5zer@gmail.com>
 * @package S3bul\SoapClient
 */
class SoapXmlElement extends SimpleXMLElement
{
    /**
     * @param SoapXmlElement $element
     * @return SoapXmlElement[]
     */
    private function valueToArray(SoapXmlElement $element): array
    {
        $count = $element->count();
        $result = [];
        for($i = 0; $i < $count; ++$i) {
            $result[] = $element[$i];
        }

        return $result;
    }

    /**
     * @param string|null $name
     * @return string|SoapXmlElement[]
     */
    public function getValue(string $name = null)
    {
        $result = is_null($name) ? $this : $this->$name;
        return $result->count() > 1 ? $this->valueToArray($result) : strval($result);
    }

}
