#!/usr/bin/env bash
STARTED_AT=$(date +%s)

./vendor/bin/phpunit --stop-on-defect --coverage-text
if [ $? -ne 0 ]; then
    exit 1
fi

file='coverage/index.html'
if [[ -f $file ]]; then
    percentage=$(grep -m 1 'progressbar' $file | awk -Fvaluenow '{print $2}' | awk -F\" '{print $2}')
    sed -i "s/>[0-9]\+\.\?[0-9]\+%/>$percentage%/g" coverage.svg
    if [[ -n $(command -v rsvg-convert) ]]; then
      echo uno
      rsvg-convert coverage.svg > coverage.png
    fi
fi

FINISHED_AT=$(date +%s)
echo 'Time taken: '$((FINISHED_AT - STARTED_AT))' seconds'

FINISHED_AT=$(date +%s)
echo 'Time taken: '$(($FINISHED_AT - $STARTED_AT))' seconds'
