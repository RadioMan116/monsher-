<?php

class MnLibToolsTools
{
    //5 depth now
    public static function reindexArrayBy($indexPathArray, $array)
    {
        $reindexedArray = array();

        switch (count($indexPathArray))
        {
            case 1:
                foreach ($array as $arrayEntry) $reindexedArray[$arrayEntry[$indexPathArray[0]]] = $arrayEntry;
                break;
            case 2:
                foreach ($array as $arrayEntry) $reindexedArray[$arrayEntry[$indexPathArray[0]][$indexPathArray[1]]] = $arrayEntry;
                break;
            case 3:
                foreach ($array as $arrayEntry) $reindexedArray[$arrayEntry[$indexPathArray[0]][$indexPathArray[1]][$indexPathArray[2]]] = $arrayEntry;
                break;
            case 4:
                foreach ($array as $arrayEntry) $reindexedArray[$arrayEntry[$indexPathArray[0]][$indexPathArray[1]][$indexPathArray[2]][$indexPathArray[3]]] = $arrayEntry;
                break;
            case 5:
                foreach ($array as $arrayEntry) $reindexedArray[$arrayEntry[$indexPathArray[0]][$indexPathArray[1]][$indexPathArray[2]][$indexPathArray[3]][$indexPathArray[4]]] = $arrayEntry;
                break;
        }

        return $reindexedArray;
    }

    //@todo check param linkage
    public static function arraySingleToIndexed($single)
    {
        if (isset($single[0])) return $single;

        return array(0 => $single);
    }
}
