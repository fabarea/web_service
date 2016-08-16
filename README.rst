Web Service for TYPO3 CMS
=========================

This is an extension for TYPO3 CMS aiming to query data in a flexible way. Possible output format: JSON, Atom, RSS.

The URLs given as example are encoded.


	# Return all processes
	http://domain.tld/content/processes

	# Return the process with id "1"
	http://domain.tld/content/processes/1

	# Return the products related to process "1"
	http://domain.tld/content/processes/1/products

	# Configure the output format. Possible values: xml, html, json (default)
	http://domain.tld/content/process/1?format=xml


Under the hood, the URL is decoded by realURL and corresponds to something like::

	http://domain.tld/?type=1399668486&tx_webservice_pi1[dataType]=tx_domain_model_processes


The Web Service is meant for retrieving data and is read-only and will consequently not provide PUT, POST, DELETE, PATCH methods.

TODO
====

* Polish output of ATOM + HTML format. E.g https://codex.wordpress.org/index.php?title=Special:RecentChanges&feed=atom
* Resolve MM relations

Build API URL
=============

To render a URL that will be understand by the Web Service, the URI API ViewHelper can be used. The examples below assume RealURL to be installed
and configured.

::

	# Minimum arguments
	{ws:uri.api(dataType: 'pages')}

	-> http://domain.tld/content/pages

	# Complete list of arguments
	{ws:uri.api(dataType: 'pages', id: 123, secondaryDataType: 'tt_content')}

	-> http://domain.tld/content/pages/123/content



RealURL configuration
=====================

RealURL is a key component enabling to nicely encode the URLs and speak REST.

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
				'data' => array(
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
		),



Cachable request
================

To be able to cache the request make use of type = 1399668487 (experimental feature)


Use Case
========

Assuming we want to dynamically retrieve a list of options for a ``select`` element and given this list of options
is being filtered on the server side by a first select element, consider the javascript snippet below::

	// Compute the URI for the API
	// http://domain.tld/content/content
	var apiBaseUrl = "{ws:uri.api(dataType: 'pages')}";

	// Retried the URL segment mapped to the secondary data type. Will be use later.
	var mappedDataType = "{ws:map(dataType: 'tt_content')}";

	// Add listener to the first select which will filter the items of the second select
	$('#process').change(function() {
		if ($(this).val()) {


			// We build the segments of the URL URL
			// Ex: http://domain.tld/content/page/1/content
			var url = apiBaseUrl + $(this).val() + '/' + mappedDataType;

			// Remove all items of the select beforehand.
			$('.form-select').empty();

			$.ajax({
				url: url,
				success: function(json) {

					// Append new values
					$.each(json, function(i, value) {
						$('.form-select').append($('<option>').text(value.name).attr('value', value.uid));
					});
				}
			});

		}
	});

Installation
============

1. Clone this repository into typo3conf/ext/web_service or install via composer:

    $ cd /path/to/typo3conf/ext/
    $ git clone https://github.com/fabarea/web_service.git

2. Go to Extension Manager and activate the extension web_service.
3. Add a rewrite rule to your .htaccess:

    RewriteRule ^content/(.*)$ /index.php?eID=web_service&route=$1 [QSA,L]

or, if you are using Nginx:

    rewrite ^/content/(.*)$ /index.php?eID=web_service&route=$1 last;

Now you can start crawling content with ``content/``.