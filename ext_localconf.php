<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

# No cachable plugin
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'web_service',
    'Pi1',
    array(
        'WebService' => 'display',
    ),
    // non-cacheable actions
    array(
        'WebService' => 'display',
    )
);

# Cachable plugin
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'web_service',
    'Pi2',
    array(
        'WebService' => 'display',
    )
);

// Define whether to automatically load TS.
$configuration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['web_service']);
if (false === isset($configuration['autoload_typoscript']) || true === (bool)$configuration['autoload_typoscript']) {

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript(
        'web_service',
        'constants',
        '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:web_service/Configuration/TypoScript/constants.txt">'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript(
        'web_service',
        'setup',
        '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:web_service/Configuration/TypoScript/setup.txt">'
    );
}
