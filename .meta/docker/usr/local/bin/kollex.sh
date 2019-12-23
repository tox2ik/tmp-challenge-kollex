echo extracting comporer deps
tar xvf /tmp/vendor.tar.xz -C /code |wc -l
echo ...files
df -h
composer install -d /code
make -C /code
