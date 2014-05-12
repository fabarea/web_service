Web Service
===========

This is an extension for TYPO3 CMS aiming to fetch data in a flexible way. Possible output format: JSON, Atom, HTML.

The Web Service is meant for retrieving data and is read-only and will consequently not provide PUT, POST, DELETE, PATCH methods.

The URLs given as example are encoded by EXT:realURL::


	# Return all processes
	http://domain/api/processes

	# Return the process with id "1"
	http://domain/api/processes/1

	# Return the products related to process "1"
	http://domain/api/processes/1/products

	# Configure the output format. Possible values
	http://domain/api/process/1?format=xml


Under the hood, the URL is decoded by realURL and corresponds to something like::

	http://domain/?type=1399668486&tx_webservice_pi1[dataType]=tx_domain_model_processes


Build API URL
=============

To render a URL that will be understand by the Web Service, the URI API ViewHelper can be used. The examples below assume RealURL to be installed
and configured.

::

	# Minimum arguments
	{ws:uri.api(dataType: 'pages')}

	-> http://domain/api/pages

	# Complete list of arguments
	{ws:uri.api(dataType: 'pages', id: 123, secondaryDataType: 'tt_content')}

	-> http://domain/api/pages/123/content


Use Case
========

Assuming we want to dynamically retrieve a list of options for a select form element. This list of options will be filtered by a first select element


RealURL configuration
=====================


::


		// Pre variables
		'preVars' => array(

			array(
				'GETvar' => 'type',
				'valueMap' => array(
					'api' => 1399668486,
				),
				'noMatch' => 'bypass',
			),

		),

		// Fixed post variables
		'fixedPostVars' => array(
			'_DEFAULT' => array(
				array(
					'type' => 'user',
					'userFunc' => 'EXT:web_service/Classes/UserFunction/RealUrl.php:Vanilla\\WebService\\UserFunction\\RealUrl->getDataType',
					'GETvar' => 'tx_webservice_pi1[dataType]',
				),
				array(
					'GETvar' => 'tx_webservice_pi1[identifier]',
					'optional' => TRUE,
				),
			),
		),



Cache Web Service
=================

To User the cache web service user type = 1399668487