<?php

declare (strict_types=1);
namespace EasyCI20220607\Symplify\EasyCI\Psr4\Json;

use EasyCI20220607\Nette\Utils\Json;
use EasyCI20220607\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use EasyCI20220607\Symplify\EasyCI\Psr4\FileSystem\Psr4PathNormalizer;
use EasyCI20220607\Symplify\EasyCI\Psr4\ValueObject\Psr4NamespaceToPaths;
final class JsonAutoloadPrinter
{
    /**
     * @var \Symplify\EasyCI\Psr4\FileSystem\Psr4PathNormalizer
     */
    private $psr4PathNormalizer;
    public function __construct(Psr4PathNormalizer $psr4PathNormalizer)
    {
        $this->psr4PathNormalizer = $psr4PathNormalizer;
    }
    /**
     * @param Psr4NamespaceToPaths[] $psr4NamespaceToPaths
     */
    public function createJsonAutoloadContent(array $psr4NamespaceToPaths) : string
    {
        $normalizedJsonArray = $this->psr4PathNormalizer->normalizePsr4NamespaceToPathsToJsonsArray($psr4NamespaceToPaths);
        $composerJson = [ComposerJsonSection::AUTOLOAD => ['psr-4' => $normalizedJsonArray]];
        return Json::encode($composerJson, Json::PRETTY);
    }
}
