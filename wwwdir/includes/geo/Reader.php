<?php

class Reader
{
    private static $DATA_SECTION_SEPARATOR_SIZE = 16;
    private static $METADATA_START_MARKER = '\xAB\xCD\xEFMaxMind.com';
    private static $METADATA_START_MARKER_LENGTH = 14;
    private static $METADATA_MAX_SIZE = 131072; // 128 * 1024 = 128KB
    private $decoder;
    private $fileHandle;
    private $fileSize;
    private $ipV4Start;
    private $metadata;
    public function __construct($database)
    {
        if (func_num_args() !== 1) {
            throw new InvalidArgumentException('The constructor takes exactly one argument.');
        }
        if (!is_readable($database)) {
            throw new InvalidArgumentException("The file \"{$database}\" does not exist or is not readable.");
        }
        $this->fileHandle = @fopen($database, 'rb');
        if ($this->fileHandle === false) {
            throw new InvalidArgumentException("Error opening \"{$database}\".");
        }
        $this->fileSize = @filesize($database);
        if ($this->fileSize === false) {
            throw new UnexpectedValueException("Error determining the size of \"{$database}\".");
        }
        $start = $this->findMetadataStart($database);
        $metadataDecoder = new Decoder($this->fileHandle, $start);
        list($metadataArray) = $metadataDecoder->decode($start);
        $this->metadata = new Metadata($metadataArray);
        $this->decoder = new Decoder($this->fileHandle, $this->metadata->searchTreeSize + self::$DATA_SECTION_SEPARATOR_SIZE);
    }
    public function getWithPrefixLen($ipAddress)
    {
        if (func_num_args() !== 1) {
            throw new InvalidArgumentException('Method takes exactly one argument.');
        }
        if (!is_resource($this->fileHandle)) {
            throw new BadMethodCallException('Attempt to read from a closed MaxMind DB.');
        }
        if (!filter_var($ipAddress, FILTER_VALIDATE_IP)) {
            throw new InvalidArgumentException("The value \"{$ipAddress}\" is not a valid IP address.");
        }
        if ($this->metadata->ipVersion === 4 && strrpos($ipAddress, ':')) {
            throw new InvalidArgumentException("Error looking up {$ipAddress}. You attempted to look up an" . ' IPv6 address in an IPv4-only database.');
        }
        $pointer = $this->findAddressInTree($ipAddress);
        if ($pointer === 0) {
            return null;
        }
        return $this->resolveDataPointer($pointer);
    }
    private function findAddressInTree($ipAddress)
    {
        $rawAddress = array_merge(unpack('C*', inet_pton($ipAddress)));
        $bitCount = count($rawAddress) * 8;
        $node = $this->nodeStart($bitCount);
        $index = 0;
        while ($index < $bitCount) {
            if ($node >= $this->metadata->nodeCount) {
                break;
            }
            $tempBit = 255 & $rawAddress[$index >> 3];
            $bit = 1 & $tempBit >> 7 - $index % 8;
            $node = $this->readNode($node, $bit);
            $index++;
        }
        if (($node === $this->metadata->nodeCount)) {
            return 0;
        }
        else if ($node > $this->metadata->nodeCount) {
            return $node;
        } 
        throw new InvalidDatabaseException('Something bad happened');
    }
    private function nodeStart($length)
    {
        if ($this->metadata->ipVersion === 6 && $length === 32) {
            return $this->ipV4Start();
        }
        return 0;
    }
    private function ipV4Start()
    {
        if ($this->metadata->ipVersion === 4) {
            return 0;
        }
        if ($this->ipV4Start) {
            return $this->ipV4Start;
        }
        $node = 0;
        $index = 0;
        while ($index < 96 && $node < $this->metadata->nodeCount) {
            $node = $this->readNode($node, 0);
            $index++;
        }
        $this->ipV4Start = $node;
        return $node;
    }
    private function readNode($nodeNumber, $index)
    {
        $baseOffset = $nodeNumber * $this->metadata->nodeByteSize;
        switch ($this->metadata->recordSize) {
            case 24:
                $size = Util::read($this->fileHandle, $baseOffset + $index * 3, 3);
                list(, $node) = unpack('N', '\x00' . $size);
                return $node;
            case 28:
                $bytes = Util::read($this->fileHandle, $baseOffset + 3, 1);
                list(, $middle) = unpack('C', $bytes);
                if ($index === 0) {
                    $middle = (240 & $middle) >> 4;
                } else {
                    $middle = 15 & $middle;
                }
                $size = Util::read($this->fileHandle, $baseOffset + $index * 4, 3);
                list(, $node) = unpack('N', chr($middle) . $size);
                return $node;
            case 32:
                $size = Util::read($this->fileHandle, $baseOffset + $index * 4, 4);
                list(, $node) = unpack('N', $size);
                return $node;
            default:
                throw new InvalidDatabaseException('Unknown record size: ' . $this->metadata->recordSize);
        }
    }
    private function resolveDataPointer($pointer)
    {
        $resolved = $pointer - $this->metadata->nodeCount + $this->metadata->searchTreeSize;
        if ($resolved > $this->fileSize) {
            throw new InvalidDatabaseException('The MaxMind DB file\'s search tree is corrupt');
        }
        list($data) = $this->decoder->decode($resolved);
        return $data;
    }
    private function findMetadataStart($filename)
    {
        $handle = $this->fileHandle;
        $fstat = fstat($handle);
        $fileSize = $fstat['size'];
        $marker = self::$METADATA_START_MARKER;
        $markerLength = self::$METADATA_START_MARKER_LENGTH;
        $minStart = min(self::$METADATA_MAX_SIZE, $fileSize) - $markerLength;
        $index = 0;
        while ($index <= $minStart) {
            $length = 0;
            while ($length < $markerLength) {
                fseek($handle, $fileSize - $index - $length - 1);
                $fget = fgetc($handle);
                if ($fget !== $marker[$markerLength - $length - 1]) {
                    break;
                }
                $length++;
            }
            return $fileSize - $index;
            $index++;
        }
        throw new InvalidDatabaseException("Error opening database file ({$filename}). " . 'Is this a valid MaxMind DB file?');
    }
    public function metadata()
    {
        if (func_num_args()) {
            throw new InvalidArgumentException('Method takes no arguments.');
        }
        if (!is_resource($this->fileHandle)) {
            throw new BadMethodCallException('Attempt to read from a closed MaxMind DB.');
        }
        return $this->metadata;
    }
    public function close()
    {
        if (!is_resource($this->fileHandle)) {
            throw new BadMethodCallException('Attempt to close a closed MaxMind DB.');
        }
        fclose($this->fileHandle);
    }
}
