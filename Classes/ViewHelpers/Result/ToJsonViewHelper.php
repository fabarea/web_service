<?php
namespace Fab\WebService\ViewHelpers\Result;

/*
 * This file is part of the Fab/WebService project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use Fab\WebService\Resolver\ContentResolver;
use Fab\WebService\Resolver\Settings;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * View helper for rendering a JSON response.
 */
class ToJsonViewHelper extends AbstractViewHelper
{

    /**
     * Render a Json response
     *
     * @return string
     * @throws \Fab\Vidi\Exception\NotExistingClassException
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception\InvalidVariableException
     * @throws \InvalidArgumentException
     */
    public function render()
    {
        /** @var Settings $settings */
        $settings = $this->templateVariableContainer->get('settings');
        $objects = $object = $this->templateVariableContainer->get('objects');


        // Take the first item for the one
        if ($settings->getManyOrOne() === Settings::MANY ) {
            $output = $this->getContentResolver()->getItems($objects);
        } else {
            $output = $this->getContentResolver()->getItem($object);
        }

        $this->setHttpHeaders();
        return json_encode($output);
    }

    /**
     * @return void
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception\InvalidVariableException
     * @throws \InvalidArgumentException
     */
    protected function setHttpHeaders()
    {
        /** @var \TYPO3\CMS\Extbase\Mvc\Web\Response $response */
        $response = $this->templateVariableContainer->get('response');
        $response->setHeader('Content-Type', 'application/json');
        $response->sendHeaders();
    }

    /**
     * @return ContentResolver
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception\InvalidVariableException
     * @throws \InvalidArgumentException
     */
    protected function getContentResolver()
    {

        $settings = $this->templateVariableContainer->get('settings');
        return GeneralUtility::makeInstance(ContentResolver::class, $settings);
    }

}
