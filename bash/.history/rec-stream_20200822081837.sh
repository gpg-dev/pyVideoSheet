#!/usr/bin/python
##  shell script example to lauch streamlink v1.0  on commandline with system python v3.6  & live mpv display


tss0="$now +%Y%m%dT%H%M%S%Z"; 
ts0="$now +%Y%m%dT%H%M%Z";  
echo tss0
echo "$ts0 - recording stream $1 ..." 
echo

#/usr/bin/python /usr/bin/streamlink --json -p vlc -r chtbt--$1--($ts0).rec.mkv chaturbate.com/$1 best  >  chtbt--$1--($ts0).json.log

ts1="$now +%Y%m%dT%H%M%Z";  
echo
echo "$ts1 - recording of stream $1 ended"  