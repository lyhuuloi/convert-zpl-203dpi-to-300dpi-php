<?php

$input = <<<EOF
^XA
^FWN
^LL1218
^PW812
^PON
^LH0,0
^LT0
^CI28

^FO300,0^GB0,220,2,B,0^FS

^FO50,40^FB100,1,2,L,0^A0N,200,200
^FDF^FS

^FO400,44^GB350,128,4^FS
^FO420,56^FB360,1,2,L,0^A0N,26,26^FDFIRST - CLASS PKG^FS
^FO420,86^FB360,1,2,L,0^A0N,26,26^FDU.S. POSTAGE & FEES PAID^FS
^FO420,116^FB360,1,2,L,0^A0N,26,26^FDOSM^FS
^FO420,146^FB360,1,2,L,0^A0N,26,26^FDE-VS^FS^FO0,240^FB812,1,2,C,0^A0N,46,46^FDUSPS FIRST - CLASS PKG^FS

^FO0,220^GB812,0,2,B,0
^FS^FO0,290^GB812,0,2,B,0^FS

^FO19,320^FB507,6,2,L,0^A0N,23,23^FDFULFILLMENT CENTER\&5XXX OCEANUS DR STE\&1XX-1XX\&HUNTINGTON BEACH, CA 92649
^FS

^FO101,596^FB697,6,2,L,0^A0N,28,28^FDBEN LEE\&142 LY HUU LOI\&HONOLULU, HI 96821-2009
^FS
^FO0,812^GB812,0,6,B,0^FS

^FO0,827^FB812,1,2,C,0^A0N,34,34^FDUSPS TRACKING # eVS^FS

^FO50,896^BY3^BCN,152,N,N,N,D^FD42096821>89200XXXX4691YYYY005695^FS

^FO0,1094^FB812,1,2,C,0^A0N,34,34^FD9200 XXXX 4691 YYYY 0056 95^FS
^FO0,1136^GB812,0,6,B,0^FS

^FO0,710^FB780,1,2,R,0^A0N,28,28^FD#TW-0000988830^FS
^XZ
EOF;

/**
 * Scale ZPL 203dpi -> 300dpi
 * @param $rawCommands
 * @param float|int $scaleFactor
 * @return string
 */

function scaleZPL($rawCommands, $scaleFactor = 300/203) {

    $sectionData = explode("^", $rawCommands);

    // ZPI cmds
    $cmdData = ['FO', 'A0', 'A@', 'LL', 'LH', 'GB', 'FB', 'BY', 'B3', 'PW']; // Added PW for height.

    foreach ($cmdData as $i => $cmds) {
        foreach ($sectionData as $j => $sections) {
            if (strrpos($sections, $cmds) === 0) {
                $sectionData[$j] = scaleCell($cmds, $sections, $scaleFactor);
            }
        }
	}

	return join("^", $sectionData);
}

/*
 * Scales each cell
 */
function scaleCell($cmd, $section, $scaleFactor) {

    //echo $cmd.$section ." => ";

    $section = substr($section, strlen($cmd), strlen($section));
    $partData = explode(',', $section);

    foreach ($partData as $p => $parts ) {

    //echo  " A> ".$parts;
        if (is_numeric(trim($parts))) {
            $partData[$p] = round($scaleFactor * trim($parts));
            //echo " B> ". $parts;
        }

    }
    //echo " => ";
    //echo $cmd.implode(",", $partData).PHP_EOL;

	return $cmd.implode(",", $partData);
}

$output = scaleZPL($input);

echo $output;

