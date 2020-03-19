## Copy this file into you base folder and start it with ./checkpeers.sh . It will automatically ban each node which is not complient with genesis.
## The script is written for bitcoin-sv-1.0.1. If you use 1.0.0 or 1.0.2, you need to adjust lines 7 and 35


while true
do
peers=$(./bitcoin-sv-1.0.1/bin/bitcoin-cli --datadir=.bitcoin getpeerinfo)
lengthof=$(echo $peers | jq length)
echo $lengthof
for i in $(seq 0 $lengthof)
do
        num=$i
        version=$(echo $peers | jq '.['$num']["subver"]')
        address=$(echo $peers | jq '.['$num']["addr"]')
        if [[ $address =~ ":" ]]
        then
                pos=$(echo $address | grep -b -o ":")
                length=${#address}
                pos=${pos:0:2}
                pos=$pos-1
                address=${address:1:pos}
        fi
        if [ "$version" = '"/Bitcoin SV:1.0.2/"' ]
        then
                echo "ok" $version
        elif [ "$version" = '"/Bitcoin SV:1.0.1/"' ]
        then
                echo "ok" $version
        elif [ "$version" = '"/Bitcoin SV:1.0.0/"' ]
        then
                echo "ok" $version
        else
                echo $address
                echo "not ok" $version
                ./bitcoin-sv-1.0.1/bin/bitcoin-cli --datadir=.bitcoin setban $address "add"
        fi
done
sleep 10
done
