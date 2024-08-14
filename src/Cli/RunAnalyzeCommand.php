<?php
namespace Tuncay\PsalmWpTaint\src\Cli;

use Ahc\Cli\Input\Command;
use Ahc\Cli\Input\Parser;

class RunAnalyzeCommand extends Command
{

  public function __construct()
  {
    parent::__construct('run', 'Run analyze script');

    $this
      ->argument('<plugin_csv_list>', 'The .csv list containing the plugin slugs and versions of the plugins to install.')
      ->argument('<output_filename>', 'The absolute path to the output directory.')
      ->argument('[plugins_directory]', 'Optional argument to set other installation directory of plugins. Default: /wp-content/plugins/')
      ->option('-i --noInstall', 'Skips installation of plugins from .csv file')
      ->option('-a --noAnalyze', 'Skips analysis of plugins')
      ->usage(
        '<bold> analyze</end> <comment>--no-install --no-analyze <./plugins.csv> <./psalm-result/></end> ## details 1<eol/>' .
          '<bold> analyze</end> <comment>-i -a <./data/plugins.csv> <./out/> <./plugins/></end> ## details 2<eol/>'
      );
  }

  public function parseCommand(array $argv): Parser|false
  {
    $parser = $this->parse($argv);

    if (!$this->checkCorrectCsvFilepath($this->plugin_csv_list)) return false;

    return $parser;
  }

  private function checkCorrectCsvFilepath(string $filepath): bool
  {
    if (is_dir($filepath)) {
      print_r("Path \"$filepath\" leads to a directory. Please input a path leading to the correct csv file.\n");
      return false;
    }

    if (!file_exists($filepath) || !str_ends_with($filepath, ".csv")) {
      print_r("File \"$filepath\" not found or not a .csv file.\n");
      return false;
    }

    if (file_get_contents($filepath) == null) {
      print_r("No data found in $filepath.\n");
      return false;
    }
  }
}
