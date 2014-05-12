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


?>