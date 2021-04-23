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
     * @param string|null $name
     * @return string
     */
    public function getValue(string $name = null): string
    {
        return strval(is_null($name) ? $this : $this->$name);
    }

}
