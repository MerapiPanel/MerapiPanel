<?php
namespace MerapiPanel\Module\Editor\Component;

class Block
{
    const required = ['title', 'name', 'editScript', 'editStyle', 'save', 'style'];
    public readonly string $name; // required
    public readonly string $title; // required
    public readonly string $editScript; // required
    public readonly string  $editStyle; // required
    public readonly string  $save; // required
    public readonly string  $style; // required

    public string $category;
    public mixed $icon;
    public string $description;
    public array $attributes;
    public array $options;
    public mixed $render;


    public function __construct($name, array $props)
    {
        // Check if all required keys exist in the props array
        if (count(array_diff(self::required, array_keys($props))) > 0) {
            throw new \Exception('Invalid props array. Missing required keys.');
        }

        // Set class properties from the props array
        $this->name = $name;
        $this->title = $props['title'];
        $this->editScript = $props['editScript'];
        $this->editStyle = $props['editStyle'];

        // Set additional class properties from the props array
        $this->description = $props['description'] ?? null;
        $this->attributes = $props['attributes'] ?? [];
    }
}
