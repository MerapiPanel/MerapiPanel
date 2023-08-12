<?php

namespace il4mb\Mpanel\Core\Plugin;

interface PluginInterface
{

    public function getName(): string;

    public function getDescription(): string;

    public function enable(): void;

    public function disable(): void;

    public function configure(array $config): void;

    public function run(): void;

}
