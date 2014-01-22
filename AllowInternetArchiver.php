<?php
/**
 * AllowInternetArchiver extension by Leucosticte
 * URL: http://www.mediawiki.org/wiki/Extension:AllowInternetArchiver
 *
 * This program is free software. You can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version. You can also redistribute it and/or
 * modify it under the terms of the Creative Commons Attribution 3.0 license.
 *
 * This extension looks up all the wikilinks on a page that would otherwise be red and compares them
 * to a table of page titles to determine whether they exist on a remote wiki. If so, the wikilink
 * turns blue and links to the page on the remote wiki.
 */


/* Alert the user that this is not a valid entry point to MediaWiki if they try to access the
special pages file directly.*/

if ( !defined( 'MEDIAWIKI' ) ) {
	echo <<<EOT
		To install the AllowInternetArchiver extension, put the following line in LocalSettings.php:
		require( "$IP/extensions/AllowInternetArchiver/AllowInternetArchiver.php" );
EOT;
	exit( 1 );
}

$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'AllowInternetArchiver',
	'author' => 'Leucosticte',
	'url' => 'https://www.mediawiki.org/wiki/Extension:AllowInternetArchiver',
	'descriptionmsg' => 'allowinternetarchiver-desc',
	'version' => '1.0.0',
);

$wgExtensionMessagesFiles['AllowInternetArchiver'] = __DIR__ . '/AllowInternetArchiver.i18n.php';
$wgHooks['OutputPageBeforeHTML'][] = 'allowInternetArchiver';
$wgAllowInternetArchiverRan = false;

function allowInternetArchiver ( &$out, &$text ) {
	global $wgParser, $wgAllowInternetArchiverRan;
	if ( $wgAllowInternetArchiverRan ) {
		return true;
	}
	$wgAllowInternetArchiverRan = true;
	$action = $out->getPageTitleActionText();
	if(
		  $action !== 'edit'
	       && $action !== 'history'
	       && $action !== 'delete'
	       && $action !== 'watch'
	       && strpos( $wgParser->mTitle->mPrefixedText, 'Special:' ) === false
	       && $wgParser->mTitle->mNamespace !== 8
	  ) {
		$out->addMeta( 'ia_archiver', 'index, follow' );
	  }
}
