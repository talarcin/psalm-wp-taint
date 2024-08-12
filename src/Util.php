<?php

namespace Tuncay\PsalmWpTaint\src;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;

/**
 * @author Tuncay Alarcin
 */
class Util {
	/**
	 * Retrieves a psalm.xml file from given path and changes the directory elements name attribute to the plugin directory path.
	 *
	 * @param string $pluginDirPath
	 * @param string $psalmXMLPath
	 *
	 * @return void
	 */
	public static function changePsalmProjectDir( string $pluginDirPath, string $psalmXMLPath ): void {
		$psalm_xml = simplexml_load_file( $psalmXMLPath );

		if ( ! $psalm_xml->projectFiles->directory ) {
			print_r( "Configuring psalm.xml with correct project directory ...\n" );
			$psalm_xml->projectFiles->addChild( "directory" );
			$psalm_xml->projectFiles->directory->addAttribute( "name", $pluginDirPath );
		} else if ( ! $psalm_xml->projectFiles->directory["name"] ) {
			print_r( "Configuring psalm.xml with correct project directory ...\n" );
			$psalm_xml->projectFiles->directory->addAttribute( "name", $pluginDirPath );
		} else {
			print_r( "Configuring psalm.xml with correct project directory ...\n" );
			$psalm_xml->projectFiles->directory["name"] = $pluginDirPath;
		}

		$psalm_xml->asXML( $psalmXMLPath );
		print_r( "Configuration of psalm.xml done!\n" );
	}

	/**
	 * Scans the given directory for php files and returns every php files path in a list.
	 *
	 * @param string $directory
	 *
	 * @return array
	 */
	public static function scanDirForPHPFiles( string $directory ): array {
		$directoryIterator = new RecursiveDirectoryIterator( $directory );
		$iterator          = new RecursiveIteratorIterator( $directoryIterator );
		$regex             = new RegexIterator( $iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH );

		$files = [];

		foreach ( iterator_to_array( $regex ) as $key => $file ) {
			$files[] = $key;
		}

		return $files;
	}

	/**
	 * Retrieves each directory inside the given directory.
	 *
	 * @param string $directory
	 *
	 * @return array|false
	 */
	public static function getDirsIn( string $directory ): array|false {
		if ( str_ends_with( $directory, "/" ) ) {
			return glob( $directory . '*', GLOB_ONLYDIR );
		}

		return glob( "$directory/*", GLOB_ONLYDIR );
	}

	/**
	 * Cleans given string from ANSI codes.
	 *
	 * @param string $line
	 *
	 * @return string
	 */
	public static function removeAnsiCodes( string $line ): string {
		return preg_replace( '#\\x1b[[][0-9]+(;[0-9]*)[A-Za-z]#', '', $line );
	}
}