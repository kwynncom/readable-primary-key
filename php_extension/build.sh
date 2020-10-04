ODIR=`pwd`
BDIR=/tmp/phprd
if [ ! -d $BDIR ]; then
    mkdir $BDIR
fi
cp * $BDIR
cd $BDIR
phpize
./configure --enable-rdtscp
make
php -d extension=$BDIR/modules/rdtscp.so $ODIR/test.php
