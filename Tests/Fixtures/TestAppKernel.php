<?php

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Class TestAppKernel.
 */
class TestAppKernel extends Kernel
{
    /**
     * @return array
     */
    public function registerBundles()
    {
        return [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new MewesK\TwigSpreadsheetBundle\MewesKTwigSpreadsheetBundle(),
            new MewesK\TwigSpreadsheetBundle\Tests\Fixtures\TestBundle\TestBundle(),
        ];
    }

    /**
     * @param LoaderInterface $loader
     *
     * @throws \Exception
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }

    /**
     * @return string
     */
    public function getCacheDir()
    {
        return $this->getRootDir().'/../../tmp/cache';
    }

    /**
     * @return string
     */
    public function getLogDir()
    {
        return $this->getRootDir().'/../../tmp/logs';
    }
}
