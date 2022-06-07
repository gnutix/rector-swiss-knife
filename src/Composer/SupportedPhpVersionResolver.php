<?php

declare (strict_types=1);
namespace EasyCI20220607\Symplify\EasyCI\Composer;

use EasyCI20220607\Composer\Semver\Semver;
use EasyCI20220607\Composer\Semver\VersionParser;
use DateTimeInterface;
use EasyCI20220607\Nette\Utils\DateTime;
use EasyCI20220607\Symplify\ComposerJsonManipulator\ComposerJsonFactory;
use EasyCI20220607\Symplify\EasyCI\Exception\ShouldNotHappenException;
use EasyCI20220607\Symplify\EasyCI\ValueObject\PhpVersionList;
/**
 * @see \Symplify\EasyCI\Tests\Composer\SupportedPhpVersionResolverTest
 */
final class SupportedPhpVersionResolver
{
    /**
     * @var \Composer\Semver\VersionParser
     */
    private $versionParser;
    /**
     * @var \Composer\Semver\Semver
     */
    private $semver;
    /**
     * @var \Symplify\ComposerJsonManipulator\ComposerJsonFactory
     */
    private $composerJsonFactory;
    public function __construct(VersionParser $versionParser, Semver $semver, ComposerJsonFactory $composerJsonFactory)
    {
        $this->versionParser = $versionParser;
        $this->semver = $semver;
        $this->composerJsonFactory = $composerJsonFactory;
    }
    /**
     * @return string[]
     */
    public function resolveFromComposerJsonFilePath(string $composerJsonFilePath) : array
    {
        $composerJson = $this->composerJsonFactory->createFromFilePath($composerJsonFilePath);
        $requirePhpVersion = $composerJson->getRequirePhpVersion();
        if ($requirePhpVersion === null) {
            $message = \sprintf('PHP version was not found in "%s"', $composerJsonFilePath);
            throw new ShouldNotHappenException($message);
        }
        return $this->resolveFromConstraints($requirePhpVersion, DateTime::from('now'));
    }
    /**
     * @return string[]
     */
    public function resolveFromConstraints(string $phpVersionConstraints, DateTimeInterface $todayDateTime) : array
    {
        // to validate version
        $this->versionParser->parseConstraints($phpVersionConstraints);
        $supportedPhpVersion = [];
        foreach (PhpVersionList::VERSIONS_BY_RELEASE_DATE as $releaseDate => $phpVersion) {
            if (!$this->semver->satisfies($phpVersion, $phpVersionConstraints)) {
                continue;
            }
            // is in the future?
            $relaseDateTime = DateTime::from($releaseDate);
            if ($relaseDateTime > $todayDateTime) {
                continue;
            }
            $supportedPhpVersion[] = $phpVersion;
        }
        return $supportedPhpVersion;
    }
}
