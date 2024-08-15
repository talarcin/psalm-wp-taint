<?php

namespace Tuncay\PsalmWpTaint\src\Cli;

use Ahc\Cli\Input\Command;
use Ahc\Cli\Input\Parser;

class RunAnalyzeCommand extends Command {

	public function __construct() {
		parent::__construct( 'run', 'Run analyze script' );

		$this
			->argument( '<output-filename>', 'The absolute path to the output directory.' )
			->argument( '[plugins-directory]', 'Optional argument to set other installation directory of plugins. Default: /wp-content/plugins/' )
			->argument( '[plugin-csv-file]', 'The .csv file containing the list of plugin slugs and versions of the plugins to install. Required when install option is used.' )
			->option( '-i --install', 'Install plugins from .csv file' )
			->option( '-n --no-analyze', 'Skips analysis of plugins' )
			->usage(
				'<bold> analyze</end> <comment><./psalm-result/> [./plugins.csv] --install --no-analyze</end> ## details 1<eol/>' .
				'<bold> analyze</end> <comment><./out/> <./plugins/> -n</end> ## details 2<eol/>' .
				'<bold> analyze</end> <comment><./out/> [./plugins.csv] [./plugins/] -i -n</end> ## details 3<eol/>'
			);
	}

	public function parseCommand( array $argv ): Parser|false {
		$parser = $this->parse( $argv );

		if ( $this->install && ( $this->pluginCsvFile == null || ! $this->checkCorrectCsvFilepath( $this->pluginCsvFile ) ) ) {
			return false;
		}

		return $parser;
	}

	private function checkCorrectCsvFilepath( string $filepath ): bool {
		if ( ! file_exists( $filepath ) || ! str_ends_with( $filepath, ".csv" ) ) {
			print_r( "File \"$filepath\" not found or not a .csv file.\n" );

			return false;
		}

		return true;
	}
}
