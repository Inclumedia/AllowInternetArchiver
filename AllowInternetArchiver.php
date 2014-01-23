<?php
/**
 * Initialization file for the AllowInternetArchiver extension.
 * 
 * This extension allows the Internet Archiver to index the wiki.
 * 
 * @version 1.0.0 - 2014-01-21
 * 
 * @link https://www.mediawiki.org/wiki/Extension:AllowInternetArchiver Documentation
 * @link https://www.mediawiki.org/wiki/Extension_talk:AllowInternetArchiver Support
 * @link https://github.com/Inclumedia/AllowInternetArchiver Source Code
 *
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GNU General Public License 3.0 or later
 * @author Nathon Larson (Leucosticte)
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
	'author' => 'Nathan Larson',
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
