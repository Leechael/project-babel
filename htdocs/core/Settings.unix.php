<?php
/* Project Babel
*  Author: Livid Torvalds
*  File: /htdocs/core/Settings.php
*  Usage: Settings
*  Format: 1 tab indent(4 spaces), LF, UTF-8, no-BOM
*
*  Subversion Keywords:
*
*  $Id$
*  $LastChangedDate$
*  $LastChangedRevision$
*  $LastChangedBy$
*  $URL$
*/

date_default_timezone_set('Asia/Shanghai');

if (@V2EX_BABEL != 1) {
	die('<strong>Project Babel</strong><br /><br />Made by <a href="http://www.v2ex.com/">V2EX</a> | software for internet');
}

/* on or off */
define('BABEL_ENABLED', 'yes');

/* constants for built-in cores */
define('BABEL_DB_HOSTNAME', '127.0.0.1');
define('BABEL_DB_PORT', 3306);
define('BABEL_DB_USERNAME', 'babel');
define('BABEL_DB_PASSWORD', 'ProjectBabelDatabasePassword');
define('BABEL_DB_SCHEMATA', 'babel');

define('BABEL_PREFIX', '/www/babel');

define('BABEL_LANG', 'zh_cn');

if (($_SERVER['SERVER_ADDR'] == '::1') | ($_SERVER['SERVER_ADDR'] == '127.0.0.1') | ($_SERVER['SERVER_ADDR'] == '192.168.31.150')) {
	define('BABEL_DEBUG', true);
} else {
	define('BABEL_DEBUG', true);
}

define('BABEL_AM_FROM', '"V2EX" <noreply@v2ex.com>');
define('BABEL_AM_SUPPORT', 'support@v2ex.org');
define('BABEL_AM_SIGNATURE', "\n\n\n_______________________________________________\n\nV2EX 敬上");

define('BABEL_DNS_NAME', 'www.v2ex.com');
define('BABEL_DNS_DOMAIN', 'v2ex.com');
define('BABEL_DNS_FEED', 'feed.v2ex.com');
define('BABEL_FEED_URL', 'http://www.v2ex.com/feed/v2ex.rss');

define('BABEL_PG_SPAN', 6);

define('BABEL_USR_INITIAL_MONEY', 2000);
define('BABEL_USR_ONLINE_DURATION', 600);
define('BABEL_USR_EXPENSE_PAGE', 30);

/* how many items per page */
define('BABEL_NOD_PAGE', 20);
define('BABEL_TPC_PAGE', 60);
define('BABEL_MSG_PAGE', 10);

/* max items in savepoint collection */
define('BABEL_SVP_LIMIT', 20);

/* ads hits limitations */
define('BABEL_ADS_LIMIT_HITS', 31);

/* passwd operations within 24 hours */
define('BABEL_PASSWD_LIMIT', 5);

/* theme */
define('BABEL_THEME', 'Carbon');

define('BABEL_MSG_PRICE', 5);
define('BABEL_PST_PRICE', 5);
define('BABEL_PST_SELF_PRICE', 3);
define('BABEL_TPC_PRICE', 20);
define('BABEL_TPC_UPDATE_PRICE', 5);

define('BABEL_ZEN_PROJECT_LIMIT', 20);
define('BABEL_ZEN_TASK_LIMIT', 100);

define('BABEL_PORTRAIT_EXT', 'jpg');

define('BABEL_HOME_STYLE_DEFAULT', 'shuffle');

define('BABEL_API_TOPIC_PRICE', 20);

define('BABEL_IP_DB_LOCATION', '/www/babel/res/qqwry.dat');

/* ad system powered by Google */
define('GOOGLE_AD_ENABLED', true);

/* legacy kijiji api */
define('KIJIJI_LEGACY_API_SEARCH_ENABLED', false);

/* Mint */
define('MINT_LOCATION', '');

/* dict api */
define('DICT_API_ENABLED', 'yes');

/* technorati api */
define('TN_API_ENABLED', false);
define('TN_PREFIX', 'http://v2blog.com/tproxy/tproxy.php?tag=');

/* constants for 3rdParty cores */
define('MAGPIE_CACHE_DIR', BABEL_PREFIX . '/cache/rss');
define('MAGPIE_OUTPUT_ENCODING', 'UTF-8');

/* smarty */
define('SMARTY_CACHING', false);

/* ImageMagick */
define('IM_ENABLED', true);
define('IM_CMD', '/usr/bin/mogrify');
define('IM_QUALITY', 100);

/* Hosted by who? */
define('HOST_COMPANY', 'Media Temple');
define('HOST_LINK', 'http://www.mediatemple.net/');

define('BABEL_BLOWFISH_KEY', 'ThereIsNoCureForTheWailingDeath');

/* Cache_Lite in PEAR for 360s */
$CACHE_LITE_OPTIONS_SHORT = array('cacheDir' => BABEL_PREFIX . '/cache/360/', 'lifeTime' => 360, 'memoryCaching' => true, 'automaticCleaningFactor' => 100);

$CACHE_LITE_OPTIONS_LONG = array('cacheDir' => BABEL_PREFIX . '/cache/7200/', 'lifeTime' => 7200, 'automaticCleaningFactor' => 100, 'hashedDirectoryLevel' => 3);

/* Zend_Lite in ZF for 7200s */
$ZEND_CACHE_OPTIONS_LONG_FRONTEND = array('lifeTime' => 7200, 'automaticSerialization' => false);

$ZEND_CACHE_OPTIONS_LONG_BACKEND = array('cacheDir' => BABEL_PREFIX . '/cache/7200/', 'hashedDirectoryLevel' => 2);

/* If you had memcached server */
define('ZEND_CACHE_MEMCACHED_ENABLED', 'no'); // This feature requires PHP to have memcache extension.

$ZEND_CACHE_OPTIONS_MEMCACHED = array('servers' => array(array('host' => 'localhost', 'port' => 11211, 'persistent' => true)));

/* Zend Framework */
define('ZEND_FRAMEWORK_VERSION', '0.8.0'); // Which version of Zend Framework to use? Remember to upload the "library" folder in Zend Framework distribution and rename it according to the version.

if (BABEL_DEBUG) {
	define('CDN_IMG', '/img/');
} else {
	define('CDN_IMG', '/img/'); // This is quite legacy.
}

if (BABEL_DEBUG) {
	define('CDN_UI', 'http://static.cn.v2ex.com/v2ex/0.5/');
} else {
	define('CDN_UI', 'http://static.cn.v2ex.com/v2ex/0.5/'); // If you set up your dedicated image server.
}

if (BABEL_DEBUG) {
	define('CDN_P', '/img/');
} else {
	define('CDN_P', 'http://www.v2ex.com/img/'); // If you set up your dedicated portrait server.
}
?>
