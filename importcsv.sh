#!/bin/bash

WD=`pwd`

if [ -f src/application/data/smokealarm_import.csv ]
then
  echo 'import exists ..'
  rm src/application/data/db.sqlite
  rm src/application/data/green_properties.json
  rm src/application/data/smokealarm.json
  cp src/application/data/smokealarm-default.json src/application/data/smokealarm.json

  composer run post-update-cmd
  composer run importcsv-cmd

fi

cd $WD
