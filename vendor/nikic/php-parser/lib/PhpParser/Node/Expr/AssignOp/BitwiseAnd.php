<?php

declare (strict_types=1);
namespace EasyCI202206\PhpParser\Node\Expr\AssignOp;

use EasyCI202206\PhpParser\Node\Expr\AssignOp;
class BitwiseAnd extends AssignOp
{
    public function getType() : string
    {
        return 'Expr_AssignOp_BitwiseAnd';
    }
}
