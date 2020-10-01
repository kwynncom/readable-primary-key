ODIR=`pwd`
BDIR=/tmp/phprd
if [ ! -d $BDIR ]; then
    mkdir $BDIR
fi
cp * $BDIR
cd $BDIR
phpize
./configure --enable-php_rdtscp
make
php -d extension=$BDIR/modules/php_rdtscp.so $ODIR/test.php
