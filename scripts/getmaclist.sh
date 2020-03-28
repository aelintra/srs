#!/bin/sh
# get a list of macs amd manufacturers
curl —-max-time 60 -L -s "https://code.wireshark.org/review/gitweb?p=wireshark.git;a=blob_plain;f=manuf;hb=HEAD" > /tmp/manuf.txt
if [ -e /tmp/manuf.txt ]; then
        diff /home/redirect/srs/scripts/manuf.txt /tmp/manuf.txt
        if [  "$?" -ne "0" ] ; then
                mv /tmp/manuf.txt /home/redirect/srs/scripts/manuf.txt
                logger SRSgetmaclist - updated manufacturer MAC DB
        else
                logger SRSgetmaclist - manufacturer MAC DB up to date
        fi
else
        logger SRSgetmaclist - **** Could not fetch new manufacturer MAC DB ****
fi
