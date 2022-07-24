<?php

declare(strict_types=1);

namespace S3bul\SoapClient;

use Exception;

/**
 * Class SoapException
 *
 * @author Sebastian Korzeniecki <seba5zer@gmail.com>
 * @package S3bul\SoapClient
 */
class SoapException extends Exception
{
    const INIT_EXCEPTION = 100;
    const TRACE_EXCEPTION = 200;
    const METHOD_EXCEPTION = 300;
    const OPTION_EXCEPTION = 400;

}
