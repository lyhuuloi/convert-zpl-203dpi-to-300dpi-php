<?php

/**
 * Class zplConverter
 * By lyhuuloi
 * 2019-03-03
 */

class zplConverter {
    /**
     * Scale ZPL 203dpi -> 300dpi
     * @param $rawCommands
     * @param float|int $scaleFactor
     * @return string
     */

    static public function scale($rawCommands, $scaleFactor = 300/203) {

        $sectionData = explode("^", $rawCommands);

        // ZPI cmds
        $cmdData = ['FO', 'A0', 'A@', 'LL', 'LH', 'GB', 'FB', 'BY', 'B3', 'PW']; // Added PW for height.

        foreach ($cmdData as $i => $cmds) {
            foreach ($sectionData as $j => $sections) {
                if (strrpos($sections, $cmds) === 0) {
                    $sectionData[$j] = self::scaleCell($cmds, $sections, $scaleFactor);
                }
            }
        }

        return join("^", $sectionData);
    }

    /*
     * Scales each cell
     */
    static private function scaleCell($cmd, $section, $scaleFactor) {

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

}
