<?php

namespace MerapiPanel\Core\Token\node;

use MerapiPanel\Core\Token\Token;
use Twig\Node\Node;

class FormTokenNode extends Node
{

    private static $token;
    public function __construct(Node $node, int $lineno, string $tag = null)
    {
        
        // Initialize the node with 'body' and optionally other parts like 'attributes'
        parent::__construct(['body' => $node], [], $lineno, $tag);
    }

    public function compile(\Twig\Compiler $compiler)
    {

        if (!isset(self::$token) && !isset($GLOBALS['is_token_created'])) {
            self::$token = Token::generate();
            $GLOBALS['is_token_created'] = true;
        }
        
        $compiler->addDebugInfo($this)
            ->write("\$token = \"" . self::$token . "\";\n")
            ->write("echo \"<input name='m-token' type='hidden' value='\$token' />\";\n")
            ->subcompile($this->getNode('body'));
    }



    protected function getAttributesAsString()
    {
        // Assuming attributes are stored as an associative array
        $attributes = $this->getAttribute('attributes');
        $result = '';

        foreach ($attributes as $name => $value) {
            // Convert each attribute to a string part. 
            // Note: You might need to adjust this logic based on how attributes are actually stored and passed.
            $result .= sprintf(' %s="%s"', $name, htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
        }

        return $result;
    }
}
