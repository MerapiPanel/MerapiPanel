<?php

namespace MerapiPanel\Core\View\Component;

use MerapiPanel\Box;
use MerapiPanel\Core\Mod\Proxy;
use MerapiPanel\Event;
use Twig\Source;

class ProcessingComponent implements \Twig\Loader\LoaderInterface
{

    const ON_BEFORE_PROCESSING = "on_before_processing";
    const ON_AFTER_PROCESSING = "on_after_processing";

    private $loader;



    public function __construct(\Twig\Loader\LoaderInterface $loader)
    {
        $this->loader = $loader;
    }






    public function getSourceContext($name): Source
    {
        $sourceContext = $this->loader->getSourceContext($name);

        // Custom preprocessing logic to handle the specific pattern
        $processedContent = $this->processComponentTags($sourceContext->getCode());

        return new \Twig\Source($processedContent, $sourceContext->getName(), $sourceContext->getPath());
    }






    protected function processComponentTags($content)
    {
        // Regex to find and process the custom tag pattern
        $pattern = '/<comp:([^:]+):([^ ]+)([^>]*)>(.*?)<\/comp:\1:\2>/s';

        return preg_replace_callback($pattern, function ($matches) {

            Event::fire(self::ON_BEFORE_PROCESSING, $matches);
            // Extract the parts of the tag
            $module = $matches[1];
            $method = $matches[2];
            $attributes = trim($matches[3]);

            $instanceAddress = "Module_{$module}_Views_Component";
            $instance = Box::Get($this)->$instanceAddress();

            $output = "";
            if ($instance instanceof Proxy && (method_exists(Proxy::Real($instance), $method))) {

                $argumentsAttribute = self::parseAttributesToAssocArray($attributes);
                $output = $instance->$method(...array_values($argumentsAttribute));
            }

            Event::fire(self::ON_AFTER_PROCESSING, [$module, $method, $attributes, &$output]);

            // Example transformation: turn it into a div with data attributes (and potentially inner content)
            return $output;
        }, $content);
    }



    public function getCacheKey($name): string
    {
        // Delegate to the wrapped loader
        return $this->loader->getCacheKey($name);
    }





    public function isFresh($name, $time): bool
    {
        // Delegate to the wrapped loader
        return $this->loader->isFresh($name, $time);
    }




    public function exists($name): bool
    {
        return $this->loader->exists($name);
    }







    static function isMookRender(): bool
    {

        if (isset($_SERVER['HTTP_MOOK_RENDER']) || isset($_SERVER['HTTP_TEMPLATE_EDIT'])) {

            return true;
        }
        return false;
    }



    static function parseAttributesToAssocArray($attributesString)
    {
        $attributesArray = [];
        $pattern = '/(\w+)="([^"]*)"/';

        if (preg_match_all($pattern, $attributesString, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $attributesArray[$match[1]] = $match[2];
            }
        }

        return $attributesArray;
    }
}
