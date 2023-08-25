<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\UX\TwigComponent\Twig;

use Twig\Compiler;
use Twig\Node\EmbedNode;
use Twig\Node\Expression\AbstractExpression;

/**
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Kevin Bond <kevinbond@gmail.com>
 *
 * @internal
 */
final class ComponentNode extends EmbedNode
{
    public function __construct(string $component, string $template, int $index, AbstractExpression $variables, bool $only, int $lineno, string $tag)
    {
        parent::__construct($template, $index, $variables, $only, false, $lineno, $tag);

        $this->setAttribute('component', $component);
    }

    public function compile(Compiler $compiler): void
    {
        $compiler->addDebugInfo($this);

        $compiler
            ->write('$embeddedContext = $this->extensions[')
            ->string(ComponentExtension::class)
            ->raw(']->embeddedContext(')
            ->string($this->getAttribute('component'))
            ->raw(', ')
            ->raw('twig_to_array(')
            ->subcompile($this->getNode('variables'))
            ->raw('), ')
            ->raw($this->getAttribute('only') ? '[]' : '$context')
            ->raw(");\n")
        ;

        $compiler->write('$embeddedBlocks = $embeddedContext[')
            ->string('outerBlocks')
            ->raw(']->convert($blocks, ')
            ->raw($this->getAttribute('index'))
            ->raw(");\n")
        ;

        $this->addGetTemplate($compiler);
        $compiler->raw('->display($embeddedContext, $embeddedBlocks);');
        $compiler->raw("\n");
    }
}
