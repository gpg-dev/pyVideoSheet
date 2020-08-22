
# Bash: Argument Parsing

- Drew Stokes
- Mar 24, 2015 · 3 min read


## One parser to rule them all
Bash is a wonderful and terrible language. It can provide extremely elegant solutions to common text processing and system management tasks, but it can also drag you into the depths of convoluted workarounds to accomplish menial jobs.
Recently I ran into one of these situations when trying to parse varying inputs in a control script. The standard tools for argument parsing were breaking at every turn as I changed argument order and added flag options for flexibility. The remainder of this article details the problems and the solution I came up with for laying argument parsing woes to rest.

## Goals
The ideal argument parser will recognize both short and long option flags, preserve the order of positional arguments, and allow both options and arguments to be specified in any order relative to each other. In other words both of these commands should result in the same parsed arguments:
```
$ ./foo bar -a baz --long thing
$ ./foo -a baz bar --long thing
```

## getopt(s)
The most widely recognized tools for parsing arguments in bash are getopt and getopts. Though both tools are similar in name, they ’re very different. getopt is a GNU library that parses argument strings and supports both short and long form flags. getopts is a bash builtin that also parses argument strings but only supports short form flags. Typically, if you need to write a simple script that only accepts one or two flag options you can do something like:
while getopts “:a:bc” opt; 
```
 do
  case $opt in
    a) AOPT=$OPTARG ;;
  esac
done
```
This works great for simple option parsing, but things start to fall apart if you want to support long options or mixing options and positional arguments together.

## A Better Way
As it turns out, it’s pretty easy to write your own arg parser once you understand the mechanics of the language. Doing so affords you the ability to cast all manner of spells to bend arguments to your will. Here’s a basic example:
```
#!/bin/bash

PARAMS=""

while (( "$#" )); do
  case "$1" in
    -a|--my-boolean-flag)
      MY_FLAG=0
      shift
      ;;
    -b|--my-flag-with-argument)
      if [ -n "$2" ] && [ ${2:0:1} != "-" ]; then
        MY_FLAG_ARG=$2
        shift 2
      else
        echo "Error: Argument for $1 is missing" >&2
        exit 1
      fi
      ;;
    -*|--*=) # unsupported flags
      echo "Error: Unsupported flag $1" >&2
      exit 1
      ;;
    *) # preserve positional arguments
      PARAMS="$PARAMS $1"
      shift
      ;;
  esac
done

# set positional arguments in their proper place
eval set -- "$PARAMS"

```


There’s a lot going on here so let’s break it down. First we set a variable `PARAMS` to save any positional arguments into for later. Next, we create a while loop that evaluates the length of the arguments array and exits when it reaches zero. Inside of the while loop, pass the first element in the arguments array through a case statement looking for either a custom flag or some default flag patterns. If the statement matches a flag, we do something (like the save the value to a variable) and we use the `shift` statement to pop elements off the front of the arguments array before the next iteration of the loop. If the statement matches a regular argument, we save it into a string to be evaluated later. Finally, after all the arguments have been processed, we set the arguments array to the list of positional arguments we saved using the `set` command.
With this new knowledge it becomes really easy to write robust and flexible bash utilities that can be deployed to multiple systems without worrying about the order or format of arguments.


Thanks to the following folks for helping make this snippet more robust:
- blogofstuff for suggesting a solution for a looping error

Drew Stokes


## WRITTEN BY
 - Drew Stokes
  - I turn beer into software. Be good humans.


## Comments

### Ted Lilley
about 3 years ago\
Nice one. Elegant in its simplicity.
I’m pretty sure you could handle the --long-option=value form as well without changing the case statement, if you add in the following line right before case:


### Matěj Týč
over 3 years ago\
Great article, I am glad that yet another person finds the getopt(s) approach unsatisfactory, preferring the manual approach. The situation you describe certainly has a very negative effect on the whole field of bash scripting as a whole. Fortunatelly, there is a remedy available — Argbash, an open source project. Argbash is a code generator that at…


### Anders Ingemann
6 months ago\
Have you heard of docopt? It creates the parser for you based on your help text.
I have made a bash version that generates a dependencyless parser based on the help text and embeds it into your script. No API to wrestle with, no docs (well, barely any) to read.


### Gary Oberbrunner
4 months ago\
This is nice, thanks! One thing I notice is that args after — (two dashes to end arg parsing) get ignored, because the use of “set” later overwrites all $* with the PARAMS.


### Kartik Subbarao
about 2 years ago\
Looks like there is a typo on line 20. PARAM=… should be PARAMS=…


### john lunzer
about 1 year ago\
This is my favorite technique for parsing command line arguments. I suggest also including a small function you can add to options which expect arguments. This provides some friendliness to users to avoid unexpected behavior.
```
checkArg () {
if [ -z ...
```
Read More


### Bruce Edge
about 2 years ago\
Nice ideas, I have issues with getopt(s) as well, but the one thing this approach lacks is the -h/ — help support.


### Meir Gabay
15 days ago\
Excellent post! I created a script which does this whole process for you, it's called bargs - https://github.com/unfor19/bargs I hope you find it useful. I hate when I have to use `while case esac` each time that I create a new Bash script


### blogofstuff
4 months ago\
Nice simple technique, and the limitations I can live with.
One issue is with using ‘shift 2’. This gets stuck in a loop if the flag with argument is the last argument and no argument is given.

