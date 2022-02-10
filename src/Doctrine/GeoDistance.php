<?php

namespace App\Doctrine;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * Usage: GEO_DISTANCE(latFrom, longFrom, latDest, longDest)
 */
class GeoDistance extends FunctionNode
{
    protected $latFrom;
    protected $longFrom;
    protected $latDest;
    protected $longDest;

    public function parse(Parser $parser): void
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->latFrom = $parser->ArithmeticExpression();
        $parser->match(Lexer::T_COMMA);
        $this->longFrom = $parser->ArithmeticExpression();
        $parser->match(Lexer::T_COMMA);
        $this->latDest = $parser->ArithmeticExpression();
        $parser->match(Lexer::T_COMMA);
        $this->longDest = $parser->ArithmeticExpression();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        return sprintf(
            '12742 * ASIN(SQRT(POWER(SIN((CAST(%s AS numeric) - CAST(%s AS numeric)) * PI()/360), 2) + COS(%s * PI()/180) * COS(%s * PI()/180) * POWER(SIN((CAST(%s AS numeric) - CAST(%s AS numeric)) * PI()/360), 2)))',
            $sqlWalker->walkArithmeticPrimary($this->latFrom),
            $sqlWalker->walkArithmeticPrimary($this->latDest),
            $sqlWalker->walkArithmeticPrimary($this->latFrom),
            $sqlWalker->walkArithmeticPrimary($this->latDest),
            $sqlWalker->walkArithmeticPrimary($this->longFrom),
            $sqlWalker->walkArithmeticPrimary($this->longDest)
        );
    }
}