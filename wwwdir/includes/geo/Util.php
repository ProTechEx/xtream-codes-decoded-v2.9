<?php
/*Rev:26.09.18r0*/

class c71F9a0aA9539E5bBB23F465b5B8A992
{
    public static function Ad87A931cDE9286D705B4954d6b159d3($stream, $Edcf28ccdc0122ea787e348c040427ed, $b8c80c9b7b88905703868c2a2f945074)
    {
        if ($b8c80c9b7b88905703868c2a2f945074 === 0) {
            return '';
        }
        if (fseek($stream, $Edcf28ccdc0122ea787e348c040427ed) === 0) {
            $value = fread($stream, $b8c80c9b7b88905703868c2a2f945074);
            if (ftell($stream) - $Edcf28ccdc0122ea787e348c040427ed === $b8c80c9b7b88905703868c2a2f945074) {
                return $value;
            }
        }
        throw new E5feA4bb1753b166E279e9172ad7B28D('The MaxMind DB file contains bad data');
    }
}
