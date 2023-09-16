<?php

namespace Mp;

abstract class AppAware
{

    abstract function setBox(Box $box);
    abstract function getBox(): ?Box;
}