<?php

namespace MerapiPanel\Module\Setting;

// CustomSettingTokenParser.php

use DOMDocument;
use MerapiPanel\Utility\AES;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;
use Twig\Node\Node;

class ViewParser extends AbstractTokenParser
{
    public function parse(Token $token)
    {
        $stream = $this->parser->getStream();
        $stream->expect(Token::BLOCK_END_TYPE);

        // Parse until "{ endsetting }"
        $nodes = $this->parser->subparse([$this, 'decideEnd'], true);

        $stream->expect(Token::BLOCK_END_TYPE);

        return new CustomSettingNode($nodes, $token->getLine(), $this->getTag());
    }

    public function decideEnd(Token $token)
    {
        return $token->test('endsetting');
    }

    public function getTag()
    {
        return 'setting';
    }
}

class CustomSettingNode extends Node
{
    public function __construct(Node $body, $line, $tag = null)
    {
        parent::__construct(['body' => $body], [], $line, $tag);
    }

    public function compile(\Twig\Compiler $compiler)
    {
        $compiler->addDebugInfo($this);

        // Get the content of the form block
        $formContent = $this->getNode('body')->getAttribute('data');

        // Use DOMDocument to parse HTML content
        $dom = new DOMDocument();
        @$dom->loadHTML($formContent);

        $inputNames = [];
        // Find all input elements and extract the 'name' attribute
        $inputs = $dom->getElementsByTagName('input');
        foreach ($inputs as $input) {
            $inputName = $input->getAttribute('name');
            if (!empty($inputName)) {
                $inputNames[] = $inputName;
            }
        }

        // Output the extracted input names
        $compiler->subcompile($this->getNode('body'))->write("echo \"<input type='hidden' name='setting_toke' value='" . AES::encrypt(serialize($inputNames)) . "'>\";");
    }
}
