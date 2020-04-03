<?php
/*Rev:26.09.18r0*/

class Util
{
    public static function read($stream, $offset, $b8c80c9b7b88905703868c2a2f945074)
    {
        if ($b8c80c9b7b88905703868c2a2f945074 === 0) {
            return '';
        }
        if (fseek($stream, $offset) === 0) {
            $value = fread($stream, $b8c80c9b7b88905703868c2a2f945074);
            if (ftell($stream) - $offset === $b8c80c9b7b88905703868c2a2f945074) {
                return $value;
            }
        }
        throw new InvalidDatabaseException('The MaxMind DB file contains bad data');
    }
}
