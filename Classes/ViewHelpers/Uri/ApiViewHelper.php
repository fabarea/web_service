<?php
namespace Fab\WebService\ViewHelpers\Uri;

/*
 * This file is part of the Fab/WebService project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * View Helper which returns a URI understandable by the web service.
 */
class ApiViewHelper extends AbstractViewHelper
{

    /**
     * Renders a URI understandable by the web service.
     *
     * @param string $dataType
     * @param int $id
     * @param string $secondaryDataType
     * @return string
     */
    public function render($dataType, $id = 0, $secondaryDataType = '')
    {

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
    protected function computeArguments($dataType, $id, $secondaryDataType)
    {

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