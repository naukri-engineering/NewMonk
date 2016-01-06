<?php

/**
 * Description of JsonSerializer
 *
 * @author Rajeev Rai
 */
class JsonSerializer implements ISerializer
{
    public function serialize($data) {
        if (is_array($data) && count($data) > 0) {
            array_walk_recursive($data, array($this, 'utfEncode'));
            return json_encode($data);
        }

        return '';
    }

    public function unserialize($data) {
        if ($data) {
            $decodedData = json_decode($data, true);
            array_walk_recursive($decodedData, array($this, 'utfDecode'));

            return $decodedData;
        }

        return '';
    }

    private function utfEncode(&$item, &$key) {
        $item = utf8_encode($item);
    }

    private function utfDecode(&$item, &$key) {
        $item = utf8_decode($item);
    }
}

