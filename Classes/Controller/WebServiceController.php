<?php
namespace Vanilla\WebService\Controller;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Fabien Udriot <fabien.udriot@typo3.org>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
use Cobweb\BobstForms\Domain\Model\Request;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Vidi\ContentRepositoryFactory;
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
	public function displayAction($dataType, $identifier = 0, $secondaryDataType = '', array $matcher = array()) {

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