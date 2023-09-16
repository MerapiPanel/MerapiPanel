<?php

namespace App\Service\Twig;

use Twig\Environment as BaseEnvironment;

class Environment extends BaseEnvironment
{
    private $presetGlobals = ['ea'];

    public function setPresetGlobals(array $names)
    {
        $this->presetGlobals = $names;
    }

    public function addGlobal(string $name, $value)
    {
        $presetGlobals = $this->presetGlobals;

        if (in_array($name, $presetGlobals)) {
            
            $ref = (new \ReflectionObject($this))->getParentClass();

            $globalsProperty = $ref->getProperty('globals');
            $globalsProperty->setAccessible(true);

            $resolvedGlobalsProperty  = $ref->getProperty('resolvedGlobals');
            $resolvedGlobalsProperty->setAccessible(true);

            $globals = $globalsProperty->getValue($this);
            $resolvedGlobals = $resolvedGlobalsProperty->getValue($this);

            foreach ($presetGlobals as $global) {
                if ($name == $global) {
                    if ($resolvedGlobals !== null) {
                        $resolvedGlobals[$global] = null;
                    } else {
                        $globals[$global] = null;
                    }
                }
            }

            $globalsProperty->setValue($this, $globals);
            $resolvedGlobalsProperty->setValue($this, $resolvedGlobals);
        }

        parent::addGlobal($name, $value);
    }
}
