<?php

declare(strict_types=1);

namespace S3bul\SoapClient\Formatter;

use S3bul\SoapClient\SoapXmlElement;

/**
 * Class SoapXmlElementFormatter
 *
 * @author Sebastian Korzeniecki <seba5zer@gmail.com>
 * @package S3bul\SoapClient\Formatter
 */
class SoapXmlElementFormatter implements FormatterInterface
{
    const DEFAULT_OPTIONS = 0;
    const DEFAULT_DATA_IS_URL = false;
    const DEFAULT_NAMESPACE_OR_PREFIX = '';
    const DEFAULT_IS_PREFIX = false;

    /**
     * @var int
     */
    private int $options;

    /**
     * @var bool
     */
    private bool $dataIsURL;

    /**
     * @var string
     */
    private string $namespaceOrPrefix;

    /**
     * @var bool
     */
    private bool $isPrefix;

    /**
     * @param int|null $options
     * @param bool|null $dataIsURL
     * @param string|null $namespaceOrPrefix
     * @param bool|null $isPrefix
     */
    public function __construct(
        int    $options = null,
        bool   $dataIsURL = null,
        string $namespaceOrPrefix = null,
        bool   $isPrefix = null
    )
    {
        $this->options = $options ?? self::DEFAULT_OPTIONS;
        $this->dataIsURL = $dataIsURL ?? self::DEFAULT_DATA_IS_URL;
        $this->namespaceOrPrefix = $namespaceOrPrefix ?? self::DEFAULT_NAMESPACE_OR_PREFIX;
        $this->isPrefix = $isPrefix ?? self::DEFAULT_IS_PREFIX;
    }

    /**
     * @param string $response
     * @param mixed $data
     * @return SoapXmlElement
     */
    public function format(string $response, $data = null): SoapXmlElement
    {
        return new SoapXmlElement(
            $response,
            $this->options,
            $this->dataIsURL,
            $this->namespaceOrPrefix,
            $this->isPrefix,
        );
    }

    /**
     * @return int
     */
    public function getOptions(): int
    {
        return $this->options;
    }

    /**
     * @param int $options
     * @return $this
     */
    public function setOptions(int $options): self
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @param int $option
     * @return $this
     */
    public function addOption(int $option): self
    {
        $this->options |= $option;
        return $this;
    }

    /**
     * @param int $option
     * @return $this
     */
    public function removeOption(int $option): self
    {
        $this->options ^= $option;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDataIsURL(): bool
    {
        return $this->dataIsURL;
    }

    /**
     * @param bool $dataIsURL
     * @return $this
     */
    public function setDataIsURL(bool $dataIsURL): self
    {
        $this->dataIsURL = $dataIsURL;
        return $this;
    }

    /**
     * @return string
     */
    public function getNamespaceOrPrefix(): string
    {
        return $this->namespaceOrPrefix;
    }

    /**
     * @param string $namespaceOrPrefix
     * @return $this
     */
    public function setNamespaceOrPrefix(string $namespaceOrPrefix): self
    {
        $this->namespaceOrPrefix = $namespaceOrPrefix;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPrefix(): bool
    {
        return $this->isPrefix;
    }

    /**
     * @param bool $isPrefix
     * @return $this
     */
    public function setIsPrefix(bool $isPrefix): self
    {
        $this->isPrefix = $isPrefix;
        return $this;
    }

}
