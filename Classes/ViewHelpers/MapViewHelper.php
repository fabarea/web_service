<?php
namespace Vanilla\WebService\ViewHelpers;

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
 * View Helper which returns the data type mapped to a URL segment.
 */
class MapViewHelper extends AbstractViewHelper {

	/**
	 * Renders the data type mapped to a URL segment.
	 *
	 * @param string $dataType
	 * @return string
	 */
	public function render($dataType) {
		return $this->mapDataType($dataType);
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