<?php

namespace il4mb\Mpanel\Core;

abstract class AppAware
{

    abstract function setBox(Box $box);
    abstract function getBox(): ?Box;
}