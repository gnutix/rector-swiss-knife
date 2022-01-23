<?php

declare (strict_types=1);
namespace EasyCI20220123\PhpParser\Node\Expr;

use EasyCI20220123\PhpParser\Node\Expr;
class Include_ extends \EasyCI20220123\PhpParser\Node\Expr
{
    const TYPE_INCLUDE = 1;
    const TYPE_INCLUDE_ONCE = 2;
    const TYPE_REQUIRE = 3;
    const TYPE_REQUIRE_ONCE = 4;
    /** @var Expr Expression */
    public $expr;
    /** @var int Type of include */
    public $type;
    /**
     * Constructs an include node.
     *
     * @param Expr  $expr       Expression
     * @param int   $type       Type of include
     * @param array $attributes Additional attributes
     */
    public function __construct(\EasyCI20220123\PhpParser\Node\Expr $expr, int $type, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->expr = $expr;
        $this->type = $type;
    }
    public function getSubNodeNames() : array
    {
        return ['expr', 'type'];
    }
    public function getType() : string
    {
        return 'Expr_Include';
    }
}