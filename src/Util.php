<?php

namespace Tuncay\PsalmWpTaint\src;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;

class Util
{
    public static function change_psalm_project_dir($pluginDirPath, $psalmXMLPath): void
    {
        $psalm_xml = simplexml_load_file($psalmXMLPath);

        if (!$psalm_xml->projectFiles->directory) {
            print_r("Configuring psalm.xml with correct project directory ...\n");

            $psalm_xml->projectFiles->addChild("directory");
            $psalm_xml->projectFiles->directory->addAttribute("name", $pluginDirPath);
        }

        if (!$psalm_xml->projectFiles->directory["name"]) {
            print_r("Configuring psalm.xml with correct project directory ...\n");
            $psalm_xml->projectFiles->directory->addAttribute("name", $pluginDirPath);
        } else {
            print_r("Configuring psalm.xml with correct project directory ...\n");
            $psalm_xml->projectFiles->directory["name"] = $pluginDirPath;
        }

        $psalm_xml->asXML("./psalm.xml");
        print_r("Configuration of psalm.xml done!\n");
    }

    public static function scanDirForPHPFiles(string $directory): array
    {
        $directoryIterator = new RecursiveDirectoryIterator($directory);
        $iterator = new RecursiveIteratorIterator($directoryIterator);
        $regex = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

        return iterator_to_array($regex);
    }

    public static function getDirsIn(string $directory): array|false
    {
        if (str_ends_with($directory, "/")) {
            return glob($directory . '*', GLOB_ONLYDIR);
        }

        return glob("$directory/*", GLOB_ONLYDIR);
    }

    public static function getPluginSlugFromPath(string $path): string
    {
        $tmp = explode("/", $path);
        return end($tmp);
    }

    public static function removeAnsiCodes(string $line): string
    {
        return preg_replace('#\\x1b[[][0-9]+(;[0-9]*)[A-Za-z]#', '', $line);
    }
}