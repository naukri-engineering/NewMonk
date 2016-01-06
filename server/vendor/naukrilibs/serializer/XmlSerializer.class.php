<?php

/**
 * Description of XmlSerializer
 *
 * @author Rajeev Rai
 */
class XmlSerializer implements ISerializer
{
	public function serialize($data, $rootNode = 'root') {
		if (is_array($data) && count($data) > 0) {
			$xml = ncArray2XML::createXML($rootNode , $data);
			return$xml->saveXML();
		}
		return '';
	}

	public function unserialize($data) {
		if ($data) {
			return ncXML2Array::createArray($data);
		}
		return '';
	}
}
