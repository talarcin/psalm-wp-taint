<?php

namespace Tuncay\PsalmWpTaint\src;

use Psalm\Plugin\EventHandler\AfterCodebasePopulatedInterface;
use Psalm\Plugin\EventHandler\Event\AfterCodebasePopulatedEvent;

class AddActionParserInitializer implements AfterCodebasePopulatedInterface
{

    public static function afterCodebasePopulated(AfterCodebasePopulatedEvent $event)
    {
        AddActionParser::getInstance()->readActionsMapFromFile("./add-actions-map.json");
    }
}