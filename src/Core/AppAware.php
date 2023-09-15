<?php

namespace Mp\Core;

abstract class AppAware
{

    abstract function setBox(Box $box);
    abstract function getBox(): ?Box;
}