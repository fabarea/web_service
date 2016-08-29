<?php
namespace Fab\WebService\ViewHelpers\Result;

/*
 * This file is part of the Fab/WebService project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use Fab\WebService\Resolver\Settings;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * View helper for rendering a CSV export request.
 */
class ToCsvViewHelper extends AbstractToFormatViewHelper
{

    /**
     * Render a CSV export request.
     *
     * @return boolean
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception\InvalidVariableException
     * @throws \InvalidArgumentException
     * @throws \Fab\Vidi\Exception\NotExistingClassException
     */
    public function render()
    {
        $items = $this->getItems();

        // Initialization step.
        $this->initializeEnvironment();
        $this->exportFileNameAndPath .= '.csv'; // add extension to the file.

        // Write the exported data to a CSV file.
        $this->writeCsvFile($items);

        $this->sendCsvHttpHeaders();
        readfile($this->exportFileNameAndPath);

        // Clean up step
        GeneralUtility::rmdir($this->temporaryDirectory, TRUE);
    }

    /**
     * Write the CSV file to a temporary location.
     *
     * @param array $items
     * @return void
     */
    protected function writeCsvFile(array $items)
    {

        // Create a file pointer
        $output = fopen($this->exportFileNameAndPath, 'w');

        /** @var Settings $settings */
        $settings = $this->templateVariableContainer->get('settings');

        // Handle CSV header, get the first object and get the list of fields.
        fputcsv($output, $settings->getFields());

        foreach ($items as $item) {

            fputcsv($output, $item);
        }

        // close file handler
        fclose($output);
    }

    /**
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function sendCsvHttpHeaders()
    {

        /** @var \TYPO3\CMS\Extbase\Mvc\Web\Response $response */
        $response = $this->templateVariableContainer->get('response');
        $response->setHeader('Content-Type', 'application/csv');
        $response->setHeader('Content-Disposition', 'attachment; filename="' . basename($this->exportFileNameAndPath) . '"');
        $response->setHeader('Content-Length', filesize($this->exportFileNameAndPath));
        $response->setHeader('Content-Description', 'File Transfer');

        $response->sendHeaders();
    }

    /**
     * @var string
     */
    protected $exportFileNameAndPath;

    /**
     * @var string
     */
    protected $temporaryDirectory;

    /**
     * Initialize some properties
     *
     * @return void
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception\InvalidVariableException
     */
    protected function initializeEnvironment()
    {
        /** @var Settings $settings */
        $settings = $this->templateVariableContainer->get('settings');

        $this->temporaryDirectory = PATH_site . 'typo3temp/' . uniqid('web_service_', true) . '/';
        GeneralUtility::mkdir($this->temporaryDirectory);

        // Compute file name and path variable
        $this->exportFileNameAndPath = $this->temporaryDirectory . $settings->getContentType() . '-' . date($GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy']);
    }

}
