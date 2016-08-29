Web Service for TYPO3 CMS
=========================

This is an extension for TYPO3 CMS aiming to query data in a flexible way. Possible output format: JSON, Atom, RSS.

The URLs given as example are encoded.


	# Return all FE user group
	http://domain.tld/content/usergroups

	# Return all FE users - authentication required
	http://domain.tld/content/users
	
	# Return latest FE users login - authentication required
	http://domain.tld/content/last-login

	# Return one FE user with id "1"
	http://domain.tld/content/users/1

	# Return the frontend group related to user "1" (no yet implemented!!)
	http://domain.tld/content/users/1/groups

Five output format are supported: atom, csv, html, json, xml. JSON is the default

	# Configure the output format - atom, csv, html, json, xml
	http://domain.tld/content/users.xml


Under the hood, the URL is decoded and corresponds to something like::

	http://domain.tld/index.php?eID=web_service&route=users/1;


The Web Service is meant for retrieving data and is read-only and will consequently (at least for now) not provide PUT, POST, DELETE, PATCH methods.


Configuration
=============

The configuration is done via TypoScript. For a new content type you must register a new key like "users" or "usergroups" as used in the URL `http://domain.tld/content/users`.


    plugin.tx_webservice {
        settings {
    
            mappings {
    
                # Required key for a new content type
                users {
    
                    # Required value!
                    tableName = fe_users
    
                    # Tell the maximum items taken by default for the list.
                    limit = 50
    
                    # Optional default filters (not yet implemented)
                    filter =
    
                    # Protect the output with a token or a user session
                    permissions {
    
                        # Possible comma separated list of Frontend User group.
                        frontendUserGroups = *
    
                        # Give a general uuid token to protect this data stream
                        #token = 3ce2b796-69cd-11e6-8b77-86f30ca893d3
                    }
    
                    # The fields being display in the list.
                    # http://domain.tld/content/users
                    many {
                        fields = uid, first_name, last_name
                    }
    
                    # The fields being display in the detail.
                    # http://domain.tld/content/users/1
                    one {
                        fields = uid, first_name, last_name, usergroup
                    }
                }
    
                last-login < .users
                last-login {
    
                    # Default ordering which will override the "default_sortby" in the TCA
                    orderings {
                        lastlogin = DESC
                    }
    
                    # Tell the maximum items taken by default for the list.
                    limit = 10
                }
    
                # Stream frontend user group information
                usergroups {
    
                    # Required value!
                    tableName = fe_groups
    
                    # In this example we take every fields of fe_groups excpect those ones
                    excludedFields = felogin_redirectPid, tx_extbase_type, TSconfig, lockToDomain, subgroup
    
                    # Default ordering which will override the "default_sortby" in the TCA
                    orderings {
                        title = ASC
                    }
    
                }
            }
    
            # Specific configuration for format "atom". In this case we really want to limit the number of items.
            atom {
                limit = 10
            }
        }
    }

Installation
============


1. Install via composer or clone the extension into /path/typo3conf/ext/. 

    $ composer require fab/web-service

2. Go to Extension Manager and activate the extension web_service.
3. Add a rewrite rule to your .htaccess:

    RewriteRule ^content/(.*)$ /index.php?eID=web_service&route=$1 [QSA,L]

or, if you are using Nginx:

    rewrite ^/content/(.*)$ /index.php?eID=web_service&route=$1 last;

Now you can start fetching content with ``content/``.