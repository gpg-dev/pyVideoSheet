#!/bin/bash
##  shell script example to lauch streamlink v1.0  on commandline with system python v3.6  & live mpv display



ts="$(date +%Y%m%dT%H%M%S%Z)"
echo "$ts - recording stream $1 ..."
echo;

#/usr/bin/python /usr/bin/streamlink --json -p vlc -r chtbt--$1--($ts0).rec.mkv chaturbate.com/$1 best  >  chtbt--$1--($ts0).json.log

ts="$(date +%Y%m%dT%H%M%S%Z)" 
echo;
echo "$ts - recording of stream $1 ended"