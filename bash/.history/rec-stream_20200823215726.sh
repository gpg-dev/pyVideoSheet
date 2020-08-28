#!/bin/bash
##  shell script example to lauch streamlink v1.0  on commandline with system python v3.6  & live mpv display

# make sure logs dir exists
logdir="$HOME/Videos/logs"
mkdir -vp $logdir

# create full-file name for log file for today (Zulu time)
fflog="$logdir/$(date -uI)/$fn00.log"   # all timestamps in Zulu (UTC) time

# set recording filename from 1-st (and only) input argument ($1 ... room name)
recdir="$HOME/Videos/gpg/cb/recbot/live-rec/"
mkdir -vp $recdir | tee -ai $fflog

ts="$(date --utc +%Y%m%dT%H%M%S%Z)"     # all timestamps in Zulu (UTC) time
ts0=$ts                                 # now

fn00="cb--$1"                           # file name base for .log file
fn0="$fn00--$ts0"                       # file name base for .rec.mkv file
fn1="$fn0.rec.mkv"
ffn1="$recdir/$fn1"

## construct shell command
#/usr/bin/python /usr/bin/streamlink chaturbate.com/$1 best  -p mpv -r $fn1 
cmd="/usr/bin/python /usr/bin/streamlink chaturbate.com/$1 best --json -o $ffn1"

#execute command
echo -n "$ts: - looking for cb stream $1 ..."  | tee -ai $fflog
echo -n | tee -ai $fflog
$cmd | tee -ai $fflog

## after command finished
ts1="$(date +%Y%m%dT%H%M%S%Z)" 
echo -n | tee -ai $fflog
echo -n "$ts1: - no stream $1" | tee -ai $fflog

fn2="$fn0-$ts1.mkv"
ffn2="$recdir/$fn2"
mv -v $fn1  $fn2 | tee -ai $fflog
echo "renamed $fn1 to $fn2" | tee -ai $fflog
echo -n  | tee -ai $fflog
