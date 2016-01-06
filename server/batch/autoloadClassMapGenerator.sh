#!/bin/bash
PROJECT_PATH="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"/..
$PROJECT_PATH/composer.phar dump-autoload --optimize
