ODIR=`pwd`
BDIR=/tmp/ntime
if [ ! -d $BDIR ]; then
    mkdir $BDIR
fi
cp * $BDIR
cd $BDIR
phpize
./configure --enable-nanotime
make
php -d extension=$BDIR/modules/nanotime.so $ODIR/test.php
