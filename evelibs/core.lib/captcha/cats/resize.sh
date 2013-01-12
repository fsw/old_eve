x=007; for i in `ls`; do convert $i -thumbnail 100x100^ -gravity center -extent 100x100 "$x.jpg"; x=`expr $x + 1`; x=`printf "%03d" $x`; done;

