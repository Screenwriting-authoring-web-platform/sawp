#!/bin/sh

php5 ../../../yii message config.php
sed -i -e 's/'\'''\'',/'\''TEST'\'',/g' TEST/app.php
sed -i -e 's/'\'''\'',/'\''TEST'\'',/g' TEST/js.php
