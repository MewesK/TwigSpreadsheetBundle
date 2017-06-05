<?php

namespace MewesK\TwigSpreadsheetBundle\Wrapper;

/**
 * Class SheetWrapper.
 */
class RowWrapper extends BaseWrapper
{
    /**
     * @var array
     */
    protected $context;
    /**
     * @var \Twig_Environment
     */
    protected $environment;
    /**
     * @var SheetWrapper
     */
    protected $sheetWrapper;

    /**
     * RowWrapper constructor.
     *
     * @param array             $context
     * @param \Twig_Environment $environment
     * @param SheetWrapper      $sheetWrapper
     */
    public function __construct(array $context, \Twig_Environment $environment, SheetWrapper $sheetWrapper)
    {
        $this->context = $context;
        $this->environment = $environment;
        $this->sheetWrapper = $sheetWrapper;
    }

    /**
     * @param null|int $index
     *
     * @throws \LogicException
     */
    public function start(int $index = null)
    {
        if ($this->sheetWrapper->getObject() === null) {
            throw new \LogicException();
        }

        if ($index === null) {
            $this->sheetWrapper->increaseRow();
        } else {
            $this->sheetWrapper->setRow($index);
        }
    }

    public function end()
    {
        $this->sheetWrapper->setColumn(null);
    }
}
