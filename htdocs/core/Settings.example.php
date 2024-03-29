<?php
/* Project Babel
*  Author: Livid Torvalds
*  File: /htdocs/core/Settings.example.php
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

/* 
 * If you're installing on win32, here could be:
 * define('BABEL_PREFIX', 'c:/www/babel');
 *
 */
define('BABEL_PREFIX', '/www/babel');

/*
 * UI: default language
 * 
 * Choose from:
 *
 * de_de
 * en_us
 * zh_cn
 *
 */
define('BABEL_LANG_DEFAULT', 'en_us');

define('BABEL_DEBUG', true);

define('BABEL_AM_SENDER', 'smtp');
define('BABEL_AM_FROM', '"Project Babel" <noreply@yourdomain.tld>');
define('BABEL_AM_SUPPORT', 'support@yourdomain.tld');
define('BABEL_AM_SIGNATURE', "\n\n\n_______________________________________________\n\nProject Babel");

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
define('BABEL_ING_PAGE', 10);

/* max items in savepoint collection */
define('BABEL_SVP_LIMIT', 20);

/* max items in PIX */
define('BABEL_PIX_UPLOAD_LIMIT', 20);

/* ads hits limitations */
define('BABEL_ADS_LIMIT_HITS', 31);

/* passwd operations within 24 hours */
define('BABEL_PASSWD_LIMIT', 5);

/* theme */
define('BABEL_THEME', 'Uranium');

define('BABEL_MSG_PRICE', 5);
define('BABEL_PST_PRICE', 5);
define('BABEL_PST_SELF_PRICE', 3);
define('BABEL_TPC_PRICE', 20);
define('BABEL_TPC_UPDATE_PRICE', 5);

define('BABEL_VISITING_AWARDING', false); // This is too evil.

define('BABEL_SECURITY_RATE', 1.1);

define('BABEL_ZEN_PROJECT_LIMIT', 20);
define('BABEL_ZEN_TASK_LIMIT', 100);

define('BABEL_PORTRAIT_EXT', 'jpg');

define('BABEL_HOME_STYLE_DEFAULT', 'shuffle');

define('BABEL_API_TOPIC_PRICE', 20);

define('BABEL_IP_DB_LOCATION', '/www/babel/res/qqwry.dat');

/* Google AdSense */
define('GOOGLE_AD_ENABLED', true);

/* Alimama */
define('ALIMAMA_ENABLED', true);

/* legacy kijiji api */
define('KIJIJI_LEGACY_API_SEARCH_ENABLED', false);

/* Mint installation location */
define('MINT_LOCATION', '');

/* MyBlogLog ID */
define('MBL_ID', '');

/* "x" in Pheedo's code */
define('PHEEDO_X', '');

/* Flickr API key */
define('FLICKR_API_KEY', '');

/* dict api */
define('DICT_API_ENABLED', 'no');

/* technorati api */
define('TN_API_ENABLED', false);
define('TN_PREFIX', 'http://v2blog.com/tproxy/tproxy.php?tag=');

/* constants for 3rdParty cores */
define('MAGPIE_CACHE_DIR', BABEL_PREFIX . '/cache/rss');
define('MAGPIE_OUTPUT_ENCODING', 'UTF-8');

/* Nexus */
define('BABEL_WEBLOG_SITE', 'b-dev.v2ex.com');
define('BABEL_WEBLOG_SITE_STATIC', 'v-dev.v2ex.com');
define('BABEL_WEBLOG_PREFIX', '/www/weblog');
define('BABEL_WEBLOG_WWWROOT', 'htdocs');
define('BABEL_WEBLOG_BUILD_INTERVAL', 10);

/* smarty */
define('SMARTY_CACHING', false);

/* ImageMagick */
define('IM_ENABLED', false);
define('IM_CMD', '/usr/bin/mogrify');
define('IM_QUALITY', 100);

/* Hosted by who? */
define('HOST_COMPANY', '(mt) Media Temple');
define('HOST_LINK', 'http://www.mediatemple.net/');

define('BABEL_BLOWFISH_KEY', 'SoFellAutomnRain');

/* How much data could a SimpleStorage piece holds? */
define('SIMPLESTORAGE_MAX', 1048576);

/* Short term cache settings (Cache_Lite in PEAR) lasting for 360 seconds */
$CACHE_LITE_OPTIONS_SHORT = array('cacheDir' => BABEL_PREFIX . '/cache/360/', 'lifeTime' => 360, 'memoryCaching' => true, 'automaticCleaningFactor' => 100);

/* Long term cache settings (Cache_Lite in PEAR) lasting for 7200 seconds */
$CACHE_LITE_OPTIONS_LONG = array('cacheDir' => BABEL_PREFIX . '/cache/7200/', 'lifeTime' => 7200, 'automaticCleaningFactor' => 100, 'hashedDirectoryLevel' => 3);

/* Which type of tiny cache to use. */
define('ZEND_CACHE_TYPE_TINY', 'Sqlite');

/**
 *
 * Tiny cache settings (Zend_Cache in Zend Framework) lasting for 120 seconds.
 * It consists two parts:
 * Frontend & Backend
 *
 */
$ZEND_CACHE_OPTIONS_TINY_FRONTEND = array('lifeTime' => 120, 'automaticSerialization' => false);

/**
 *
 * Tiny cache: Backends
 *
 */
$ZEND_CACHE_OPTIONS_TINY_BACKEND = array();
$ZEND_CACHE_OPTIONS_TINY_BACKEND['Sqlite'] = array('cacheDBCompletePath' => BABEL_PREFIX . '/cache/120.db');
$ZEND_CACHE_OPTIONS_TINY_BACKEND['File'] = array('cacheDir' => BABEL_PREFIX . '/cache/120/', 'hashedDirectoryLevel' => 3);

/* Which type of long term cache to use. */
define('ZEND_CACHE_TYPE_LONG', 'File');

/**
 *
 * Long term cache settings (Zend_Cache in Zend Framework) lasting for 7200 seconds.
 * It consists two parts:
 * Frontend & Backend
 *
 */
$ZEND_CACHE_OPTIONS_LONG_FRONTEND = array('lifeTime' => 7200, 'automaticSerialization' => false);

/**
 *
 * Long term cache: Backends
 *
 */
$ZEND_CACHE_OPTIONS_LONG_BACKEND = array();
$ZEND_CACHE_OPTIONS_LONG_BACKEND['Sqlite'] = array('cacheDBCompletePath' => BABEL_PREFIX . '/cache/7200.db');
$ZEND_CACHE_OPTIONS_LONG_BACKEND['File'] = array('cacheDir' => BABEL_PREFIX . '/cache/7200/', 'hashedDirectoryLevel' => 2);

/* If you have memcached server(s). */
define('ZEND_CACHE_MEMCACHED_ENABLED', 'no'); // This feature requires PHP to have memcache extension.

define('ZEND_CACHE_OPTIONS_MEMCACHED_SERVER', '127.0.0.1');

define('ZEND_CACHE_OPTIONS_MEMCACHED_PORT', 11211);

$ZEND_CACHE_OPTIONS_MEMCACHED = array('servers' => array(array('host' => ZEND_CACHE_OPTIONS_MEMCACHED_SERVER, 'port' => ZEND_CACHE_OPTIONS_MEMCACHED_PORT, 'persistent' => true)));

/* Zend Framework */
define('ZEND_FRAMEWORK_VERSION', '1.0.2'); // Which version of Zend Framework to use?

define('CDN_IMG', '/img/');
define('CDN_UI', '/');
define('CDN_P', '/img/');

define('MIDGARD_BOT', 'v2ex.ing@gmail.com');
?>