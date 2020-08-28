#!/bin/bash
##  shell script example to lauch streamlink v1.0  on commandline with system python v3.6  & live mpv display

# make sure logs dir exists
logdir="$HOME/Videos/logs/$(date -uI)"
echo "logdir:  $logdir"
mkdir -vp $logdir

# create full-file name for log file for today (Zulu time)
fn00="cb--$1"                           # file name base for .log file
fflog="$logdir/$fn00.log"   # all timestamps in Zulu (UTC) time
echo "fflog: $fflog"
touch $fflog                            # create log file

## get timestamp "now"
ts="$(date --utc +%Y%m%dT%H%M%S%Z)"     # all timestamps in Zulu (UTC) time

# set recording filename from 1-st (and only) input argument ($1 ... room name)
recdir="$HOME/Videos/gpg/cb/recbot/live-rec"
echo "$ts - fflog : - $fflog"     | tee -ai $fflog
echo "$ts - recdir: - $recdir"    | tee -ai $fflog
mkdir -vp $recdir           | tee -ai $fflog


ts1=$ts                                 # now
fn0="$fn00--$ts1"                       # file name base for .rec.mkv file
fn1="$fn0.rec.mkv"
ffn1="$recdir/$fn1"
echo "$ts - ffn1  : - $recdir"    | tee -ai $fflog

## construct shell command
cmd="/usr/bin/python /usr/bin/streamlink chaturbate.com/$1 best --json -o $ffn1"

#execute json command
echo "$ts - info  : - looking for cb stream $1 ..."  | tee -ai $fflog
echo | tee -ai $fflog
echo "$ts - json  :" | tee -ai $fflog
res=eval $cmd
echo  $res | tee -ai $fflog

cmd="/usr/bin/python /usr/bin/streamlink -p vlc  chaturbate.com/$1 best -o $ffn1" 

#execute live rec command
echo "$ts - info  : - recording cb stream $1 ..."  | tee -ai $fflog
echo | tee -ai $fflog
res=eval $cmd 
echo  $res | tee -ai $fflog


## after command finished
echo | tee -ai $fflog
echo "$ts - info  : - no stream $1" | tee -ai $fflog

ts2=$ts                                 # now
fn2="$fn0-$ts2.mkv"
ffn2="$recdir/$fn2"
mv -v $ffn1  $ffn2 | tee -ai $fflog
echo "$ts - mv    : - renamed $fn1 to $fn2" | tee -ai $fflog
echo | tee -ai $fflog
