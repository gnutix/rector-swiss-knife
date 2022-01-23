<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace EasyCI20220123\Symfony\Component\Console\Output;

use EasyCI20220123\Symfony\Component\Console\Formatter\NullOutputFormatter;
use EasyCI20220123\Symfony\Component\Console\Formatter\OutputFormatterInterface;
/**
 * NullOutput suppresses all output.
 *
 *     $output = new NullOutput();
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Tobias Schultze <http://tobion.de>
 */
class NullOutput implements \EasyCI20220123\Symfony\Component\Console\Output\OutputInterface
{
    private $formatter;
    /**
     * {@inheritdoc}
     */
    public function setFormatter(\EasyCI20220123\Symfony\Component\Console\Formatter\OutputFormatterInterface $formatter)
    {
        // do nothing
    }
    /**
     * {@inheritdoc}
     */
    public function getFormatter() : \EasyCI20220123\Symfony\Component\Console\Formatter\OutputFormatterInterface
    {
        // to comply with the interface we must return a OutputFormatterInterface
        return $this->formatter = $this->formatter ?? new \EasyCI20220123\Symfony\Component\Console\Formatter\NullOutputFormatter();
    }
    /**
     * {@inheritdoc}
     */
    public function setDecorated(bool $decorated)
    {
        // do nothing
    }
    /**
     * {@inheritdoc}
     */
    public function isDecorated() : bool
    {
        return \false;
    }
    /**
     * {@inheritdoc}
     */
    public function setVerbosity(int $level)
    {
        // do nothing
    }
    /**
     * {@inheritdoc}
     */
    public function getVerbosity() : int
    {
        return self::VERBOSITY_QUIET;
    }
    /**
     * {@inheritdoc}
     */
    public function isQuiet() : bool
    {
        return \true;
    }
    /**
     * {@inheritdoc}
     */
    public function isVerbose() : bool
    {
        return \false;
    }
    /**
     * {@inheritdoc}
     */
    public function isVeryVerbose() : bool
    {
        return \false;
    }
    /**
     * {@inheritdoc}
     */
    public function isDebug() : bool
    {
        return \false;
    }
    /**
     * {@inheritdoc}
     * @param mixed[]|string $messages
     */
    public function writeln($messages, int $options = self::OUTPUT_NORMAL)
    {
        // do nothing
    }
    /**
     * {@inheritdoc}
     * @param mixed[]|string $messages
     */
    public function write($messages, bool $newline = \false, int $options = self::OUTPUT_NORMAL)
    {
        // do nothing
    }
}