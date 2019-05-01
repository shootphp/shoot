<?php
declare(strict_types=1);

namespace Shoot\Shoot\Twig\TokenParser;

use Shoot\Shoot\Twig\Node\ModelNode;
use Twig\Error\SyntaxError;
use Twig\Node\Node;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

/**
 * Parses model tags in the token stream.
 *
 * @internal
 */
final class ModelTokenParser extends AbstractTokenParser
{
    /**
     * @param Token $token
     *
     * @return Node
     *
     * @throws SyntaxError
     */
    public function parse(Token $token): Node
    {
        $stream = $this->parser->getStream();

        $presentationModel = $stream->expect(Token::STRING_TYPE)->getValue();

        $stream->expect(Token::BLOCK_END_TYPE);

        return new ModelNode($presentationModel, $token->getLine(), $this->getTag());
    }

    /**
     * @return string
     */
    public function getTag(): string
    {
        return 'model';
    }
}
