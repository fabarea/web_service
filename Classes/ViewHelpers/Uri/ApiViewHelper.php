<?php
namespace Vanilla\WebService\ViewHelpers\Uri;

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
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * View Helper which returns a URI understandable by the web service.
 */
class ApiViewHelper extends AbstractViewHelper {

	/**
	 * Renders a URI understandable by the web service.
	 *
	 * @param string $dataType
	 * @param int $id
	 * @param string $secondaryDataType
	 * @return string
	 */
	public function render($dataType, $id = 0, $secondaryDataType = '') {

		$pageType = 1399668486;
		$settings = $this->getWebServiceSettings();
		$arguments = $this->computeArguments($dataType, $id, $secondaryDataType);

		$uriBuilder = $this->controllerContext->getUriBuilder();
		$uri = $uriBuilder->reset()
			->setTargetPageUid($settings['pageUid'])
			->setTargetPageType($pageType)
			->setArguments($arguments)
			->build();

		return $uri;
	}

	/**
	 * Compute the array of arguments being passed to the URI builder.
	 *
	 * @param string $dataType
	 * @param int $id
	 * @param string $secondaryDataType
	 * @return array
	 */
	protected function computeArguments($dataType, $id, $secondaryDataType) {

		$arguments['tx_webservice_pi1'] = array('dataType' => $this->mapDataType($dataType));
		if ($id > 0) {
			$arguments['tx_webservice_pi1']['identifier'] = $id;
		}
		if ($secondaryDataType) {
			$arguments['tx_webservice_pi1']['secondaryDataType'] = $this->mapDataType($secondaryDataType);
		}

		return $arguments;
	}

	/**
	 * Returns the general web service configuration.
	 *
	 * @return array
	 */
	protected function getWebServiceSettings() {
		return $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_webservice.']['settings.'];
	}

	/**
	 * Map the data type to a URL segment e.g tt_content -> content
	 *
	 * @param string $dataType
	 * @return array
	 */
	protected function mapDataType($dataType) {
		$configuration = $this->getWebServiceSettings();

		if (!empty($configuration['dataTypes.'][$dataType . '.']['mapping'])) {
			$dataType = $configuration['dataTypes.'][$dataType . '.']['mapping'];
		}
		return $dataType;
	}

}