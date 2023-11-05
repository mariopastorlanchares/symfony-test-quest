<?php

namespace App\DQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\Query\SqlWalker;

class RandomFunction extends FunctionNode
{
    /**
     * @throws QueryException
     */
    public function parse(Parser $parser): void
    {
        // No hay necesidad de analizar argumentos para RANDOM().
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        // Para PostgreSQL, usamos RANDOM().
        return 'RANDOM()';
    }
}
