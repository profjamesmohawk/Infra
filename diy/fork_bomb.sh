#
# relatively safe fork bomb
#

i=0
while true
do
	sleep 1000 &
	echo $i
	(( i++ ))
done

wait
