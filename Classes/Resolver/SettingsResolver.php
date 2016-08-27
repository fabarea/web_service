<?php
namespace Fab\WebService\Resolver;

/*
 * This file is part of the Fab/WebService project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class SettingsResolver
 */
class SettingsResolver
{
    /**
     * @var array
     */
    protected $mappings = [];

    /**
     * @param array $settings
     */
    public function __construct(array $settings)
    {
        if (array_key_exists('mappings', $settings) && is_array($settings['mappings'])) {
            $this->mappings = $settings['mappings'];
        }
    }

    /**
     * @param string $route
     * @return Settings
     * @throws \InvalidArgumentException
     */
    public function resolve($route)
    {

        /** @var Settings $settings */
        $settings = GeneralUtility::makeInstance(Settings::class);
        $settings->setRouteSegments(GeneralUtility::trimExplode('/', $route));

        $aliasDataType = $settings->getRouteSegments()[0];

        if (array_key_exists($aliasDataType, $this->mappings)) {
            $mappings = $this->mappings[$aliasDataType];

            if (array_key_exists('tableName', $mappings)) {
                $settings->setContentType((string)$mappings['tableName']);
            }

            if (array_key_exists('excludedFields', $mappings)) {
                $settings->setExcludedFields(GeneralUtility::trimExplode(',', $mappings['excludedFields'], true));
            }

            if (array_key_exists('orderings', $mappings) && is_array($mappings['orderings'])) {
                $settings->setOrderings($mappings['orderings']);
            }

            if (array_key_exists('limit', $mappings)) {
                $settings->setLimit((int)$mappings['limit']);
            }


            if ($settings->countRouteSegments() === 2) {
                $settings->setManyOrOne(Settings::ONE);
            } else {
                $settings->setManyOrOne(Settings::MANY);
            }

            if (array_key_exists($settings->getManyOrOne(), $mappings) && array_key_exists('fields', $mappings[$settings->getManyOrOne()])) {
                $fieldList = $mappings[$settings->getManyOrOne()]['fields'];
                $settings->setFields(GeneralUtility::trimExplode(',', $fieldList, true));
            }
        }

        return $settings;
    }

}
