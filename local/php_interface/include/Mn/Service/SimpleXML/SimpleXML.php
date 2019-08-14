<?php

class MnServiceSimpleXMLSimpleXML
{
    public static function export($xml, $altFileName = null)
    {
        if (!isset($altFileName))
        {
            $curFileName = array_pop(explode('/', $_SERVER['PHP_SELF']));
            $xmlFileName = str_replace('.php', '.xml', $curFileName);
        }
        else $xmlFileName = $altFileName . '.xml';

        unlink($xmlFileName);
        $xmlFile = fopen($xmlFileName, 'w');
        fwrite($xmlFile, mb_convert_encoding($xml, 'utf-8', 'windows-1251'));
        fclose($xmlFile);
    }

    //@todo refactor name to array and mod intros if needed
    public static function convertNodeToStrings($node)
    {
        $json = json_encode($node);
        $array = json_decode($json, TRUE);

        return $array;
    }
}
