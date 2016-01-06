<?php

/**
 * Description of rmSerializer
 *
 * @author Rajeev Rai
 */
interface ISerializer
{
    public function serialize($data);

    public function unserialize($data);
}

