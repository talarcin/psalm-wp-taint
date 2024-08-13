<?php


final class PsalmWpTaintAnalysis
{

  public static function run(AnalyzeCommand $command): void
  {
    if (str_contains(__DIR__, '')) {
      chdir("./");
    }

    $pluginsDir = $command->plugins_directory != '' ? $command->plugins_directory : "./wp-content/plugins/";

    if (!$command->noInstall && $command->plugin_csv_list) {
      self::installPlugins(file($command->plugin_csv_list), $pluginsDir);
    }
  }

  private static function installPlugins(array $csvFileLines, string $pluginsDir): void
  {

    foreach ($csvFileLines as $line) {
      print_r("----------------------------------------\n");
      [$pluginSlug, $pluginVersion] = explode(",", $line);

      if (is_dir("$pluginsDir/$pluginSlug")) {
        print_r("Plugin \"$pluginSlug\" already installed. Skipping installation.\n");
        continue;
      }

      print_r("Installing plugin:\n \"$pluginSlug (v$pluginVersion) \" ...\n");
      exec("ddev wp plugin install $pluginSlug --version=$pluginVersion");
    }

    print_r("Plugins installed successfully.\n");
    print_r("----------------------------------------\n");
  }
}
