<?php

declare (strict_types=1);
namespace EasyCI20220123\Symplify\SmartFileSystem\Finder;

use EasyCI20220123\Nette\Utils\Finder as NetteFinder;
use SplFileInfo;
use EasyCI20220123\Symfony\Component\Finder\Finder as SymfonyFinder;
use EasyCI20220123\Symfony\Component\Finder\SplFileInfo as SymfonySplFileInfo;
use EasyCI20220123\Symplify\SmartFileSystem\SmartFileInfo;
/**
 * @see \Symplify\SmartFileSystem\Tests\Finder\FinderSanitizer\FinderSanitizerTest
 */
final class FinderSanitizer
{
    /**
     * @param NetteFinder|SymfonyFinder|SplFileInfo[]|SymfonySplFileInfo[]|string[] $files
     * @return SmartFileInfo[]
     */
    public function sanitize($files) : array
    {
        $smartFileInfos = [];
        foreach ($files as $file) {
            $fileInfo = \is_string($file) ? new \SplFileInfo($file) : $file;
            if (!$this->isFileInfoValid($fileInfo)) {
                continue;
            }
            /** @var string $realPath */
            $realPath = $fileInfo->getRealPath();
            $smartFileInfos[] = new \EasyCI20220123\Symplify\SmartFileSystem\SmartFileInfo($realPath);
        }
        return $smartFileInfos;
    }
    private function isFileInfoValid(\SplFileInfo $fileInfo) : bool
    {
        return (bool) $fileInfo->getRealPath();
    }
}