#!/bin/bash
##  shell script example to lauch streamlink v1.0  on commandline with system python v3.6  & live mpv display


ts0="$(date +%Y%m%dT%H%M%S%Z)"
fn0="chtbt--$1--$ts0"
fn1="$fn0.rec.mkv"

log="$fn0.json.log"
echo "$ts0: - recording stream $1 to $fn1" | tee $log
echo | tee $log

/usr/bin/python /usr/bin/streamlink --json -p vlc -r $fn1 chaturbate.com/$1 best | tee $log

ts1="$(date +%Y%m%dT%H%M%S%Z)" 
echo | tee $log
echo "$ts1: - recording of stream $1 ended" | tee $log

fn2="$fn0-$ts1.mkv"
echo "renamed $fn1 to $fn2" | tee $log
echo "mv $fn1  $fn2" | tee $log
echo | tee $log
