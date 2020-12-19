ODIR=`pwd`
BDIR=/tmp/npk
if [ ! -d $BDIR ]; then
    mkdir $BDIR
fi
cp * $BDIR
cd $BDIR
phpize
./configure --enable-nanopk_dev
make
php -d extension=$BDIR/modules/nanopk_dev.so $ODIR/test.php
