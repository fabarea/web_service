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

/**
 * View helper for rendering an HTML response.
 */
class ToHtmlViewHelper extends AbstractToFormatViewHelper
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
        $output = sprintf(
            '<!DOCTYPE html>
<html>
<head>
	<meta charset=utf-8/>
	<title>%s</title>

	<style>
		.container {
			display: table;
		}

		.row {
			display: table-row;
		}

		.column {
			display: table-cell;
		}
	</style>
</head>
<body>
<div class="container">
	<div class="row">
		%s
	</div>
	%s
</div>
</body>
</html>',
            Tca::table($settings->getContentType())->getLabel(),
            $this->renderHeader(),
            $this->renderEntries($entries)
        );

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
		<div class="row">
			%s
		</div>',
                $this->renderEntry($entry)
            );
        }

        return implode("\n", $renderedEntries);
    }

    /**
     * @param array $entry
     * @return string
     */
    protected function renderEntry(array $entry)
    {

        $formattedEntry = [];
        foreach ($entry as $key => $item) {
            $formattedEntry[] = sprintf(
                '<div class="column %s">%s</div>',
                $key,
                $item
            );
        }

        return implode("\n        ", $formattedEntry);
    }

    /**
     * @return array
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception\InvalidVariableException
     */
    protected function renderHeader()
    {
        /** @var Settings $settings */
        $settings = $this->templateVariableContainer->get('settings');

        $formattedEntry = [];
        foreach ($settings->getFields() as $field) {
            $formattedEntry[] = sprintf(
                '<div class="column %s">%s</div>',
                $field,
                $field
            );
        }

        return implode("\n        ", $formattedEntry);
    }

}
