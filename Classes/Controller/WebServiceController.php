<?php
namespace Fab\WebService\Controller;

/*
 * This file is part of the Fab/WebService project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use Fab\Vidi\Domain\Repository\ContentRepositoryFactory;
use Fab\Vidi\Persistence\Order;
use Fab\Vidi\Tca\Tca;
use Fab\WebService\Resolver\Settings;
use Fab\WebService\Resolver\SettingsResolver;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Fab\Vidi\Persistence\Matcher;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;

/**
 * Class WebServiceController
 */
class WebServiceController extends ActionController
{

    /**
     * @param string $route
     * @throws \Fab\Vidi\Exception\InvalidKeyInArrayException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \Fab\Vidi\Exception\NotExistingClassException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    public function outputAction($route)
    {

        $settings = $this->getSettingsResolver()->resolve($route);

        if ($settings->getContentType()) {

            $matcher = $this->getMatcher($settings);
            $order = $this->getOrder($settings);
            if ($settings->getManyOrOne() === Settings::MANY) {
                $objects = ContentRepositoryFactory::getInstance($settings->getContentType())->findBy($matcher, $order, $settings->getLimit());
            } else {
                if ($settings->getContentIdentifier() === 0) {
                    $message = sprintf('I could find a valid identifier "%s"', $settings->getContentIdentifier());
                    throw new \RuntimeException($message, 1472294542);
                }
                $matcher->equals('uid', $settings->getContentIdentifier());
                $objects = ContentRepositoryFactory::getInstance($settings->getContentType())->findOneBy($matcher);
            }

            // Early return
            if (!$objects) {
                return;
            }
            $this->view->assign('settings', $settings);
            $this->view->assign('objects', $objects);
            $this->view->assign('response', $this->controllerContext->getResponse());
        } else {
            $message = sprintf('I could find a valid content type for segment "%s"', $settings->getRouteSegments()[0]);
            throw new \RuntimeException($message, 1472294541);
        }

        $fileNameAndPath = 'EXT:web_service/Resources/Private/Templates/WebService/Output.' . $settings->getFormat();
        $templatePathAndFilename = GeneralUtility::getFileAbsFileName($fileNameAndPath);
        $this->view->setTemplatePathAndFilename($templatePathAndFilename);
    }

    /**
     * @param Settings $settings
     * @return Matcher
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \InvalidArgumentException
     */
    public function getMatcher(Settings $settings)
    {

        /** @var $matcher Matcher */
        $matcher = GeneralUtility::makeInstance(Matcher::class, $settings->getContentType());

        // Trigger signal for post processing Order Object.
        $this->emitPostProcessMatcherSignal($matcher);

        return $matcher;
    }

    /**
     * @param Settings $settings
     * @return Order
     * @throws \Fab\Vidi\Exception\NotExistingClassException
     * @throws \InvalidArgumentException
     */
    public function getOrder(Settings $settings)
    {
        $orderings = $settings->getOrderings();
        if (!$orderings) {
            $orderings = Tca::table($settings->getContentType())->getDefaultOrderings();
        }

        return GeneralUtility::makeInstance(Order::class, $orderings);
    }

    /**
     * @return SettingsResolver
     * @throws \InvalidArgumentException
     */
    protected function getSettingsResolver()
    {
        return GeneralUtility::makeInstance(SettingsResolver::class, $this->settings);
    }

    /**
     * Signal that is called for post processing matcher
     *
     * @signal
     * @param Matcher $matcher
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    protected function emitPostProcessMatcherSignal(Matcher $matcher)
    {
        $this->getSignalSlotDispatcher()->dispatch(self::class, 'postProcessMatcher', array($matcher));
    }

    /**
     * Get the SignalSlot dispatcher
     *
     * @return \TYPO3\CMS\Extbase\SignalSlot\Dispatcher
     */
    protected function getSignalSlotDispatcher()
    {
        return $this->objectManager->get(Dispatcher::class);
    }

}