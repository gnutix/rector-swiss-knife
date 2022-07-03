<?php

declare (strict_types=1);
namespace EasyCI202207\Symplify\Astral\PhpDocParser\ValueObject\Ast\PhpDoc;

use EasyCI202207\PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use EasyCI202207\PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use EasyCI202207\PHPStan\PhpDocParser\Ast\Type\TypeNode;
/**
 * @noRector final on purpose, so it can be extended by 3rd party
 */
class SimplePhpDocNode extends PhpDocNode
{
    public function getParam(string $desiredParamName) : ?ParamTagValueNode
    {
        $desiredParamNameWithDollar = '$' . \ltrim($desiredParamName, '$');
        foreach ($this->getParamTagValues() as $paramTagValueNode) {
            if ($paramTagValueNode->parameterName !== $desiredParamNameWithDollar) {
                continue;
            }
            return $paramTagValueNode;
        }
        return null;
    }
    public function getParamType(string $desiredParamName) : ?TypeNode
    {
        $paramTagValueNode = $this->getParam($desiredParamName);
        if (!$paramTagValueNode instanceof ParamTagValueNode) {
            return null;
        }
        return $paramTagValueNode->type;
    }
}
