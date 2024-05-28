<?php

namespace Tuncay\PsalmWpTaint;

use SimpleXMLElement;
use Psalm\Plugin\PluginEntryPointInterface;
use Psalm\Plugin\RegistrationInterface;
use Tuncay\PsalmWpTaint\src\AddActionChecker;
use Tuncay\PsalmWpTaint\src\AddActionCheckFinisher;

class Plugin implements PluginEntryPointInterface
{

    /**
     * @param RegistrationInterface $registration
     * @param SimpleXMLElement|null $config
     * @return void
     */
    public function __invoke(RegistrationInterface $registration, ?SimpleXMLElement $config = null): void
    {
        require_once "src/AddActionChecker.php";
        $registration->registerHooksFromClass(AddActionChecker::class);

        require_once "src/AddActionCheckFinisher.php";
        $registration->registerHooksFromClass(AddActionCheckFinisher::class);
        
        foreach ($this->getStubFiles() as $file) {
            $registration->addStubFile($file);
        }
    }

    /**
     * @return array
     */
    private function getStubFiles(): array
    {
        print_r("Getting stub files..." . PHP_EOL);
        return glob(__DIR__ . '/stubs/*.phpstub') ?: [];
    }
}
