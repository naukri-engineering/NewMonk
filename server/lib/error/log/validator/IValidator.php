<?php

namespace NewMonk\lib\error\log\validator;

interface IValidator
{
    public function filter($errorLogs);
}
