<?php
namespace Vanilla\WebService\Controller;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use Cobweb\BobstForms\Domain\Model\Request;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Vidi\Domain\Repository\ContentRepositoryFactory;
use TYPO3\CMS\Vidi\Persistence\Matcher;
use TYPO3\CMS\Vidi\Tca\TcaService;

/**
 * Controller class for handling action related to a product.
 */
class WebServiceController extends ActionController {

	/**
	 * @param string $dataType
	 * @param int $identifier
	 * @param string $secondaryDataType
	 * @param array $matcher
	 * @return void
	 */
	public function displayAction($dataType = 'fe_users', $identifier = 0, $secondaryDataType = '', array $matcher = array()) {

		$dataType = $this->resolveDataType($dataType);
		$secondaryDataType = $this->resolveDataType($secondaryDataType);

		$matcher = $this->getMatcher();

		// mm_processes
		$currentDataType = $dataType;

		if (!empty($secondaryDataType)) {
			$currentDataType = $secondaryDataType;

//			$joinField = TcaService::table($secondaryDataType)->searchJoinField($dataType); # could also be many ->searchJoinFields($dataType);
//			if (TcaService::table($secondaryDataType)->field($joinField)->hasRelationManyToMany()) {
//			    $matcher->equals('mm_processes.tx_bobst_products', $identifier);
				#$matcher->equals($joinField . '.' . $dataType, $identifier);
//			} else {
//			    $matcher->equals('tx_bobst_products', $identifier);
//			}
		}
		$contentObjects = ContentRepositoryFactory::getInstance($currentDataType)->findBy($matcher);

		$output = array();
		$labelField = TcaService::table($currentDataType)->getLabelField();

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
	 * @return \TYPO3\CMS\Vidi\Persistence\Matcher
	 */
	public function getMatcher($dataType = '') {

		/** @var $matcher \TYPO3\CMS\Vidi\Persistence\Matcher */
		$matcher = GeneralUtility::makeInstance('TYPO3\CMS\Vidi\Persistence\Matcher');

		return $matcher;
	}

	/**
	 * @param string $dataType
	 * @throws \Exception
	 * @return string
	 */
	protected function resolveDataType($dataType) {
		$resolvedDataType = '';
		if ($dataType) {
			foreach ($this->settings['dataTypes'] as $tableName => $mappingValues) {
				if ($mappingValues['mapping'] == $dataType) {
					$resolvedDataType = $tableName;
					break;
				}
			}

			if (!$resolvedDataType) {
				throw new \Exception(sprintf('I could not resolved "%s"', $dataType), 1399883431);
			}
		}
		return $resolvedDataType;
	}

	/**
	 * @return string
	 */
	protected function getTemplatePathAndFileName() {
		return ExtensionManagementUtility::extPath('web_service') . 'Resources/Private/Templates/WebService/Display.json';
	}

	/**
	 * Signal that is called for post processing matcher
	 *
	 * @signal
	 */
	protected function emitPostProcessRequestSignal(Matcher $matcher) {
		$this->getSignalSlotDispatcher()->dispatch('Vanilla\WebService\Controller\WebServiceController', 'postProcessMatcher', array($matcher));
	}

	/**
	 * Get the SignalSlot dispatcher
	 *
	 * @return \TYPO3\CMS\Extbase\SignalSlot\Dispatcher
	 */
	protected function getSignalSlotDispatcher() {
		return $this->objectManager->get('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher');
	}

}