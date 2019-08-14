<?php

class MnLibFileSystemFileSystem
{
    public static function loadSource($dir, $exception = array())
    {
        if (in_array($dir, $exception) || !is_dir($dir)) return false;

        $dirHandle = opendir($dir);
        if (!$dirHandle) return false;

        while (($item = readdir($dirHandle)) !== false)
        {
            if ($item[0] == ".") continue;

            $itemPath = $dir . '/' . $item;

            if (in_array($itemPath, $exception)) continue;

            if (!is_dir($itemPath)) include($itemPath);
            else self::loadSource($itemPath, $exception);
        }
    }

    public static function deleteNotInArray($dir, $array)
    {
        $dirHandle = opendir($dir);
        if (!$dirHandle) return false;

        while (($item = readdir($dirHandle)) !== false)
        {
            if ($item[0] == ".") continue;

            $itemPath = $dir . '/' . $item;

            if (!is_dir($itemPath) && !in_array(str_replace($_SERVER['DOCUMENT_ROOT'], '', $itemPath), $array))
            {
                unlink($itemPath);
            }
            else
            {
                self::deleteNotInArray($itemPath, $array);
            }
        }
    }

    public static function deleteOldSql($dir)
    {
        $dirHandle = opendir($dir);
        if (!$dirHandle) return false;

        while (($item = readdir($dirHandle)) !== false)
        {
            if ($item[0] == ".") continue;

            $itemPath = $dir . '/' . $item;

            if (!is_dir($itemPath))
            {
                if (substr($itemPath, -4) == '.sql' && substr($itemPath, -7) != 't50.sql')
                {
                    $modTime = filemtime($itemPath);

                    if ($modTime < mktime(date("H"), date("i"), date("s"), date("m"), date("d")-14, date("Y")))
                    {
                        unlink($itemPath);
                    }
                }
            }
            else
            {
                self::deleteOldSql($itemPath);
            }
        }
    }
}
