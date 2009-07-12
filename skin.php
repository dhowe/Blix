<?php if (!defined('PmWiki')) exit();
/* PmWiki Blix skin
 *
 * Examples at: http://pmwiki.com/Cookbook/Blix and http://solidgone.com/Skins/
 * Copyright (c) 2009 David Gilbert
 * Dual licensed under the MIT and GPL licenses:
 *    http://www.opensource.org/licenses/mit-license.php
 *    http://www.gnu.org/licenses/gpl.html
 */
global $FmtPV;
$FmtPV['$SkinName'] = '"Blix"';
$FmtPV['$SkinVersion'] = '"2.0.0"';

# Create a nosearch markup, since one doesn't exist
Markup('nosearch', 'directives',  '/\\(:nosearch:\\)/ei', "SetTmplDisplay('PageSearchFmt',0)");

global $Blix_Width, $Blix_TitleBg, $Blix_TitleColor, $SkinDirUrl, $HTMLStylesFmt;
if (!empty($Blix_TitleBg))
	$HTMLStylesFmt['blix'] .= '#header {background-image:url(' .$SkinDirUrl . '/images/backgrounds/' . $Blix_TitleBg .'); '
		.(!empty($Blix_TitleColor) ?' background-color:'.$Blix_TitleColor.';' :'') .'}';
if (!empty($Blix_Width))
	$HTMLStylesFmt['blix'] .= '#container, #credits {max-width:' .$Blix_Width .';}';

# ----------------------------------------
# - Standard Skin Setup
# ----------------------------------------
global $PageLogoUrl, $PageLogoUrlHeight, $PageLogoUrlWidth;
if (!empty($PageLogoUrl)) {
	if (!isset($PageLogoUrlWidth) || !isset($PageLogoUrlHeight)) {
		$size = getimagesize($PageLogoUrl);
		SDV($PageLogoUrlWidth, ($size ?$size[0]+15 :0) .'px');
		SDV($PageLogoUrlHeight, ($size ?$size[1] :0) .'px');
	}
	$HTMLStylesFmt['blix'] .= '#header .sitetitle a{height:' .$PageLogoUrlHeight .'; background: url(' .$PageLogoUrl .') left top no-repeat} '.
		'#header .sitetitle a, #header .sitetag{padding-left: ' .$PageLogoUrlWidth .'} '.
		'#header .sitetag{margin-top: ' .(30-substr($PageLogoUrlHeight,0,-2)) .'px}';
}

$FmtPV['$WikiTitle'] = '$GLOBALS["WikiTitle"]';
$FmtPV['$WikiTag'] = '$GLOBALS["WikiTag"]';

# Define a link style for new page links
global $LinkPageCreateFmt;
SDV($LinkPageCreateFmt, "<a class='createlinktext' href='\$PageUrl?action=edit'>\$LinkText</a>");

# Default color scheme
global $SkinColor, $ValidSkinColors;
if ( !is_array($ValidSkinColors) ) $ValidSkinColors = array();
array_push($ValidSkinColors, 'spring', 'autumn');
if ( isset($_GET['color']) && in_array($_GET['color'], $ValidSkinColors) ) {
	$SkinColor = $_GET['color'];
} elseif ( !in_array($SkinColor, $ValidSkinColors) ) {
	$SkinColor = 'spring';
}

# Override pmwiki styles otherwise they will override styles declared in css
global $HTMLStylesFmt;
$HTMLStylesFmt['pmwiki'] = '';

# Add a custom page storage location
global $WikiLibDirs;
$PageStorePath = dirname(__FILE__)."/wikilib.d/{\$FullName}";
$where = count($WikiLibDirs);
if ($where>1) $where--;
array_splice($WikiLibDirs, $where, 0, array(new PageStore($PageStorePath)));
