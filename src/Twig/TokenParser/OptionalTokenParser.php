<?php
declare(strict_types=1);

namespace Shoot\Shoot\Twig\TokenParser;

use Shoot\Shoot\Twig\Node\OptionalNode;
use Twig_Error_Syntax as SyntaxError;
use Twig_Node as Node;
use Twig_Token as Token;
use Twig_TokenParser as AbstractTokenParser;

final class OptionalTokenParser extends AbstractTokenParser
{
    /**
     * Parses a token and returns a node.
     *
     * @param Token $token
     *
     * @throws SyntaxError
     *
     * @return Node
     */
    public function parse(Token $token): Node
    {
        $stream = $this->parser->getStream();

        $stream->expect(Token::BLOCK_END_TYPE);

        $body = $this->parser->subparse(function (Token $token): bool {
            return $token->test('endoptional');
        }, true);

        $stream->expect(Token::BLOCK_END_TYPE);

        return new OptionalNode($body, $token->getLine(), $this->getTag());
    }

    /**
     * @return string The tag name associated with this token parser.
     */
    public function getTag(): string
    {
        return 'optional';
    }
}
