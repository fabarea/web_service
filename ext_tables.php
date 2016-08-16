<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

if (TYPO3_MODE === 'BE') {

    /**
     * Registers a Backend Module
     */
//	Tx_Extbase_Utility_Extension::registerModule(
//		$_EXTKEY,
//		'user',	 // Make module a submodule of 'user'
//		'm1',	// Submodule key
//		'',						// Position
//		array(
//			'Backend' => 'index, sendMessage, sendMessageTest',
//			'ListManager' => 'list, save',
//			'MessageTemplate' => 'list, save',
//		),
//		array(
//			'access' => 'user,group',
//			'icon'   => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
//			'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_m1.xlf',
//		)
//	);

}

// Possible Static TS loading
$configuration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['web_service']);
if (true === isset($configuration['autoload_typoscript']['value']) && true === (bool)$configuration['autoload_typoscript']['value']) {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('web_service', 'Configuration/TypoScript', 'Web Service: expose content in various formats');
}