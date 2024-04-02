<?php

namespace MerapiPanel\Core\Token\parser;

use MerapiPanel\Core\Token\node\FormTokenNode;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

class FormToken extends AbstractTokenParser
{
    public function parse(Token $token)
    {

        $parser = $this->parser;
        $stream = $parser->getStream();

        // $attributes = $this->parseAttributes($stream);

        // Get the line number and tag name for the starting token
        $lineNumber = $token->getLine();
        $tagName = $this->getTag();

        // Skip over the 'custom_element' token that opened this block
        $stream->expect(Token::BLOCK_END_TYPE);

        // Parse the content inside the custom_element block until we encounter an 'end_custom_element' tag
        $body = $parser->subparse([$this, 'decideCustomElementEnd'], true);

        // Consume the 'end_custom_element' token
        $stream->expect(Token::BLOCK_END_TYPE);

        return new FormTokenNode($body, $lineNumber, $tagName);
    }

    public function decideCustomElementEnd(Token $token)
    {
        return $token->test('end_' . $this->getTag());
    }


    public function getTag()
    {
        return 'form_token';
    }
}
