# Search and replace text on an entire mysql database #

This class written in php, search a text massively on an entire mysql database and replace it by other one.

The usage is very simple:

	// Create an instace of the class
	$mysqlSearchAndReplace = new mysqlSearchAndReplace("myDatabase", "localhost", "root", "");

	// Simple search and replace
	$mysqlSearchAndReplace->searchAndReplace("old text", "new text");

	// Case sensitive = true
	$mysqlSearchAndReplace->searchAndReplace("Old Text", "New text", true);

	// We specify the tables where search
	$mysqlSearchAndReplace->searchAndReplace("old text", "new text", true, array('tableOne', 'tableTwo'));



## Autor ##

* Jon Segador <jonseg@gmail.com>
* Twitter : *[@jonseg](http://twitter.com/#!/jonseg)*
* Linkedin: *[http://es.linkedin.com/pub/jon-segador/11/685/602](http://es.linkedin.com/pub/jon-segador/11/685/602)*
* Blog    : *[http://jonsegador.com/](http://jonsegador.com/)*


