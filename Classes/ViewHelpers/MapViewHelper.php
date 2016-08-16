<?php
namespace Fab\WebService\ViewHelpers;

/*
 * This file is part of the Fab/WebService project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * View Helper which returns the data type mapped to a URL segment.
 */
class MapViewHelper extends AbstractViewHelper
{

    /**
     * Renders the data type mapped to a URL segment.
     *
     * @param string $dataType
     * @return string
     */
    public function render($dataType)
    {
        return $this->mapDataType($dataType);
    }

    /**
     * Returns the general web service configuration.
     *
     * @return array
     */
    protected function getWebServiceSettings()
    {
        return $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_webservice.']['settings.'];
    }

    /**
     * Map the data type to a URL segment e.g tt_content -> content
     *
     * @param string $dataType
     * @return array
     */
    protected function mapDataType($dataType)
    {
        $configuration = $this->getWebServiceSettings();

        if (!empty($configuration['dataTypes.'][$dataType . '.']['mapping'])) {
            $dataType = $configuration['dataTypes.'][$dataType . '.']['mapping'];
        }
        return $dataType;
    }

}