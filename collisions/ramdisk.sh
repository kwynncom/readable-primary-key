RDIR=/tmp/rd
if [ ! -d $RDIR ]; then
    mkdir $RDIR
fi
sudo mount -t ramfs -o size=4g ramfs /tmp/rd
sudo chmod 777 /tmp/rd
