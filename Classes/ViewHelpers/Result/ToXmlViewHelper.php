<?php
namespace Fab\WebService\ViewHelpers\Result;

/*
 * This file is part of the Fab/WebService project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use TYPO3\CMS\Fluid\Core\ViewHelper\Exception\InvalidVariableException;

/**
 * View helper for rendering an XML response.
 */
class ToXmlViewHelper extends AbstractToFormatViewHelper
{

    /**
     * @return string
     * @throws \UnexpectedValueException
     * @throws \Fab\Vidi\Exception\NotExistingClassException
     * @throws InvalidVariableException
     * @throws \InvalidArgumentException
     */
    public function render()
    {
        $items = $this->getItems();
        $output = sprintf(
            '<?xml version="1.0"?>
<items>
    %s
</items>',
            $this->renderEntries($items)
        );

        $this->setHttpHeaders();
        return $output;
    }

    /**
     * @param array $items
     * @return string
     */
    protected function renderEntries(array $items)
    {

        $renderedEntries = [];
        foreach ($items as $item) {
            $renderedEntries[] = sprintf(
                '
    <item>
        %s
    </item>',
                $this->renderItem($item)
            );
        }

        return implode("\n", $renderedEntries);
    }

    /**
     * @param array $item
     * @return array
     */
    protected function renderItem(array $item)
    {

        $formattedItem = [];
        foreach ($item as $key => $value) {
            $formattedItem[] = sprintf(
                '<%s>%s<%s>',
                $key,
                $value,
                $key
            );
        }

        return implode("\n        ", $formattedItem);
    }

    /**
     * @return void
     * @throws InvalidVariableException
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
