<?php
namespace Fab\WebService\Controller;

/*
 * This file is part of the Fab/WebService project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use Fab\Vidi\Domain\Repository\ContentRepositoryFactory;
use RuntimeException;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Fab\Vidi\Persistence\Matcher;
use Fab\Vidi\Tca\Tca;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;

/**
 * Controller class for handling action related to a product.
 */
class WebServiceController extends ActionController
{

    /**
     * @param string $dataType
     * @param int $identifier
     * @param string $secondaryDataType
     * @param array $matcher
     * @return void
     */
    public function displayAction($dataType = 'fe_users', $identifier = 0, $secondaryDataType = '', array $matcher = array())
    {

        $dataType = $this->resolveDataType($dataType);
        $secondaryDataType = $this->resolveDataType($secondaryDataType);

        $matcher = $this->getMatcher();

        // mm_processes
        $currentDataType = $dataType;

        if (!empty($secondaryDataType)) {
            $currentDataType = $secondaryDataType;

//			$joinField = Tca::table($secondaryDataType)->searchJoinField($dataType); # could also be many ->searchJoinFields($dataType);
//			if (Tca::table($secondaryDataType)->field($joinField)->hasRelationManyToMany()) {
//			    $matcher->equals('mm_processes.tx_bobst_products', $identifier);
            #$matcher->equals($joinField . '.' . $dataType, $identifier);
//			} else {
//			    $matcher->equals('tx_bobst_products', $identifier);
//			}
        }
        $contentObjects = ContentRepositoryFactory::getInstance($currentDataType)->findBy($matcher);

        $output = array();
        $labelField = Tca::table($currentDataType)->getLabelField();

        foreach ($contentObjects as $contentObject) {
            $_record = array();
            $_record['uid'] = $contentObject['uid'];
            $_record[$labelField] = $contentObject[$labelField];
            $output[] = $_record;
        }

        $this->view->assign('entries', $output);
        $this->view->setTemplatePathAndFilename($this->getTemplatePathAndFileName());
    }

    /**
     * Returns a matcher object.
     *
     * @param string $dataType
     * @return Matcher
     * @throws \InvalidArgumentException
     */
    public function getMatcher($dataType = '')
    {

        /** @var $matcher Matcher */
        $matcher = GeneralUtility::makeInstance(Matcher::class);

        return $matcher;
    }

    /**
     * @param string $dataType
     * @return string
     * @throws \RuntimeException
     */
    protected function resolveDataType($dataType)
    {
        $resolvedDataType = '';
        if ($dataType) {
            foreach ($this->settings['dataTypes'] as $tableName => $mappingValues) {
                if ($mappingValues['mapping'] == $dataType) {
                    $resolvedDataType = $tableName;
                    break;
                }
            }

            if (!$resolvedDataType) {
                throw new RuntimeException(sprintf('I could not resolved "%s"', $dataType), 1399883431);
            }
        }
        return $resolvedDataType;
    }

    /**
     * @return string
     * @throws \BadFunctionCallException
     */
    protected function getTemplatePathAndFileName()
    {
        return ExtensionManagementUtility::extPath('web_service') . 'Resources/Private/Templates/WebService/Display.json';
    }

    /**
     * Signal that is called for post processing matcher
     *
     * @signal
     * @param Matcher $matcher
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    protected function emitPostProcessRequestSignal(Matcher $matcher)
    {
        $this->getSignalSlotDispatcher()->dispatch('Fab\WebService\Controller\WebServiceController', 'postProcessMatcher', array($matcher));
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