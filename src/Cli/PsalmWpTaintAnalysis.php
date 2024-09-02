<?php

namespace Tuncay\PsalmWpTaint\src\Cli;

use Tuncay\PsalmWpTaint\src\PsalmAnalysisOutputHandler;
use Tuncay\PsalmWpTaint\src\PsalmOutputParser;
use Tuncay\PsalmWpTaint\src\Util;
use Tuncay\PsalmWpTaint\src\FuzzableActionSelector;
use Tuncay\PsalmWpTaint\src\PsalmError\PsalmResult;
use Tuncay\PsalmWpTaint\src\PsalmError\PsalmXMLTaintReport;

use function WappoVendor\last;

final class PsalmWpTaintAnalysis {

	public static function run( RunAnalyzeCommand $command ): void {
		if ( str_contains( __DIR__, '' ) ) {
			chdir( "./" );
		}

		$pluginsDir = $command->pluginsDirectory != '' ? $command->pluginsDirectory : "./wp-content/plugins/";

		if ( $command->install && $command->pluginCsvFile ) {
			self::installPlugins( file( $command->pluginCsvFile ), $pluginsDir );
		}

		if ( $command->analyze ) {
			if ( ! self::checkPsalmXMLFile() ) {
				return;
			}

			print_r( "File \"psalm.xml\" found.\n" );
			print_r( "Getting installed plugins from $pluginsDir ...\n" );

			$installedPlugins = Util::getDirsIn( $pluginsDir );
			print_r( "Found " . count( $installedPlugins ) . " plugins in $pluginsDir.\n" );
			$reports = self::runPsalmAnalysisOnAllFoundPlugins( $installedPlugins );

			$outputHandler          = new PsalmAnalysisOutputHandler();
			$analysisResults        = $outputHandler->handle( new PsalmOutputParser(), $reports );
			$addActionsMap          = (array) json_decode( file_get_contents( "./add-actions-map.json" ) );
			$fuzzableActionSelector = new FuzzableActionSelector( $addActionsMap, $analysisResults, $pluginsDir );
			$fuzzableActionSelector->selectActionsToFuzz( $pluginsDir );

			self::saveResults( $analysisResults, $fuzzableActionSelector, $command->outputFilename );
		}
	}

	private static function installPlugins( array $csvFileLines, string $pluginsDir ): void {

		foreach ( $csvFileLines as $line ) {
			print_r( "----------------------------------------\n" );
			[ $pluginSlug, $pluginVersion ] = explode( ",", $line );

			if ( is_dir( "$pluginsDir/$pluginSlug" ) ) {
				print_r( "Plugin \"$pluginSlug\" already installed. Skipping installation.\n" );
				continue;
			}

			print_r( "Installing plugin:\n \"$pluginSlug (v$pluginVersion) \" ...\n" );
			exec( "ddev wp plugin install $pluginSlug --version=$pluginVersion" );
		}

		print_r( "Plugins installed successfully.\n" );
		print_r( "----------------------------------------\n" );
	}

	private static function runPsalmAnalysisOnAllFoundPlugins( array $pluginDirPaths ): array {
		$reports = [];
		foreach ( $pluginDirPaths as $pluginDirPath ) {
			if ( str_ends_with( $pluginDirPath, "~" ) ) {
				continue;
			}

			print_r( "----------------------------------------\n" );
			print_r( "Running psalm's taint analysis on $pluginDirPath ...\n" );

			Util::changePsalmProjectDir( $pluginDirPath, "./psalm.xml" );
			$tmp        = explode( "/", $pluginDirPath );
			$pluginSlug = end( $tmp );

			exec( "./vendor/bin/psalm --taint-analysis --output-format=xml > ./psalm-report.xml" );
			$report = new PsalmXMLTaintReport( "./psalm-report.xml" );

			$reports[ $pluginSlug ] = $report->reportValues();
		}

		return $reports;
	}

	private static function saveResults( PsalmResult $psalmResult, FuzzableActionSelector $fuzzableActionSelector, string $outputFilename ): void {
		if ( ! is_dir( "./psalm-result/" ) ) {
			mkdir( "./psalm-result/" );
		}

		if ( ! file_exists( "./psalm-result/$outputFilename.json" ) ) {
			fopen( "./psalm-result/$outputFilename.json", "w" );
		} else {
			$file = fopen( "./psalm-result/$outputFilename.json", "w" );
			fwrite( $file, "" );
			fclose( $file );
		}

		file_put_contents( "./psalm-result/$outputFilename.json", json_encode( $psalmResult->printAsArray(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) );
		$fuzzableActionSelector->writeFuzzableActionsToFile( "./psalm-result/actions_to_fuzz-$outputFilename" );

		print_r( "----------------------------------------\n" );
		print_r( "Analysis results saved to \"./psalm-result/$outputFilename.json\" and \"./psalm-result/actions_to_fuzz-$outputFilename.json\"\n" );
	}

	private static function checkPsalmXMLFile(): bool {
		if ( ! file_exists( "./psalm.xml" ) ) {
			print_r( "File \"psalm.xml\" not found. Please make sure psalm is setup correctly.\n" );

			return false;
		}

		return true;
	}
}
