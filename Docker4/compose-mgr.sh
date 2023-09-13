#!/bin/bash
#
# Run multiple copies of a stack
# The env variable HTTP_PORT is set to a unique value for each stack.
# The project name is set to the base name of the compose file prefixed 
# with HTTP_PORT
#
# James, Summer 2023
#

BASE_PORT=8000
CMD=/usr/bin/docker-compose

############ no changes should be needed past this line ##############

######################################################################
# usage RC MSG
#
#   display MSG
#   display usage message
#   exit with RC
#
function usage {
    echo "$0: $2"
    echo "usage: $0 -f FILE -n N up|down"
    echo "      -f FILE"
    echo "          Specify the compose file"
    echo "      -n "
    echo "          run N copies of the stack to bring up or down"
    exit $1
} >&2

# parse the command line arguments
#
while getopts "f:n:" arg 
do
    case $arg in
        f)
            COMPOSE_FILE=$OPTARG
            if [ ! -f $COMPOSE_FILE ] 
            then
                usage 2 "Bad file name:$COMPOSE_FILE"
            fi
            ;; 
        n)
            # N must be an integer
            N=$OPTARG
            if [[ ! $N =~ ^[0-9]+$ ]]
            then
                usage 1 "N must be an integer" 
            fi 
            ;;
        *)
            usage 1
			;;
    esac
done

shift $(($OPTIND - 1)) # skip the args already processed
case $1 in
    up)
        VERB="up"
        FLAGS="-d"
        ;;
    down)
        VERB="down"
        FLAGS=""
        ;;
    *)
        usage 1 "up or down required"
		;;
esac


# command line args have been parsed
# if we get here, we know that we were invoked with sane arguments and that
#       VERB=up|down
#       N is integer 

export HTTP_PORT
i=0
while (( i < N ))
do
    (( HTTP_PORT = BASE_PORT + i ))
    echo "Bringing $(basename $COMPOSE_FILE) $VERB on port $HTTP_PORT"

    $CMD \
            -f $COMPOSE_FILE \
            -p ${HTTP_PORT}_$(basename -s .yml $COMPOSE_FILE) \
            $VERB   \
            $FLAGS

    (( i++ ))
done





