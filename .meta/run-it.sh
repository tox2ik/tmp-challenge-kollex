#!/bin/bash -l

# curlerr () { curl -D/dev/stderr "$@"; } 

index=main.php

( sleep 1; touch $index) &


#shift #// run-it.sh
#cd `dirname $0`; cd ..

while true; do
	(find . -name \*php; 
	 find . -name '.env*'; 
	 find . -name 'run-*sh'; 
	 find . -name \*feature; 
	 find . -name \*tpl; 
	 find . -name \*.yaml; 
	 find . -name \*.sql ;
	 echo ./composer.json;
	) | inotifywait -e  modify -e move  -e attrib -e delete --fromfile - 2>/dev/null

	if [[ $? -ne 0 ]] ; then exit 2;fi

	php -r 'echo str_repeat("\n", 3);'

	## avoid invisible black
	printf '\033]4;0;#686868\007'


    if [[ $1 =~ .*phpunit$ ]] ||
		[[ $1 = behat ]] ||
		[[ $1 = make ]] ||
		[[ $1 = php ]]; then "$@"
    elif [[ $1 =~ .*\\.php ]]; then php $1
    elif [[ $1 =~ .*\\.sh ]]; then bash -l $1;
    else
    	behat --stop-on-failure --colors -n "$@"
    fi
	sleep 0.3
done
