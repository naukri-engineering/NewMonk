<?php

/**
 * Description of JsonSerializer
 *
 * @author Rajeev Rai
 */
class JsonUrlSerializer implements ISerializer
{
    public function serialize($data) {
        if (is_array($data) && count($data) > 0) {
            array_walk_recursive($data, array($this, 'urlEncode'));
            return json_encode($data);
        }

        return '';
    }

    public function unserialize($data) {
        if ($data) {
            $decodedData = json_decode($data, true);
            array_walk_recursive($decodedData, array($this, 'urlDecode'));

            return $decodedData;
        }

        return '';
    }

    private function urlEncode(&$item, &$key) {
        $item = urlencode($item);
    }

    private function urlDecode(&$item, &$key) {
        $item = urldecode($item);
    }
}

