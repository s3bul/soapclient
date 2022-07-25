<?php

declare(strict_types=1);

namespace S3bul\SoapClient;

use S3bul\SoapClient\Formatter\FormatterInterface;
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
    const OPTION_CLASSMAP = 'classmap';
    const OPTION_CONNECTION_TIMEOUT = 'connection_timeout';
    const OPTION_CACHE_WSDL = 'cache_wsdl';
    const OPTION_USER_AGENT = 'user_agent';
    const OPTION_STREAM_CONTEXT = 'stream_context';

    private const RESTRICT_OPTIONS = [
        self::OPTION_LOCATION,
        self::OPTION_SOAP_VERSION,
        self::OPTION_TRACE,
        self::OPTION_CLASSMAP,
        self::OPTION_STREAM_CONTEXT,
    ];

    const DEFAULT_SOAP_VERSION = SOAP_1_1;
    const DEFAULT_TRACE = false;

    /**
     * @var PhpSoapClient|null
     */
    private ?PhpSoapClient $client = null;

    /**
     * @var string|null
     */
    private ?string $wsdl = null;

    /**
     * @var array<string, mixed>
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
     * @var array<string, string>
     */
    private array $classmap = [];

    /**
     * @var mixed
     */
    private $lastCallResponse = null;

    /**
     * @var FormatterInterface|null
     */
    private ?FormatterInterface $formatter = null;

    /**
     * @param string|null $wsdl
     * @param array<string, mixed> $options
     * @return $this
     */
    public function init(string $wsdl = null, array $options = []): self
    {
        $this->client = new PhpSoapClient($wsdl ?? $this->wsdl, array_merge($this->getOptions(), $options));

        return $this;
    }

    /**
     * @return void
     */
    public function reset(): void
    {
        $this->client = null;
        $this->wsdl = null;
        $this->options = [];
        $this->location = null;
        $this->soapVersion = self::DEFAULT_SOAP_VERSION;
        $this->trace = self::DEFAULT_TRACE;
        $this->streamContext = null;
        $this->classmap = [];
        $this->lastCallResponse = null;
        $this->formatter = null;
    }

    /**
     * @throws SoapException
     */
    private function checkClient(): void
    {
        if(is_null($this->client)) {
            throw new SoapException('SoapClient: First call "init" method', SoapException::INIT_CODE);
        }
    }

    /**
     * @throws SoapException
     */
    private function checkTrace(): void
    {
        if($this->trace !== true) {
            throw new SoapException('SoapClient: First set "trace" to true', SoapException::TRACE_CODE);
        }
    }

    /**
     * @return array<string, string>
     */
    public function getSoapServices(): array
    {
        $this->checkClient();

        $result = [];
        $services = array_unique($this->client->__getFunctions());
        foreach($services as $service) {
            $matches = [];
            $match = preg_match('/(\w+) +(\w+)/', $service, $matches);
            if($match === 1) {
                $result[$matches[2]] = $matches[1];
            }
        }

        return $result;
    }

    /**
     * @return mixed
     */
    public function getLastResponse()
    {
        $this->checkClient();
        $this->checkTrace();
        $response = $this->client->__getLastResponse();

        if(is_null($response)) {
            return null;
        }

        return is_null($this->formatter) ? $response : $this->formatter->format($response, $this->lastCallResponse);
    }

    /**
     * @return mixed
     */
    public function getLastCallResponse()
    {
        $this->checkTrace();

        return $this->lastCallResponse;
    }

    /**
     * @return SoapResponse
     */
    public function getLastSoapResponse(): SoapResponse
    {
        $this->checkTrace();

        return (new SoapResponse())
            ->setData($this->getLastCallResponse())
            ->setResponse($this->getLastResponse());
    }

    /**
     * @param string $name
     * @param mixed[] $arguments
     * @return mixed
     * @throws SoapException
     */
    public function __call(string $name, array $arguments)
    {
        $this->checkClient();

        $isCallMethod = substr($name, 0, 4) === 'call';

        if(
            (substr($name, 0, 2) === '__' || !$isCallMethod) &&
            !method_exists($this->client, $name)
        ) {
            throw new SoapException("SoapClient: Method \"$name\" doesn't exists", SoapException::METHOD_CODE);
        }

        if(substr($name, 0, 9) === '__getLast') {
            $this->checkTrace();
        }

        $method = $isCallMethod ? substr($name, 4) : $name;

        $result = call_user_func_array([$this->client, $method], $arguments);

        if($this->trace === true) {
            $this->lastCallResponse = $result;
        }

        return $result;
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
    public function isTrace(): ?bool
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
     * @return array<string, string>
     */
    public function getClassmap(): array
    {
        return $this->classmap;
    }

    /**
     * @param array<string, string> $classmap
     * @return $this
     */
    public function setClassmap(array $classmap): self
    {
        $this->classmap = [];
        return $this->addClassmap($classmap);
    }

    /**
     * @param array<string, string> $classmap
     * @return $this
     */
    public function addClassmap(array $classmap): self
    {
        foreach($classmap as $key => $name) {
            $this->addOneClassmap($key, $name);
        }

        return $this;
    }

    /**
     * @param string $key
     * @param string $name
     * @return $this
     */
    public function addOneClassmap(string $key, string $name): self
    {
        $this->classmap[$key] = $name;
        return $this;
    }

    /**
     * @return array<string, mixed>
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
        if(count($this->classmap) > 0) {
            $custom[self::OPTION_CLASSMAP] = $this->classmap;
        }
        return array_merge($this->options, $custom);
    }

    /**
     * @param array<string, mixed> $options
     * @return $this
     */
    public function setOptions(array $options): self
    {
        $this->options = [];
        return $this->addOptions($options);
    }

    /**
     * @param array<string, mixed> $options
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
     * @throws SoapException
     */
    public function setOption(string $option, $value): self
    {
        if(in_array($option, self::RESTRICT_OPTIONS)) {
            throw new SoapException("SoapClient: Option \"$option\" is restricted", SoapException::OPTION_CODE);
        }
        $this->options[$option] = $value;

        return $this;
    }

    /**
     * @return FormatterInterface|null
     */
    public function getFormatter(): ?FormatterInterface
    {
        return $this->formatter;
    }

    /**
     * @param FormatterInterface|null $formatter
     * @return $this
     */
    public function setFormatter(?FormatterInterface $formatter): self
    {
        $this->formatter = $formatter;
        return $this;
    }

}
