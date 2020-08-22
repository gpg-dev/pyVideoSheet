#!/bin/bash
##  shell script example to lauch streamlink v1.0  on commandline with system python v3.6  & live mpv display



ts0="$(date +%Y%m%dT%H%M%S%Z)"
fn0="chtbt--$1--($ts0)"
fn+=".rec.mkv"
echo "$ts0 - recording stream $1 to $fn"
echo;

#/usr/bin/python /usr/bin/streamlink --json -p vlc -r chtbt--$1--($ts0).rec.mkv chaturbate.com/$1 best  >  chtbt--$1--($ts0).json.log

ts1="$(date +%Y%m%dT%H%M%S%Z)" 
fn1+="--$ts1.mkv"
echo "renaming $fn0 to $fn1"
echo "mv $fn0  $fn1"
echo;
echo "$ts - recording of stream $1 ended"