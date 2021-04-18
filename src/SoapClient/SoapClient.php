<?php

declare(strict_types=1);

namespace S3bul\SoapClient;

use InvalidArgumentException;
use SoapClient as PhpSoapClient;

/**
 * Class SoapClient
 *
 * @author Sebastian Korzeniecki <seba5zer@gmail.com>
 * @package S3bul\SoapClient
 */
class SoapClient
{
    const OPTION_LOCATION = 'location';
    const OPTION_SOAP_VERSION = 'soap_version';
    const OPTION_TRACE = 'trace';
    const OPTION_STREAM_CONTEXT = 'stream_context';

    private const RESTRICT_OPTIONS = [
        self::OPTION_LOCATION,
        self::OPTION_SOAP_VERSION,
        self::OPTION_TRACE,
        self::OPTION_STREAM_CONTEXT,
    ];

    const DEFAULT_SOAP_VERSION = SOAP_1_1;
    const DEFAULT_TRACE = true;

    /**
     * @var PhpSoapClient|null
     */
    private ?PhpSoapClient $client = null;

    /**
     * @var string|null
     */
    private ?string $wsdl = null;

    /**
     * @var array
     */
    private array $options = [];

    /**
     * @var string|null
     */
    private ?string $location = null;

    /**
     * @var int|null
     */
    private ?int $soapVersion = self::DEFAULT_SOAP_VERSION;

    /**
     * @var bool|null
     */
    private ?bool $trace = self::DEFAULT_TRACE;

    /**
     * @var resource|null
     */
    private $streamContext = null;

    /**
     * @param string|null $wsdl
     * @param array $options
     * @return $this
     */
    public function init(string $wsdl = null, array $options = []): self
    {
        $this->client = new PhpSoapClient($wsdl ?? $this->wsdl, array_merge($this->getOptions(), $options));

        return $this;
    }

    /**
     * @return $this
     */
    public function reset(): self
    {
        $this->client = null;
        $this->wsdl = null;
        $this->options = [];
        $this->location = null;
        $this->soapVersion = self::DEFAULT_SOAP_VERSION;
        $this->trace = self::DEFAULT_TRACE;
        $this->streamContext = null;

        return $this;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        if(!is_null($this->client)) {
            throw new InvalidArgumentException('SoapClient: First call "init" method');
        }

        if(!method_exists($this->client, $name)) {
            throw new InvalidArgumentException("SoapClient: Method \"$name\" doesn't exists");
        }

        return call_user_func_array([$this->client, $name], $arguments);
    }

    /**
     * @return PhpSoapClient|null
     */
    public function getClient(): ?PhpSoapClient
    {
        return $this->client;
    }

    /**
     * @return string|null
     */
    public function getWsdl(): ?string
    {
        return $this->wsdl;
    }

    /**
     * @param string|null $wsdl
     * @return $this
     */
    public function setWsdl(?string $wsdl): self
    {
        $this->wsdl = $wsdl;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * @param string|null $location
     * @return $this
     */
    public function setLocation(?string $location): self
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getSoapVersion(): ?int
    {
        return $this->soapVersion;
    }

    /**
     * @param int|null $soapVersion
     * @return $this
     */
    public function setSoapVersion(?int $soapVersion): self
    {
        $this->soapVersion = $soapVersion;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getTrace(): ?bool
    {
        return $this->trace;
    }

    /**
     * @param bool|null $trace
     * @return $this
     */
    public function setTrace(?bool $trace): self
    {
        $this->trace = $trace;
        return $this;
    }

    /**
     * @return resource|null
     */
    public function getStreamContext()
    {
        return $this->streamContext;
    }

    /**
     * @param resource|array|null $streamContext
     * @return $this
     */
    public function setStreamContext($streamContext): self
    {
        $this->streamContext = !is_array($streamContext) ? stream_context_create($streamContext) : $streamContext;
        return $this;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        $custom = [];
        if(!is_null($this->location)) {
            $custom[self::OPTION_LOCATION] = $this->location;
        }
        if(!is_null($this->soapVersion)) {
            $custom[self::OPTION_SOAP_VERSION] = $this->soapVersion;
        }
        if(!is_null($this->trace)) {
            $custom[self::OPTION_TRACE] = $this->trace;
        }
        if(!is_null($this->streamContext)) {
            $custom[self::OPTION_STREAM_CONTEXT] = $this->streamContext;
        }
        return array_merge($this->options, $custom);
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options): self
    {
        $this->options = [];
        return $this->addOptions($options);
    }

    /**
     * @param array $options
     * @return $this
     */
    public function addOptions(array $options): self
    {
        foreach($options as $key => $value) {
            $this->addOption($key, $value);
        }

        return $this;
    }

    /**
     * @param string $option
     * @param mixed $value
     * @return $this
     * @see SoapClient::addOption()
     */
    public function setOption(string $option, $value): self
    {
        return $this->addOption($option, $value);
    }

    /**
     * @param string $option
     * @param mixed $value
     * @return $this
     */
    public function addOption(string $option, $value): self
    {
        if(in_array($option, self::RESTRICT_OPTIONS)) {
            throw new InvalidArgumentException("SoapClient: Option \"$option\" is restricted");
        }
        $this->options[$option] = $value;

        return $this;
    }


}
