<?php
namespace Fab\WebService\ViewHelpers\Result;

/*
 * This file is part of the Fab/WebService project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use Fab\Vidi\Tca\Tca;
use Fab\WebService\Resolver\Settings;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * View helper for rendering an ATOM response.
 */
class ToAtomViewHelper extends AbstractToFormatViewHelper
{

    /**
     * @return string
     * @throws \UnexpectedValueException
     * @throws \Fab\Vidi\Exception\NotExistingClassException
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception\InvalidVariableException
     * @throws \InvalidArgumentException
     */
    public function render()
    {
        /** @var Settings $settings */
        $settings = $this->templateVariableContainer->get('settings');
        $entries = $this->getItems();
        $contentType = md5($settings->getContentType());
        $output = sprintf(
            '<?xml version="1.0"?>
<feed xmlns="http://www.w3.org/2005/Atom" xml:lang="en">

    <title>%s</title>
    <link href="%s"/>
    <id>urn:uuid:60a76c80-d399-11d9-b91C-%s</id>
    <updated>%s</updated>
    %s
</feed>',
            Tca::table($settings->getContentType())->getLabel(),
            GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'),
            substr($contentType, -11),
            date('c'),
            $this->renderEntries($entries)
        );

        $this->setHttpHeaders();
        return $output;
    }

    /**
     * @param array $entries
     * @return string
     */
    protected function renderEntries(array $entries)
    {

        $renderedEntries = [];
        foreach ($entries as $entry) {
            $renderedEntries[] = sprintf(
                '
    <entry xmlns="http://www.w3.org/2005/Atom">
        %s
    </entry>',
                $this->renderEntry($entry)
            );
        }

        return implode("\n", $renderedEntries);
    }

    /**
     * @param array $entry
     * @return array
     */
    protected function renderEntry(array $entry)
    {

        $formattedEntry = [];
        foreach ($entry as $key => $item) {
            $formattedEntry[] = sprintf(
                '<%s>%s<%s>',
                $key,
                $item,
                $key
            );
        }

        return implode("\n        ", $formattedEntry);
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
        $response->setHeader('Content-Type', 'application/rss+xml');
        $response->sendHeaders();
    }

}
