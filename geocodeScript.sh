#!/bin/sh
url=https://maps.googleapis.com/maps/api/geocode/json?
add="$@"

if [[ $add =~ FRANCE[[:space:]]971[[:digit:]]{2} ]]; then
	add=${add/%FRANCE/GUADELOUPE}
fi
if [[ $add =~ FRANCE[[:space:]]972[[:digit:]]{2} ]]; then
	add=${add/%FRANCE/MARTINIQUE}
fi
if [[ $add =~ FRANCE[[:space:]]974[[:digit:]]{2} ]]; then
	add=${add/%FRANCE/REUNION}
fi
add=${add//[[:space:]]/+}
field="address=$add&key=AIzaSyBUFexjopmvsIR2l5M1kFg9ZJtRrHHz7QU"
myurl="$url$field"
content=$(curl "{$myurl}")

loc=",error"
if [[ $content =~ [[:punct:]]location[[:punct:]][[:space:]]*[[:punct:]][[:space:]]*[[:punct:]][[:space:]]*[[:punct:]]lat[[:punct:]][[:space:]]*[[:punct:]][[:space:]]*(-?[0-9\.]+)[[:punct:]][[:space:]]*[[:punct:]]lng[[:punct:]][[:space:]]*[[:punct:]][[:space:]]*(-?[0-9\.]+) ]]; then
	loc=",${BASH_REMATCH[1]},${BASH_REMATCH[2]}"
else
	echo $add > add.txt
	sed -ri "s/-[^0-9]+-//g" add.txt
	add1=$(<add.txt)
	field1="address=$add1&key=AIzaSyBUFexjopmvsIR2l5M1kFg9ZJtRrHHz7QU"
	myurl1="$url$field1"
	content1=$(curl "{$myurl1}")
	if [[ $content1 =~ [[:punct:]]location[[:punct:]][[:space:]]*[[:punct:]][[:space:]]*[[:punct:]][[:space:]]*[[:punct:]]lat[[:punct:]][[:space:]]*[[:punct:]][[:space:]]*(-?[0-9\.]+)[[:punct:]][[:space:]]*[[:punct:]]lng[[:punct:]][[:space:]]*[[:punct:]][[:space:]]*(-?[0-9\.]+) ]]; then
		loc=",${BASH_REMATCH[1]},${BASH_REMATCH[2]}"
	else
		echo $content1
		echo $add1 >> err.log
	fi
fi
# mycontent="$mycontent$add\n$content\n\n"
echo $loc > loc.txt
