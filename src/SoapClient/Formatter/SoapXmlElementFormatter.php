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

    /**
     * @var int
     */
    private int $options = self::DEFAULT_OPTIONS;

    /**
     * @return $this
     */
    public function reset(): self
    {
        $this->options = self::DEFAULT_OPTIONS;

        return $this;
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
     * @param string $data
     * @return SoapXmlElement
     */
    public function format(string $data): SoapXmlElement
    {
        return new SoapXmlElement($data, $this->options);
    }

}
