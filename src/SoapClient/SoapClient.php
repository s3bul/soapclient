<?php

declare(strict_types=1);

namespace S3bul\SoapClient;

use InvalidArgumentException;
use SimpleXMLElement;
use SoapClient as PhpSoapClient;
use SoapHeader;

/**
 * Class SoapClient
 *
 * @author Sebastian Korzeniecki <seba5zer@gmail.com>
 * @package S3bul\SoapClient
 *
 * @method string|null __doRequest(string $request, string $location, string $action, int $version, bool $oneWay = false)
 * @method array __getCookies()
 * @method array|null __getFunctions()
 * @method string|null __getLastRequest()
 * @method string|null __getLastRequestHeaders()
 * @method string|null __getLastResponse()
 * @method string|null __getLastResponseHeaders()
 * @method array|null __getTypes()
 * @method void __setCookie(string $name, string|null $value = null)
 * @method string|null __setLocation(string $location = '')
 * @method bool __setSoapHeaders(SoapHeader|array|null $headers = null)
 * @method mixed __soapCall(string $name, array $args, array|null $options = null, SoapHeader|array|null $inputHeaders = null, array &$outputHeaders = null)
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
    const DEFAULT_SIMPLE_RESPONSE = true;
    const DEFAULT_SIMPLE_XML_ELEMENT = true;

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
     * @var bool
     */
    private bool $simpleResponse = self::DEFAULT_SIMPLE_RESPONSE;

    /**
     * @var bool
     */
    private bool $simpleXmlElement = self::DEFAULT_SIMPLE_XML_ELEMENT;

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
        $this->simpleResponse = self::DEFAULT_SIMPLE_RESPONSE;
        $this->simpleXmlElement = self::DEFAULT_SIMPLE_XML_ELEMENT;

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    private function checkClient(): void
    {
        if(is_null($this->client)) {
            throw new InvalidArgumentException('SoapClient: First call "init" method');
        }
    }

    /**
     * @param string|SimpleXMLElement $response
     * @return string
     */
    private function getSimpleResponse(string $response)
    {
        $result = preg_replace('/(<\/?)(\w+):([^>]*>)/', '$1$3', $response);
        return $this->simpleXmlElement ? new SimpleXMLElement($result) : $result;
    }

    /**
     * @return string|SimpleXMLElement|null
     */
    public function getLastResponse()
    {
        $this->checkClient();
        $response = $this->client->__getLastResponse();
        return $this->simpleResponse && !is_null($response) ?
            $this->getSimpleResponse($response) : $response;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        $this->checkClient();

        $callName = substr($name, 0, 4) === 'call';

        if(
            (substr($name, 0, 2) === '__' || !$callName) &&
            !method_exists($this->client, $name)
        ) {
            throw new InvalidArgumentException("SoapClient: Method \"$name\" doesn't exists");
        }

        $method = $callName ? substr($name, 4) : $name;

        return call_user_func_array([$this->client, $method], $arguments);
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
        $this->streamContext = is_array($streamContext) ? stream_context_create($streamContext) : $streamContext;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSimpleResponse(): bool
    {
        return $this->simpleResponse;
    }

    /**
     * @param bool $simpleResponse
     * @return $this
     */
    public function setSimpleResponse(bool $simpleResponse): self
    {
        $this->simpleResponse = $simpleResponse;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSimpleXmlElement(): bool
    {
        return $this->simpleXmlElement;
    }

    /**
     * @param bool $simpleXmlElement
     * @return $this
     */
    public function setSimpleXmlElement(bool $simpleXmlElement): self
    {
        $this->simpleXmlElement = $simpleXmlElement;
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
            $this->setOption($key, $value);
        }

        return $this;
    }

    /**
     * @param string $option
     * @param mixed $value
     * @return $this
     */
    public function setOption(string $option, $value): self
    {
        if(in_array($option, self::RESTRICT_OPTIONS)) {
            throw new InvalidArgumentException("SoapClient: Option \"$option\" is restricted");
        }
        $this->options[$option] = $value;

        return $this;
    }

}
