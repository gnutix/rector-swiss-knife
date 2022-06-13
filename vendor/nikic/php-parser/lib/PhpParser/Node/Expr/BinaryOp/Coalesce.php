<?php

declare (strict_types=1);
namespace EasyCI202206\PhpParser\Node\Expr\BinaryOp;

use EasyCI202206\PhpParser\Node\Expr\BinaryOp;
class Coalesce extends BinaryOp
{
    public function getOperatorSigil() : string
    {
        return '??';
    }
    public function getType() : string
    {
        return 'Expr_BinaryOp_Coalesce';
    }
}
