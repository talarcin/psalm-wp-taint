<?php

namespace Tuncay\PsalmWpTaint\src;

use Psalm\Plugin\EventHandler\AfterCodebasePopulatedInterface;
use Psalm\Plugin\EventHandler\Event\AfterCodebasePopulatedEvent;

/**
 * @author Tuncay Alarcin
 */
class AddActionParserInitializer implements AfterCodebasePopulatedInterface
{
    public static function afterCodebasePopulated(AfterCodebasePopulatedEvent $event): void {
        AddActionParser::getInstance()->readActionsMapFromFile("./add-actions-map.json");
    }
}