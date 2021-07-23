#!/bin/bash

WD=`pwd`
PORT=16254
apache=`command -v httpd`

php=php
if [[ -x /usr/bin/php8 ]]; then php=php8; fi

cd www
if [[ "" == $apache ]]; then
  php=php
  if [[ -x /usr/bin/php8 ]]; then php=php8; fi

  echo "this application is available at http://localhost:$PORT"
  $php -S localhost:$PORT _mvp.php

else
  data="`pwd`/../src/application/data"
  error_log="$data/error.log"
  config="$data/httpd.conf"
  [[ ! -f $error_log ]] || rm $error_log
  if [[ ! -f $config ]]; then
    cp $WD/httpd-minimal.conf $config
    echo "<Directory `pwd`>" >>$config
    echo "  AllowOverride all" >>$config
    echo "  Require all granted" >>$config
    echo "</Directory>" >>$config


  fi

  echo "this application is available at http://localhost:$PORT"
  httpd  -D FOREGROUND \
    -f $config \
    -c "DocumentRoot `pwd`" \
    -c "Listen $PORT" \
    -c "ErrorLog $error_log" \
    -c "CustomLog $data/access.log combined" \
    -c "PidFile $data/httpd.pid"

fi
cd $WD
