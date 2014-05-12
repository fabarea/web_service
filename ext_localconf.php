<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

# No cachable plugin
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$_EXTKEY,
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
	$_EXTKEY,
	'Pi2',
	array(
		'WebService' => 'display',
	)
);

?>