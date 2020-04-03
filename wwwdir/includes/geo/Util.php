<?php

class Util
{
    public static function read($stream, $offset, $numberOfBytes)
    {
        if ($numberOfBytes === 0) {
            return '';
        }
        if (fseek($stream, $offset) === 0) {
            $value = fread($stream, $numberOfBytes);
            if (ftell($stream) - $offset === $numberOfBytes) {
                return $value;
            }
        }
        throw new InvalidDatabaseException('The MaxMind DB file contains bad data');
    }
}
