<?php
namespace MerapiPanel\Module\Editor\Component;

class Block
{
    const required = ['title', 'name', 'index', 'save', 'style'];
    public readonly null|string $name; // required
    public readonly null|string $title; // required
    public readonly null|string $editScript; // required
    public readonly null|string $editStyle; // required
    public readonly null|string $save; // required
    public readonly null|string $style; // required
    public readonly null|string $index;

    public string $category;
    public mixed $icon;
    public string $description;
    public array $attributes;
    public array $options;
    public mixed $render;


    public function __construct($name, mixed $props)
    {
        
        // // Check if all required keys exist in the props array
        // if (count(array_diff(self::required, array_keys($props))) > 0) {
        //     throw new \Exception('Invalid props array. Missing required keys ' . implode(", ",array_diff(self::required, array_keys($props))));
        // }

        // Set class properties from the props array
        $this->name = $name;
        $this->title = $props['title'] ?? null;
        $this->save = $props['save'] ?? null;
        $this->style = $props['style'] ?? null;
        $this->index = $props['index'] ?? null;

        // Set additional class properties from the props array
        $this->description = $props['description'] ?? null;
        $this->attributes = $props['attributes'] ?? [];
    }
}
