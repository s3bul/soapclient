<?php

declare(strict_types=1);

namespace S3bul\SoapClient\Formatter;

/**
 * Interface FormatterInterface
 *
 * @author Sebastian Korzeniecki <seba5zer@gmail.com>
 * @package S3bul\SoapClient\Formatter
 */
interface FormatterInterface
{
    /**
     * @param string $response
     * @param mixed $data
     * @return mixed
     */
    public function format(string $response, $data = null);

}
