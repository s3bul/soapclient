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
     * @param string $data
     * @return string
     */
    public function format(string $data): string;

}
