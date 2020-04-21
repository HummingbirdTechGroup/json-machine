<?php

namespace JsonMachine;

use JsonMachine\JsonDecoder\Decoder;

class JsonMachine implements \IteratorAggregate
{
    /**
     * @var \Traversable
     */
    private $bytesIterator;

    /**
     * @var string
     */
    private $jsonPointer;

    /**
     * @var Decoder|null
     */
    private $jsonDecoder;

    /** @var array */
    private $nonDecodedFields;

    /**
     * @param $bytesIterator
     * @param string $jsonPointer
     * @param Decoder $jsonDecoder
     * @param array $nonDecodedFields
     */
    public function __construct($bytesIterator, $jsonPointer = '', $jsonDecoder = null, $nonDecodedFields = [])
    {
        $this->bytesIterator = $bytesIterator;
        $this->jsonPointer = $jsonPointer;
        $this->jsonDecoder = $jsonDecoder;
        $this->nonDecodedFields = $nonDecodedFields;
    }

    /**
     * @param $string
     * @param string $jsonPointer
     * @param Decoder $jsonDecoder
     * @param array $nonDecodedFields
     * @return self
     */
    public static function fromString($string, $jsonPointer = '', $jsonDecoder = null, $nonDecodedFields = [])
    {
        return new static(new StringBytes($string), $jsonPointer, $jsonDecoder, $nonDecodedFields);
    }

    /**
     * @param string $file
     * @param string $jsonPointer
     * @param Decoder $jsonDecoder
     * @param array $nonDecodedFields
     * @return self
     */
    public static function fromFile($file, $jsonPointer = '', $jsonDecoder = null, $nonDecodedFields = [])
    {
        return new static(new StreamBytes(fopen($file, 'r')), $jsonPointer, $jsonDecoder, $nonDecodedFields);
    }

    /**
     * @param resource $stream
     * @param string $jsonPointer
     * @param Decoder $jsonDecoder
     * @param array $nonDecodedFields
     * @return self
     */
    public static function fromStream($stream, $jsonPointer = '', $jsonDecoder = null, $nonDecodedFields = [])
    {
        return new static(new StreamBytes($stream), $jsonPointer, $jsonDecoder, $nonDecodedFields);
    }

    /**
     * @param \Traversable|array $iterable
     * @param string $jsonPointer
     * @param Decoder $jsonDecoder
     * @param array $nonDecodedFields
     * @return self
     */
    public static function fromIterable($iterable, $jsonPointer = '', $jsonDecoder = null, $nonDecodedFields = [])
    {
        return new static($iterable, $jsonPointer, $jsonDecoder, $nonDecodedFields);
    }

    public function getIterator()
    {
        return new Parser(new Lexer($this->bytesIterator), $this->jsonPointer, $this->jsonDecoder, $this->nonDecodedFields);
    }
}
