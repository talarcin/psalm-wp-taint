<?php

namespace Tuncay\PsalmWpTaint;

use SimpleXMLElement;
use Psalm\Plugin\PluginEntryPointInterface;
use Psalm\Plugin\RegistrationInterface;

class Plugin implements PluginEntryPointInterface
{

    /**
     * @param RegistrationInterface $registration
     * @param SimpleXMLElement|null $config
     * @return void
     */
    public function __invoke(RegistrationInterface $registration, ?SimpleXMLElement $config = null): void
    {

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
