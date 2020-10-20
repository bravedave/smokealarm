#!/bin/bash

WD=`pwd`

if [ -f src/application/data/smoke-alarm-property-status.csv ]
then
  echo 'import exists ..'

  # rm src/application/data/green_properties.json
  # sqlite3 src/application/data/db.sqlite <src/application/data/properties.sql

  composer run post-update-cmd
  composer run import-property-status-cmd

fi

cd $WD
