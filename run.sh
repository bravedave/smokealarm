#!/bin/bash

WD=`pwd`
PORT=16254
if [[ -x /usr/bin/php8 ]]; then php=php8; fi

cd www
echo "this application is available at http://localhost:$PORT"
$php -S localhost:$PORT _mvp.php
cd $WD
