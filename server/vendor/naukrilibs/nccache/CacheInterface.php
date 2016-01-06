<?php
namespace nccache;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author rajan
 */
interface CacheInterface
{
    /**
     * Adds a $value to a $key only if $key was not present
     * @param type $key
     * @param type $value
     * @param type $ttl
     */
    public function add($key, $value);

    /**
     * Adds a $value to a $key if $key was not present and updates if present already
     * @param type $key
     * @param type $value
     * @param type $ttl
     */

    public function addUpdate($key, $value);

    /**
     * Adds a list of $key-$value pairs if $key was not present and updates if present already
     * @param type $items eg. array('key1' => 'value1','key2' => 'value2','key3' => 'value3');
     * @param type $ttl
     */
    public function addUpdateMulti($items);

    /**
     * Return a value stored in $key
     * @param type $key
     */
    public function get($key);

    /**
     * Returns TRUE on success or FALSE on failure
     * @param type $key
     */
    public function delete($key);
}

