#!/bin/bash
##  shell script example to lauch streamlink v1.0  on commandline with system python v3.6  & live mpv display



ts0="$(date +%Y%m%dT%H%M%S%Z)"
fn0="chtbt--$1--$ts0"
fn1="$fn0.rec.mkv"
echo "$ts0 - recording stream $1 to $fn1"
echo;

#/usr/bin/python /usr/bin/streamlink --json -p vlc -r chtbt--$1--($ts0).rec.mkv chaturbate.com/$1 best  >  chtbt--$1--($ts0).json.log

ts1="$(date +%Y%m%dT%H%M%S%Z)" 
echo;
echo "$ts1 - recording of stream $1 ended"

fn2="$fn0-$ts1.mkv"
echo "renaming $fn1 to $fn2"
echo "mv $fn2  $fn2"
