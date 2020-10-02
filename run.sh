#!/bin/bash

WD=`pwd`
PORT=$[RANDOM%1000+1024]

cd www
echo "this application is available at http://localhost:$PORT"
php -S localhost:$PORT _mvp.php
cd $WD
