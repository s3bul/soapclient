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
    const INIT_CODE = 100;
    const TRACE_CODE = 200;
    const METHOD_CODE = 300;
    const OPTION_CODE = 400;

}
