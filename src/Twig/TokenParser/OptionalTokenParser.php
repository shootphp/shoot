<?php
declare(strict_types=1);

namespace Shoot\Shoot\Twig\TokenParser;

use Shoot\Shoot\Twig\Node\OptionalNode;
use Twig\Error\SyntaxError;
use Twig\Node\Node;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

/**
 * Parses optional tags in the token stream.
 *
 * @internal
 */
final class OptionalTokenParser extends AbstractTokenParser
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

        $stream->expect(Token::BLOCK_END_TYPE);

        $body = $this->parser->subparse(function (Token $token): bool {
            return $token->test('endoptional');
        }, true);

        $stream->expect(Token::BLOCK_END_TYPE);

        return new OptionalNode($body, $token->getLine(), $this->getTag());
    }

    /**
     * @return string
     */
    public function getTag(): string
    {
        return 'optional';
    }
}
