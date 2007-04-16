<?php
/* Project Babel
 *
 * Author: Livid Liu <v2ex.livid@mac.com>
 * File: /htdocs/core/InstallCore.php
 * Usage: a Quick and Dirty script for fast installation
 * Format: 1 tab indent(4 spaces), LF, UTF-8, no-BOM
 *
 * Subversion Keywords:
 *
 * $Id$
 * $LastChangedDate$
 * $LastChangedRevision$
 * $LastChangedBy$
 * $URL$
 *
 * Copyright (C) 2006 Livid Liu <v2ex.livid@mac.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software Foundation,
 * Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

define('V2EX_BABEL', 1);
require('Settings.php');

class Install {
	var $db;
	
	public function __construct() {
		$this->db = mysql_connect(BABEL_DB_HOSTNAME, BABEL_DB_USERNAME, BABEL_DB_PASSWORD);
		mysql_select_db(BABEL_DB_SCHEMATA, $this->db);
		mysql_query("SET NAMES utf8", $this->db);
		mysql_query("SET CHARACTER SET utf8", $this->db);
		mysql_query("SET COLLATION_CONNECTION='utf8_general_ci'", $this->db);
		header('Content-type: text/html;charset=UTF-8');
		echo('Install Core init<br /><br />');
	}
	
	public function __destruct() {
		mysql_close($this->db);
	}
	
	public function vxSetupWeightV2EX() {
		mysql_unbuffered_query("UPDATE babel_node SET nod_weight = 100 WHERE nod_name = 'limbo'");
		mysql_unbuffered_query("UPDATE babel_node SET nod_weight = 1000 WHERE nod_name = 'mechanus'");
		mysql_unbuffered_query("UPDATE babel_node SET nod_weight = 10000 WHERE nod_name = 'thegraywaste'");
		mysql_unbuffered_query("UPDATE babel_node SET nod_weight = 10 WHERE nod_name = 'sigil'");
		mysql_unbuffered_query("UPDATE babel_node SET nod_weight = 1 WHERE nod_name = 'elysium'");
		mysql_unbuffered_query("UPDATE babel_node SET nod_weight = 500 WHERE nod_name = 'theriveroceanus'");
	}
	
	public function vxSetupSections() {
		$this->vxSetupSection("UPDATE babel_node SET nod_sid = 1, nod_level = 0, nod_title = '异域', nod_header = '异域', nod_footer = '' WHERE nod_id = 1 LIMIT 1");
		$this->vxSetupSection("UPDATE babel_node SET nod_sid = 1, nod_title = '混沌海', nod_header = '', nod_footer = '' WHERE nod_id = 2 LIMIT 1");
		$this->vxSetupSection("UPDATE babel_node SET nod_sid = 1, nod_title = '机械境', nod_header = '', nod_footer = '' WHERE nod_id = 3 LIMIT 1");
		$this->vxSetupSection("UPDATE babel_node SET nod_sid = 1, nod_title = '灰色荒野', nod_header = '', nod_footer = '' WHERE nod_id = 4 LIMIT 1");
	}
	
	public function vxSetupSectionExtra($name, $title, $description = '', $header = '', $footer = '') {
		$sql = "SELECT nod_id FROM babel_node WHERE nod_name = '{$name}' LIMIT 1";
		$rs = mysql_query($sql);
		if (mysql_num_rows($rs) == 1) {
			$_t = time();
			$sql = "UPDATE babel_node SET nod_title = '{$title}', nod_description = '{$description}', nod_header = '{$header}', nod_footer = '{$footer}', nod_lastupdated = {$_t} WHERE nod_name = '{$name}' LIMIT";
			mysql_query($sql, $this->db);
			if (mysql_affected_rows($this->db) == 1) {
				echo ('OK: ' . $sql . '<br />');
				return true;
			} else {
				echo('NU: ' . $sql . '<br />');
				return false;
			}
		} else {
			$_t = time();
			$sql = "INSERT INTO babel_node(nod_pid, nod_uid, nod_sid, nod_level, nod_name, nod_title, nod_description, nod_header, nod_footer, nod_created, nod_lastupdated) VALUES(1, 1, 5, 1, '{$name}', '{$title}', '{$description}', '{$header}', '{$footer}', {$_t}, {$_t})";
			mysql_query($sql, $this->db);
			if (mysql_affected_rows($this->db) == 1) {
				echo ('OK: ' . $sql . '<br />');
				return true;
			} else {
				echo ('NU: ' . $sql . '<br />');
				return false;
			}
		}
	}
	
	public function vxSetupSection($stmt) {
		$sql = $stmt;
		mysql_query($sql);
		if (mysql_affected_rows() == 1) {
			echo 'OK: ' . $sql . '<br />';
		} else {
			echo 'NU ' . mysql_affected_rows() . ': ' . $sql . '<br />';
		}
	}
	
	public function vxSetupChannelById($board_id, $url) {
		$url = mysql_real_escape_string($url);
		$t = time();
		$sql = "INSERT INTO babel_channel(chl_pid, chl_url, chl_created) VALUES({$board_id}, '{$url}', {$t})";
		$sql_exist = "SELECT chl_id FROM babel_channel WHERE chl_url = '{$url}' AND chl_pid = {$board_id}";
		$rs = mysql_query($sql_exist);
		if (mysql_num_rows($rs) == 0) {
			mysql_query($sql) or die(mysql_error());
			if (mysql_affected_rows() == 1) {
				echo('OK: ' . $sql . '<br />');
			} else {
				echo('FD: ' . $sql . '<br />');
			}
		} else {
			echo('EX: ' . $sql . '<br />');
		}
	}
	
	public function vxSetupChannelByName($board_name, $url) {
		$url = mysql_real_escape_string($url);
		$t = time();
		$sql = "SELECT nod_id FROM babel_node WHERE nod_name = '{$board_name}' LIMIT 1";
		$board_id = mysql_result(mysql_query($sql), 0, 0);
		$sql = "INSERT INTO babel_channel(chl_pid, chl_url, chl_created) VALUES({$board_id}, '{$url}', {$t})";
		$sql_exist = "SELECT chl_id FROM babel_channel WHERE chl_url = '{$url}' AND chl_pid = {$board_id}";
		$rs = mysql_query($sql_exist);
		if (mysql_num_rows($rs) == 0) {
			mysql_query($sql) or die(mysql_error());
			if (mysql_affected_rows() == 1) {
				echo('OK: ' . $sql . '<br />');
			} else {
				echo('FD: ' . $sql . '<br />');
			}
		} else {
			echo('EX: ' . $sql . '<br />');
		}
	}
	
	public function vxSetupRelatedByName($board_name, $url, $title) {
		$url = mysql_real_escape_string($url);
		$title = mysql_real_escape_string($title);
		$_t = time();
		$sql = "SELECT nod_id FROM babel_node WHERE nod_name = '{$board_name}' LIMIT 1";
		$board_id = mysql_result(mysql_query($sql), 0, 0);
		$sql = "INSERT INTO babel_related(rlt_pid, rlt_url, rlt_title, rlt_created) VALUES({$board_id}, '{$url}', '{$title}', {$_t})";
		$sql_exist = "SELECT rlt_id FROM babel_related WHERE rlt_url = '{$url}' AND rlt_pid = {$board_id}";
		$rs = mysql_query($sql_exist);
		if (mysql_num_rows($rs) == 0) {
			mysql_query($sql) or die(mysql_error());
			if (mysql_affected_rows() == 1) {
				echo('OK: ' . $sql . '<br />');
			} else {
				echo('FD: ' . $sql . '<br />');
			}
		} else {
			echo('EX: ' . $sql . '<br />');
		}
	}
	
	public function vxSetupKijijiChannels() {
		$cities = array('beijing','shanghai','guangzhou','changchun','chengdu','chongqing','dalian','guiyang','hangzhou','harbin','hefei','jinan','kunming','lanzhou','nanchang','nanjing','qingdao','shantou','shenyang','shenzhen','shijiazhuang','suzhou','taiyuan','tianjin','wuhan','xiamen','xian','yantai','zhengzhou');
		$ids = array(401, 4078, 4014, 4072, 4058, 4041, 4088, 4082);
		
		foreach ($cities as $city) {
			$sql = "SELECT nod_id FROM babel_node WHERE nod_name = '{$city}'";
			$rs = mysql_query($sql);
			if (mysql_num_rows($rs) == 1) {
				$Node = mysql_fetch_object($rs);
				mysql_free_result($rs);
				foreach ($ids as $cid) {
					$url = 'http://' . $city . '.kijiji.com.cn/f-SearchAdRss?RssFeedType=rss_2.0&CatId=' . $cid;
					$this->vxSetupChannel($Node->nod_id, $url);
				}
				$Node = null;
			} else {
				mysql_free_result($rs);
			}
		}
	}
	
	public function vxSetupBoard($board_name, $board_title, $board_pid, $board_sid, $board_uid, $board_level, $board_header = '', $board_footer = '', $board_description = '') {
		$board_name = mysql_real_escape_string($board_name);
		$board_title = mysql_real_escape_string($board_title);
		$board_header = mysql_real_escape_string($board_header);
		$board_footer = mysql_real_escape_string($board_footer);
		$board_description = mysql_real_escape_string($board_description);
		$board_created = time();
		$board_lastupdated = time();
		
		$sql = "INSERT INTO babel_node(nod_name, nod_title, nod_pid, nod_sid, nod_uid, nod_level, nod_header, nod_footer, nod_description, nod_created, nod_lastupdated) VALUES('{$board_name}', '{$board_title}', {$board_pid}, {$board_sid}, {$board_uid}, {$board_level}, '{$board_header}', '{$board_footer}', '{$board_description}', {$board_created}, {$board_lastupdated})";
		$sql_exist = "SELECT nod_id FROM babel_node WHERE nod_name = '{$board_name}'";
		$rs = mysql_query($sql_exist);
		if (mysql_num_rows($rs) > 0) {
			$Node = mysql_fetch_object($rs);
			mysql_free_result($rs);
			$sql_update = "UPDATE babel_node SET nod_title = '{$board_title}', nod_pid = {$board_pid}, nod_sid = {$board_sid}, nod_uid = {$board_uid}, nod_level = {$board_level}, nod_header = '{$board_header}', nod_footer = '{$board_footer}', nod_description = '{$board_description}' WHERE nod_id = {$Node->nod_id}";
			mysql_query($sql_update);
			if (mysql_affected_rows() == 1) {
				echo 'UD: ' . $sql_update . '<br />';
			} else {
				echo 'EX: ' . $sql_update . '<br />';
			}
		} else {
			mysql_query($sql) or die(mysql_error());
			if (mysql_affected_rows() == 1) {
				echo 'OK: ' . $sql . '<br />';
			} else {
				echo 'FD: ' . $sql . '<br />';
			}
		}
	}
}

$i = new Install();
$i->vxSetupWeightV2EX();

// The River Oceanus
//$i->vxSetupSectionExtra('theriveroceanus', '海神之河');
$i->vxSetupBoard('stock', '股票', 452, 452, 1, 2, '', '股票 | 证券 | Stock');
	$i->vxSetupRelatedByName('stock', 'http://www.stockstar.com/', '证券之星');
	$i->vxSetupRelatedByName('stock', 'http://www.gf.com.cn/', '广发证券');
	$i->vxSetupRelatedByName('stock', 'http://www.jrj.com/', '金融界');
	$i->vxSetupRelatedByName('stock', 'http://www.hexun.com/', '和讯');
$i->vxSetupBoard('stockindexfutures', '股指期货', 452, 452, 1, 2, '', '股指期货 | Stock Index Futures');
$i->vxSetupBoard('nyse', 'New York Stock Exchange', 452, 452, 1, 2, '', '');
$i->vxSetupBoard('amex', 'American Stock and Options Exchange', 452, 452, 1, 2, '', '');
$i->vxSetupBoard('nasdaq', 'NASDAQ', 452, 452, 1, 2, '', '');
$i->vxSetupBoard('forex', '外汇', 452, 452, 1, 2, '', '外汇 | Forex');
	$i->vxSetupRelatedByName('forex', 'http://www.forex.com/', 'FOREX');
$i->vxSetupBoard('futures', '期货', 452, 452, 1, 2, '', '期货 | Futures');
$i->vxSetupBoard('option', '期权', 452, 452, 1, 2, '', '期权 | Option');
$i->vxSetupBoard('bond', '债券', 452, 452, 1, 2, '', '债券 | Bond');
$i->vxSetupBoard('mutualfunds', '开放式基金', 452, 452, 1, 2, '', '开放式基金 | 共同基金 | Mutual Funds');
	$i->vxSetupRelatedByName('mutualfunds', 'http://www.caibangzi.com/', '财帮子');
$i->vxSetupBoard('capitalism', '資本主義', 452, 452, 1, 2, '', '');
$i->vxSetupBoard('money', '投资与理财', 452, 452, 1, 2, '', '');
	$i->vxSetupChannelByName('money', 'http://www.money-courier.com/index.rdf');
	$i->vxSetupChannelByName('money', 'http://www.ftchinese.com/sc/rss2_full.jsp');
	$i->vxSetupRelatedByName('money', 'http://www.cmbchina.com/', '中国招商银行');
	$i->vxSetupRelatedByName('money', 'http://www.cmbc.com.cn/', '中国民生银行');
	$i->vxSetupRelatedByName('money', 'http://www.icbc.com.cn/', '中国工商银行');
	$i->vxSetupRelatedByName('money', 'http://www.ccb.com.cn/', '中国建设银行');
	$i->vxSetupRelatedByName('money', 'http://www.bank-of-china.com/', '中国银行');
$i->vxSetupBoard('vc', '风险投资', 452, 452, 1, 2, '', '');
$i->vxSetupBoard('lottery', '彩票', 452, 452, 1, 2, '', '');
$i->vxSetupBoard('jobs', '我要找份好工作', 452, 452, 1, 2, '', '');
$i->vxSetupBoard('sales', '销售一切', 452, 452, 1, 2, '', '');
$i->vxSetupBoard('exchange', '以物换物', 452, 452, 1, 2, '', '');
$i->vxSetupBoard('knack', '诀窍', 452, 452, 1, 2, '或许是小聪明，或许是大智慧', '');

$i->vxSetupBoard('sh000001', '上证指数', 452, 452, 1, 2, '上证综合指数 (000001)', '');
$i->vxSetupBoard('399001', '深圳成指', 452, 452, 1, 2, '深圳成分指数 (399001)', '');

$i->vxSetupBoard('600000', '浦发银行', 452, 452, 1, 2, '浦发银行 (600000)', '');
$i->vxSetupBoard('600001', '邯郸钢铁', 452, 452, 1, 2, '邯郸钢铁 (600001)', '');
$i->vxSetupBoard('600002', '齐鲁石化', 452, 452, 1, 2, '齐鲁石化 (600002)', '');
$i->vxSetupBoard('600003', '东北高速', 452, 452, 1, 2, '东北高速 (600003)', '');
$i->vxSetupBoard('600004', '白云机场', 452, 452, 1, 2, '白云机场 (600004)', '');
$i->vxSetupBoard('600005', '武钢股份', 452, 452, 1, 2, '武钢股份 (600005)', '');
$i->vxSetupBoard('600006', '东风汽车', 452, 452, 1, 2, '东风汽车 (600006)', '');
$i->vxSetupBoard('600007', '中国国贸', 452, 452, 1, 2, '中国国贸 (600007)', '');
$i->vxSetupBoard('600008', '首创股份', 452, 452, 1, 2, '首创股份 (600008)', '');
$i->vxSetupBoard('600009', '上海机场', 452, 452, 1, 2, '上海机场 (600009)', '');
$i->vxSetupBoard('600010', '包钢股份', 452, 452, 1, 2, '包钢股份 (600010)', '');
$i->vxSetupBoard('600011', '华能国际', 452, 452, 1, 2, '华能国际 (600011)', '');
$i->vxSetupBoard('600012', '皖通高速', 452, 452, 1, 2, '皖通高速 (600012)', '');
$i->vxSetupBoard('600015', '华夏银行', 452, 452, 1, 2, '华夏银行 (600015)', '');
$i->vxSetupBoard('600016', '民生银行', 452, 452, 1, 2, '民生银行 (600016)', '');
$i->vxSetupBoard('600017', '日照港', 452, 452, 1, 2, '日照港 (600017)', '');
$i->vxSetupBoard('600018', '上港集团', 452, 452, 1, 2, '上港集团 (600018)', '');
$i->vxSetupBoard('600019', '宝钢股份', 452, 452, 1, 2, '宝钢股份 (600019)', '');
$i->vxSetupBoard('600020', '中原高速', 452, 452, 1, 2, '中原高速 (600020)', '');
$i->vxSetupBoard('600021', '上海电力', 452, 452, 1, 2, '上海电力 (600021)', '');
$i->vxSetupBoard('600022', '济南钢铁', 452, 452, 1, 2, '济南钢铁 (600022)', '');
$i->vxSetupBoard('600026', '中海发展', 452, 452, 1, 2, '中海发展 (600026)', '');
$i->vxSetupBoard('600027', '华电国际', 452, 452, 1, 2, '华电国际 (600027)', '');
$i->vxSetupBoard('600028', '中国石化', 452, 452, 1, 2, '中国石化 (600028)', '');
$i->vxSetupBoard('600029', 'S南航', 452, 452, 1, 2, 'S南航 (600029)', '南方航空');
$i->vxSetupBoard('600030', '中信证券', 452, 452, 1, 2, '中信证券 (600030)', '');
$i->vxSetupBoard('600031', '三一重工', 452, 452, 1, 2, '三一重工 (600031)', '');
$i->vxSetupBoard('600033', '福建高速', 452, 452, 1, 2, '福建高速 (600033)', '');
$i->vxSetupBoard('600035', '楚天高速', 452, 452, 1, 2, '楚天高速 (600035)', '');
$i->vxSetupBoard('600036', '招商银行', 452, 452, 1, 2, '招商银行 (600036)', '');
$i->vxSetupBoard('600037', '歌华有线', 452, 452, 1, 2, '歌华有线 (600037)', '');
$i->vxSetupBoard('600038', '哈飞股份', 452, 452, 1, 2, '哈飞股份 (600038)', '');
$i->vxSetupBoard('600039', '四川路桥', 452, 452, 1, 2, '四川路桥 (600039)', '');
$i->vxSetupBoard('600048', '保利地产', 452, 452, 1, 2, '保利地产 (600048)', '');
$i->vxSetupBoard('600050', '中国联通', 452, 452, 1, 2, '中国联通 (600050)', '');
$i->vxSetupBoard('600051', '宁波联合', 452, 452, 1, 2, '宁波联合 (600051)', '');
$i->vxSetupBoard('600052', '浙江广厦', 452, 452, 1, 2, '浙江广厦 (600052)', '');
$i->vxSetupBoard('600053', '中江地产', 452, 452, 1, 2, '中江地产 (600053)', '');
$i->vxSetupBoard('600054', '黄山旅游', 452, 452, 1, 2, '黄山旅游 (600054)', '');
$i->vxSetupBoard('600055', '万东医疗', 452, 452, 1, 2, '万东医疗 (600055)', '');
$i->vxSetupBoard('600056', '中技贸易', 452, 452, 1, 2, '中技贸易 (600056)', '');
$i->vxSetupBoard('600057', '夏新电子', 452, 452, 1, 2, '夏新电子 (600057)', '');
$i->vxSetupBoard('600058', '五矿发展', 452, 452, 1, 2, '五矿发展 (600058)', '');
$i->vxSetupBoard('600059', '古越龙山', 452, 452, 1, 2, '古越龙山 (600059)', '');
$i->vxSetupBoard('600060', '海信电器', 452, 452, 1, 2, '海信电器 (600060)', '');
$i->vxSetupBoard('600061', '中纺投资', 452, 452, 1, 2, '中纺投资 (600061)', '');
$i->vxSetupBoard('600062', '双鹤药业', 452, 452, 1, 2, '双鹤药业 (600062)', '');
$i->vxSetupBoard('600063', '皖维高新', 452, 452, 1, 2, '皖维高新 (600063)', '');
$i->vxSetupBoard('600064', '南京高科', 452, 452, 1, 2, '南京高科 (600064)', '');
$i->vxSetupBoard('600066', '宇通客车', 452, 452, 1, 2, '宇通客车 (600066)', '');
$i->vxSetupBoard('600067', '冠城大通', 452, 452, 1, 2, '冠城大通 (600067)', '');
$i->vxSetupBoard('600068', '葛洲坝', 452, 452, 1, 2, '葛洲坝 (600068)', '');
$i->vxSetupBoard('600069', '银鸽投资', 452, 452, 1, 2, '银鸽投资 (600069)', '');
$i->vxSetupBoard('600070', '浙江富润', 452, 452, 1, 2, '浙江富润 (600070)', '');
$i->vxSetupBoard('600071', '凤凰光学', 452, 452, 1, 2, '凤凰光学 (600071)', '');
$i->vxSetupBoard('600072', '江南重工', 452, 452, 1, 2, '江南重工 (600072)', '');
$i->vxSetupBoard('600073', '上海梅林', 452, 452, 1, 2, '上海梅林 (600073)', '');
$i->vxSetupBoard('600075', '新疆天业', 452, 452, 1, 2, '新疆天业 (600075)', '');
$i->vxSetupBoard('600100', '同方股份', 452, 452, 1, 2, '同方股份 (600100)', '');
$i->vxSetupBoard('600103', '青山纸业', 452, 452, 1, 2, '青山纸业 (600103)', '');
$i->vxSetupBoard('600104', '上海汽车', 452, 452, 1, 2, '上海汽车 (600104)', '');
$i->vxSetupBoard('600105', '永鼎光缆', 452, 452, 1, 2, '永鼎光缆 (600105)', '');
$i->vxSetupBoard('600109', '成都建设', 452, 452, 1, 2, '成都建设 (600109)', '');
$i->vxSetupBoard('600113', '浙江东日', 452, 452, 1, 2, '浙江东日 (600113)', '');
$i->vxSetupBoard('600121', '郑州煤电', 452, 452, 1, 2, '郑州煤电 (600121)', '');
$i->vxSetupBoard('600123', '兰花科创', 452, 452, 1, 2, '兰花科创 (600123)', '');
$i->vxSetupBoard('600126', '杭钢股份', 452, 452, 1, 2, '杭钢股份 (600126)', '');
$i->vxSetupBoard('600135', 'S乐凯', 452, 452, 1, 2, 'S乐凯 (600135)', '');
$i->vxSetupBoard('600137', '*ST长控', 452, 452, 1, 2, '*ST长控 (600137)', '');
$i->vxSetupBoard('600138', '中青旅', 452, 452, 1, 2, '中青旅 (600138)', '');
$i->vxSetupBoard('600150', '沪东重机', 452, 452, 1, 2, '沪东重机 (600150)', '');
$i->vxSetupBoard('600157', 'S鲁润', 452, 452, 1, 2, 'S鲁润 (600157)', '');
$i->vxSetupBoard('600161', '天坛生物', 452, 452, 1, 2, '天坛生物 (600161)', '');
$i->vxSetupBoard('600169', '太原重工', 452, 452, 1, 2, '太原重工 (600169)', '');
$i->vxSetupBoard('600182', 'S佳通', 452, 452, 1, 2, 'S佳通 (600182)', '');
$i->vxSetupBoard('600186', '莲花味精', 452, 452, 1, 2, '莲花味精 (600186)', '');
$i->vxSetupBoard('600193', '创兴科技', 452, 452, 1, 2, '创兴科技 (600193)', '');
$i->vxSetupBoard('600195', '中牧股份', 452, 452, 1, 2, '中牧股份 (600195)', '');
$i->vxSetupBoard('600198', '大唐电信', 452, 452, 1, 2, '大唐电信 (600198)', '');
$i->vxSetupBoard('600205', 'S山东铝', 452, 452, 1, 2, 'S山东铝 (600205)', '');
$i->vxSetupBoard('600207', '安彩高科', 452, 452, 1, 2, '安彩高科 (600207)', '');
$i->vxSetupBoard('600210', '紫江企业', 452, 452, 1, 2, '紫江企业 (600210)', '');
$i->vxSetupBoard('600213', 'S*ST亚星', 452, 452, 1, 2, 'S*ST亚星 (600213)', '');
$i->vxSetupBoard('600217', '秦岭水泥', 452, 452, 1, 2, '秦岭水泥 (600217)', '');
$i->vxSetupBoard('600223', '万杰高科', 452, 452, 1, 2, '万杰高科 (600223)', '');
$i->vxSetupBoard('600239', 'S红河', 452, 452, 1, 2, 'S红河 (600239)', '');
$i->vxSetupBoard('600252', '中恒集团', 452, 452, 1, 2, '中恒集团 (600252)', '');
$i->vxSetupBoard('600256', '广汇股份', 452, 452, 1, 2, '广汇股份 (600256)', '');
$i->vxSetupBoard('600263', '路桥建设', 452, 452, 1, 2, '路桥建设 (600263)', '');
$i->vxSetupBoard('600271', '航天信息', 452, 452, 1, 2, '航天信息 (600271)', '');
$i->vxSetupBoard('600290', '华仪电气', 452, 452, 1, 2, '华仪电气 (600290)', '');
$i->vxSetupBoard('600296', 'S兰铝', 452, 452, 1, 2, 'S兰铝 (600296)', '');
$i->vxSetupBoard('600299', '星新材料', 452, 452, 1, 2, '星新材料 (600299)', '');
$i->vxSetupBoard('600308', '华泰股份', 452, 452, 1, 2, '华泰股份 (600308)', '');
$i->vxSetupBoard('600312', '平高电气', 452, 452, 1, 2, '平高电气 (600312)', '');
$i->vxSetupBoard('600318', 'S巢东', 452, 452, 1, 2, 'S巢东 (600318)', '');
$i->vxSetupBoard('600320', '振华港机', 452, 452, 1, 2, '振华港机 (600320)', '');
$i->vxSetupBoard('600322', '天房发展', 452, 452, 1, 2, '天房发展 (600322)', '');
$i->vxSetupBoard('600323', '南海发展', 452, 452, 1, 2, '南海发展 (600323)', '');
$i->vxSetupBoard('600355', '精伦电子', 452, 452, 1, 2, '精伦电子 (600355)', '');
$i->vxSetupBoard('600362', '江西铜业', 452, 452, 1, 2, '江西铜业 (600362)', '');
$i->vxSetupBoard('600366', '宁波韵升', 452, 452, 1, 2, '宁波韵升 (600366)', '');
$i->vxSetupBoard('600375', '星马汽车', 452, 452, 1, 2, '星马汽车 (600375)', '');
$i->vxSetupBoard('600383', '金地集团', 452, 452, 1, 2, '金地集团 (600383)', '');
$i->vxSetupBoard('600393', '东华实业', 452, 452, 1, 2, '东华实业 (600393)', '');
$i->vxSetupBoard('600401', '江苏申龙', 452, 452, 1, 2, '江苏申龙 (600401)', '');
$i->vxSetupBoard('600456', '宝钛股份', 452, 452, 1, 2, '宝钛股份 (600456)', '');
$i->vxSetupBoard('600460', '士兰微', 452, 452, 1, 2, '士兰微 (600460)', '');
$i->vxSetupBoard('600467', '好当家', 452, 452, 1, 2, '好当家 (600467)', '');
$i->vxSetupBoard('600470', '六国化工', 452, 452, 1, 2, '六国化工 (600470)', '');
$i->vxSetupBoard('600475', '华光股份', 452, 452, 1, 2, '华光股份 (600475)', '');
$i->vxSetupBoard('600477', '杭萧钢构', 452, 452, 1, 2, '杭萧钢构 (600477)', '');
$i->vxSetupBoard('600481', '双良股份', 452, 452, 1, 2, '双良股份 (600481)', '');
$i->vxSetupBoard('600493', '凤竹纺织', 452, 452, 1, 2, '凤竹纺织 (600493)', '');
$i->vxSetupBoard('600497', '驰宏锌锗', 452, 452, 1, 2, '驰宏锌锗 (600497)', '');
$i->vxSetupBoard('600500', '中化国际', 452, 452, 1, 2, '中化国际 (600500)', '');
$i->vxSetupBoard('600508', '上海能源', 452, 452, 1, 2, '上海能源 (600508)', '');
$i->vxSetupBoard('600510', '黑牡丹', 452, 452, 1, 2, '黑牡丹 (600510)', '');
$i->vxSetupBoard('600515', '*ST一投', 452, 452, 1, 2, '*ST一投 (600515)', '');
$i->vxSetupBoard('600519', '贵州茅台', 452, 452, 1, 2, '贵州茅台 (600519)', '');
$i->vxSetupBoard('600531', '豫光金铅', 452, 452, 1, 2, '豫光金铅 (600531)', '');
$i->vxSetupBoard('600540', '新赛股份', 452, 452, 1, 2, '新赛股份 (600540)', '');
$i->vxSetupBoard('600546', '中油化建', 452, 452, 1, 2, '中油化建 (600546)', '');
$i->vxSetupBoard('600553', '太行水泥', 452, 452, 1, 2, '太行水泥 (600553)', '');
$i->vxSetupBoard('600566', '洪城股份', 452, 452, 1, 2, '洪城股份 (600566)', '');
$i->vxSetupBoard('600569', '安阳钢铁', 452, 452, 1, 2, '安阳钢铁 (600569)', '');
$i->vxSetupBoard('600570', '恒生电子', 452, 452, 1, 2, '恒生电子 (600570)', '');
$i->vxSetupBoard('600601', '方正科技', 452, 452, 1, 2, '方正科技 (600601)', '');
$i->vxSetupBoard('600607', '上实医药', 452, 452, 1, 2, '上实医药 (600607)', '');
$i->vxSetupBoard('600609', '*ST金杯', 452, 452, 1, 2, '*ST金杯 (600609)', '');
$i->vxSetupBoard('600619', '海立股份', 452, 452, 1, 2, '海立股份 (600619)', '');
$i->vxSetupBoard('600622', '嘉宝集团', 452, 452, 1, 2, '嘉宝集团 (600622)', '');
$i->vxSetupBoard('600629', '新钢钒', 452, 452, 1, 2, '新钢钒 (600629)', '');
$i->vxSetupBoard('600641', '万业企业', 452, 452, 1, 2, '万业企业 (600641)', '');
$i->vxSetupBoard('600642', '申能股份', 452, 452, 1, 2, '申能股份 (600642)', '');
$i->vxSetupBoard('600649', '原水股份', 452, 452, 1, 2, '原水股份 (600649)', '');
$i->vxSetupBoard('600651', '飞乐音响', 452, 452, 1, 2, '飞乐音响 (600651)', '');
$i->vxSetupBoard('600653', '申华控股', 452, 452, 1, 2, '申华控股 (600653)', '');
$i->vxSetupBoard('600660', '福耀玻璃', 452, 452, 1, 2, '福耀玻璃 (600660)', '');
$i->vxSetupBoard('600666', '西南药业', 452, 452, 1, 2, '西南药业 (600666)', '');
$i->vxSetupBoard('600675', '中华企业', 452, 452, 1, 2, '中华企业 (600675)', '');
$i->vxSetupBoard('600677', '航天通信', 452, 452, 1, 2, '航天通信 (600677)', '');
$i->vxSetupBoard('600690', '青岛海尔', 452, 452, 1, 2, '青岛海尔 (600690)', '');
$i->vxSetupBoard('600702', '沱牌曲酒', 452, 452, 1, 2, '沱牌曲酒 (600702)', '');
$i->vxSetupBoard('600707', '彩虹股份', 452, 452, 1, 2, '彩虹股份 (600707)', '');
$i->vxSetupBoard('600717', '天津港', 452, 452, 1, 2, '天津港 (600717)', '');
$i->vxSetupBoard('600722', '沧州化工', 452, 452, 1, 2, '沧州化工 (600722)', '');
$i->vxSetupBoard('600725', '云维股份', 452, 452, 1, 2, '云维股份 (600725)', '');
$i->vxSetupBoard('600726', '华电能源', 452, 452, 1, 2, '华电能源 (600726)', '');
$i->vxSetupBoard('600741', '巴士股份', 452, 452, 1, 2, '巴士股份 (600741)', '');
$i->vxSetupBoard('600744', '华银电力', 452, 452, 1, 2, '华银电力 (600744)', '');
$i->vxSetupBoard('600761', '安徽合力', 452, 452, 1, 2, '安徽合力 (600761)', '');
$i->vxSetupBoard('600764', '中电广通', 452, 452, 1, 2, '中电广通 (600764)', '');
$i->vxSetupBoard('600768', '宁波富邦', 452, 452, 1, 2, '宁波富邦 (600768)', '');
$i->vxSetupBoard('600770', '综艺股份', 452, 452, 1, 2, '综艺股份 (600770)', '');
$i->vxSetupBoard('600785', '新华百货', 452, 452, 1, 2, '新华百货 (600785)', '');
$i->vxSetupBoard('600787', '中储股份', 452, 452, 1, 2, '中储股份 (600787)', '');
$i->vxSetupBoard('600791', '天创置业', 452, 452, 1, 2, '天创置业 (600791)', '');
$i->vxSetupBoard('600795', '国电电力', 452, 452, 1, 2, '国电电力 (600795)', '');
$i->vxSetupBoard('600804', '鹏博士', 452, 452, 1, 2, '鹏博士 (600804)', '');
$i->vxSetupBoard('600812', '华北制药', 452, 452, 1, 2, '华北制药 (600812)', '');
$i->vxSetupBoard('600820', '隧道股份', 452, 452, 1, 2, '隧道股份 (600820)', '');
$i->vxSetupBoard('600822', '上海物贸', 452, 452, 1, 2, '上海物贸 (600822)', '');
$i->vxSetupBoard('600839', '四川长虹', 452, 452, 1, 2, '四川长虹 (600839)', '');
$i->vxSetupBoard('600849', '上海医药', 452, 452, 1, 2, '上海医药 (600849)', '');
$i->vxSetupBoard('600851', '海欣股份', 452, 452, 1, 2, '海欣股份 (600851)', '');
$i->vxSetupBoard('600854', '春兰股份', 452, 452, 1, 2, '春兰股份 (600854)', '');
$i->vxSetupBoard('600863', '内蒙华电', 452, 452, 1, 2, '内蒙华电 (600863)', '');
$i->vxSetupBoard('600866', '星湖科技', 452, 452, 1, 2, '星湖科技 (600866)', '');
$i->vxSetupBoard('600868', '梅雁水电', 452, 452, 1, 2, '梅雁水电 (600868)', '');
$i->vxSetupBoard('600869', '三普药业', 452, 452, 1, 2, '三普药业 (600869)', '');
$i->vxSetupBoard('600871', '仪征化纤', 452, 452, 1, 2, '仪征化纤 (600871)', '');
$i->vxSetupBoard('600875', '东方电机', 452, 452, 1, 2, '东方电机 (600875)', '');
$i->vxSetupBoard('600879', '火箭股份', 452, 452, 1, 2, '火箭股份 (600879)', '');
$i->vxSetupBoard('600882', '大成股份', 452, 452, 1, 2, '大成股份 (600882)', '');
$i->vxSetupBoard('600884', '杉杉股份', 452, 452, 1, 2, '杉杉股份 (600884)', '');
$i->vxSetupBoard('600887', '伊利股份', 452, 452, 1, 2, '伊利股份 (600887)', '');
$i->vxSetupBoard('600894', '广钢股份', 452, 452, 1, 2, '广钢股份 (600894)', '');
$i->vxSetupBoard('600900', '长江电力', 452, 452, 1, 2, '长江电力 (600900)', '');
$i->vxSetupBoard('600960', '滨州活塞', 452, 452, 1, 2, '滨州活塞 (600960)', '');
$i->vxSetupBoard('600961', '株冶火炬', 452, 452, 1, 2, '株冶火炬 (600961)', '');
$i->vxSetupBoard('600970', '中材国际', 452, 452, 1, 2, '中材国际 (600970)', '');
$i->vxSetupBoard('600971', '恒源煤电', 452, 452, 1, 2, '恒源煤电 (600971)', '');
$i->vxSetupBoard('600979', '广安爱众', 452, 452, 1, 2, '广安爱众 (600979)', '');
$i->vxSetupBoard('600995', '文山电力', 452, 452, 1, 2, '文山电力 (600995)', '');
$i->vxSetupBoard('601007', '金陵饭店', 452, 452, 1, 2, '金陵饭店 (601007)', '');
$i->vxSetupBoard('601111', '中国国航', 452, 452, 1, 2, '中国国航 (601111)', '');
$i->vxSetupBoard('601318', '中国平安', 452, 452, 1, 2, '中国平安 (601318)', '');
$i->vxSetupBoard('601333', '广深铁路', 452, 452, 1, 2, '广深铁路 (601333)', '');
$i->vxSetupBoard('601398', '工商银行', 452, 452, 1, 2, '工商银行 (601398)', '');
$i->vxSetupBoard('601588', '北辰实业', 452, 452, 1, 2, '北辰实业 (601588)', '');
$i->vxSetupBoard('601628', '中国人寿', 452, 452, 1, 2, '中国人寿 (601628)', '');
$i->vxSetupBoard('601872', '招商轮船', 452, 452, 1, 2, '招商轮船 (601872)', '');
$i->vxSetupBoard('601988', '中国银行', 452, 452, 1, 2, '中国银行 (601988)', '');
$i->vxSetupBoard('601991', '大唐发电', 452, 452, 1, 2, '大唐发电 (601991)', '');

$i->vxSetupBoard('000001', 'S深发展A', 452, 452, 1, 2, 'S深发展A (000001)', '');
$i->vxSetupBoard('000002', '万科A', 452, 452, 1, 2, '万科A (000002)', '');
$i->vxSetupBoard('000004', '*ST国农', 452, 452, 1, 2, '*ST国农 (000004)', '');
$i->vxSetupBoard('000005', 'ST星源', 452, 452, 1, 2, 'ST星源 (000005)', '');
$i->vxSetupBoard('000006', '深振业A', 452, 452, 1, 2, '深振业A (000006)', '');
$i->vxSetupBoard('000007', '深达声A', 452, 452, 1, 2, '深达声A (000007)', '');
$i->vxSetupBoard('000009', 'S深宝安A', 452, 452, 1, 2, 'S深宝安A (000009)', '');
$i->vxSetupBoard('000031', '中粮地产', 452, 452, 1, 2, '中粮地产 (000031)', '');
$i->vxSetupBoard('000045', '深纺织A', 452, 452, 1, 2, '深纺织A (000045)', '');
$i->vxSetupBoard('000058', '深赛格', 452, 452, 1, 2, '深赛格 (000058)', '');
$i->vxSetupBoard('000060', '中金岭南', 452, 452, 1, 2, '中金岭南 (000060)', '');
$i->vxSetupBoard('000063', '中兴通讯', 452, 452, 1, 2, '中兴通讯 (000063)', '');
$i->vxSetupBoard('000078', '海王生物', 452, 452, 1, 2, '海王生物 (000078)', '');
$i->vxSetupBoard('000088', '盐田港', 452, 452, 1, 2, '盐田港 (000088)', '');
$i->vxSetupBoard('000089', '深圳机场', 452, 452, 1, 2, '深圳机场 (000089)', '');
$i->vxSetupBoard('000135', 'S乐凯', 452, 452, 1, 2, 'S乐凯 (000135)', '');
$i->vxSetupBoard('000158', '常山股份', 452, 452, 1, 2, '常山股份 (000158)', '');
$i->vxSetupBoard('000402', '金融街', 452, 452, 1, 2, '金融街 (000402)', '');
$i->vxSetupBoard('000410', '沈阳机床', 452, 452, 1, 2, '沈阳机床 (000410)', '');
$i->vxSetupBoard('000418', '小天鹅A', 452, 452, 1, 2, '小天鹅A (000418)', '');
$i->vxSetupBoard('000422', '湖北宜化', 452, 452, 1, 2, '湖北宜化 (000422)', '');
$i->vxSetupBoard('000426', '大地基础', 452, 452, 1, 2, '大地基础 (000426)', '');
$i->vxSetupBoard('000513', '丽珠集团', 452, 452, 1, 2, '丽珠集团 (000513)', '');
$i->vxSetupBoard('000521', 'S美菱', 452, 452, 1, 2, 'S美菱 (000521)', '');
$i->vxSetupBoard('000522', '白云山A', 452, 452, 1, 2, '白云山A (000522)', '');
$i->vxSetupBoard('000543', '皖能电力', 452, 452, 1, 2, '皖能电力 (000543)', '');
$i->vxSetupBoard('000545', '吉林制药', 452, 452, 1, 2, '吉林制药 (000545)', '');
$i->vxSetupBoard('000554', '泰山石油', 452, 452, 1, 2, '泰山石油 (000554)', '');
$i->vxSetupBoard('000602', '金马集团', 452, 452, 1, 2, '金马集团 (000602)', '');
$i->vxSetupBoard('000617', '石油济柴', 452, 452, 1, 2, '石油济柴 (000617)', '');
$i->vxSetupBoard('000659', '珠海中富', 452, 452, 1, 2, '珠海中富 (000659)', '');
$i->vxSetupBoard('000682', '东方电子', 452, 452, 1, 2, '东方电子 (000682)', '');
$i->vxSetupBoard('000720', '鲁能泰山', 452, 452, 1, 2, '鲁能泰山 (000720)', '');
$i->vxSetupBoard('000727', '华东科技', 452, 452, 1, 2, '华东科技 (000727)', '');
$i->vxSetupBoard('000735', '罗牛山', 452, 452, 1, 2, '罗牛山 (000735)', '');
$i->vxSetupBoard('000739', '普洛康裕', 452, 452, 1, 2, '普洛康裕 (000739)', '');
$i->vxSetupBoard('000766', '通化金马', 452, 452, 1, 2, '通化金马 (000766)', '');
$i->vxSetupBoard('000768', '西飞国际', 452, 452, 1, 2, '西飞国际 (000768)', '');
$i->vxSetupBoard('000777', '中核科技', 452, 452, 1, 2, '中核科技 (000777)', '');
$i->vxSetupBoard('000779', 'ST派神', 452, 452, 1, 2, 'ST派神 (000779)', '');
$i->vxSetupBoard('000800', '一汽轿车', 452, 452, 1, 2, '一汽轿车 (000800)', '');
$i->vxSetupBoard('000807', '云铝股份', 452, 452, 1, 2, '云铝股份 (000807)', '');
$i->vxSetupBoard('000819', '岳阳兴长', 452, 452, 1, 2, '岳阳兴长 (000819)', '');
$i->vxSetupBoard('000822', '山东海化', 452, 452, 1, 2, '山东海化 (000822)', '');
$i->vxSetupBoard('000833', '贵糖股份', 452, 452, 1, 2, '贵糖股份 (000833)', '');
$i->vxSetupBoard('000848', '承德露露', 452, 452, 1, 2, '承德露露 (000848)', '');
$i->vxSetupBoard('000858', '五粮液', 452, 452, 1, 2, '五粮液 (000858)', '');
$i->vxSetupBoard('000860', '顺鑫农业', 452, 452, 1, 2, '顺鑫农业 (000860)', '');
$i->vxSetupBoard('000881', '大连国际', 452, 452, 1, 2, '大连国际 (000881)', '');
$i->vxSetupBoard('000888', '峨眉山A', 452, 452, 1, 2, '峨眉山A (000888)', '');
$i->vxSetupBoard('000889', '渤海物流', 452, 452, 1, 2, '渤海物流 (000889)', '');
$i->vxSetupBoard('000895', 'S双汇', 452, 452, 1, 2, 'S双汇 (000895)', '');
$i->vxSetupBoard('000898', '鞍钢股份', 452, 452, 1, 2, '鞍钢股份 (000898)', '');
$i->vxSetupBoard('000900', '现代投资', 452, 452, 1, 2, '现代投资 (000900)', '');
$i->vxSetupBoard('000926', '福星科技', 452, 452, 1, 2, '福星科技 (000926)', '');
$i->vxSetupBoard('000933', '神火股份', 452, 452, 1, 2, '神火股份 (000933)', '');
$i->vxSetupBoard('000959', '首钢股份', 452, 452, 1, 2, '首钢股份 (000959)', '');
$i->vxSetupBoard('000960', '锡业股份', 452, 452, 1, 2, '锡业股份 (000960)', '');
$i->vxSetupBoard('000962', '东方钽业', 452, 452, 1, 2, '东方钽业 (000962)', '');
$i->vxSetupBoard('000966', '长源电力', 452, 452, 1, 2, '长源电力 (000966)', '');
$i->vxSetupBoard('000983', '西山煤电', 452, 452, 1, 2, '西山煤电 (000983)', '');
$i->vxSetupBoard('000985', '大庆华科', 452, 452, 1, 2, '大庆华科 (000985)', '');
$i->vxSetupBoard('002005', '德豪润达', 452, 452, 1, 2, '德豪润达 (002005)', '');
$i->vxSetupBoard('002021', '中捷股份', 452, 452, 1, 2, '中捷股份 (002021)', '');
$i->vxSetupBoard('002022', '科华生物', 452, 452, 1, 2, '科华生物 (002022)', '');
$i->vxSetupBoard('002028', '思源电气', 452, 452, 1, 2, '思源电气 (002028)', '');
$i->vxSetupBoard('002024', '苏宁电器', 452, 452, 1, 2, '苏宁电器 (002024)', '');
$i->vxSetupBoard('002033', '丽江旅游', 452, 452, 1, 2, '丽江旅游 (002033)', '');
$i->vxSetupBoard('002066', '瑞泰科技', 452, 452, 1, 2, '瑞泰科技 (002066)', '');
$i->vxSetupBoard('002069', '獐子岛', 452, 452, 1, 2, '獐子岛 (002069)', '');
$i->vxSetupBoard('002093', '国脉科技', 452, 452, 1, 2, '国脉科技 (002093)', '');
$i->vxSetupBoard('002100', '天康生物', 452, 452, 1, 2, '天康生物 (002100)', '');
$i->vxSetupBoard('002107', '沃华医药', 452, 452, 1, 2, '沃华医药 (002107)', '');
$i->vxSetupBoard('002110', '三钢闽光', 452, 452, 1, 2, '三钢闽光 (002110)', '');
$i->vxSetupBoard('002114', '罗平锌电', 452, 452, 1, 2, '罗平锌电 (002114)', '');
$i->vxSetupBoard('002121', '科陆电子', 452, 452, 1, 2, '科陆电子 (002121)', '');
$i->vxSetupBoard('002126', '银轮股份', 452, 452, 1, 2, '银轮股份 (002126)', '');

$i->vxSetupBoard('399134', '造纸指数', 452, 452, 1, 2, '造纸印刷指数 (399134)', '');
$i->vxSetupBoard('399150', '建筑指数', 452, 452, 1, 2, '建筑业指数 (399150)', '');
$i->vxSetupBoard('399300', '沪深300', 452, 452, 1, 2, '沪深300 (399300)', '');

$i->vxSetupBoard('f000001', '华夏成长', 452, 452, 1, 2, '华夏成长 (000001)', '');
$i->vxSetupBoard('f000011', '华夏大盘精选', 452, 452, 1, 2, '华夏大盘精选 (000011)', '');
$i->vxSetupBoard('f000021', '华夏优势', 452, 452, 1, 2, '华夏优势 (000021)', '');
$i->vxSetupBoard('f001001', '华夏债券A/B类', 452, 452, 1, 2, '华夏债券A/B类 (001001)', '');
$i->vxSetupBoard('f001003', '华夏债券C类', 452, 452, 1, 2, '华夏债券C类 (001003)', '');
$i->vxSetupBoard('f002001', '华夏回报', 452, 452, 1, 2, '华夏回报 (002001)', '');
$i->vxSetupBoard('f002011', '华夏红利', 452, 452, 1, 2, '华夏红利 (002011)', '');
$i->vxSetupBoard('f002021', '华夏回报二号', 452, 452, 1, 2, '华夏回报二号 (002021)', '');
$i->vxSetupBoard('f050004', '博时精选', 452, 452, 1, 2, '博时精选 (050004)', '');
$i->vxSetupBoard('f070002', '嘉实增长', 452, 452, 1, 2, '嘉实增长 (070002)', '');
$i->vxSetupBoard('f110010', '易方达价值成长', 452, 452, 1, 2, '易方达价值成长 (110010)', '');
$i->vxSetupBoard('f160706', '嘉实300', 452, 452, 1, 2, '嘉实300 (160706)', '');
$i->vxSetupBoard('f161706', '招商成长', 452, 452, 1, 2, '招商成长 (161706)', '');
$i->vxSetupBoard('f377010', '上投阿尔法', 452, 452, 1, 2, '上投阿尔法 (377010)', '');
$i->vxSetupBoard('f377020', '上投内需动力', 452, 452, 1, 2, '上投内需动力 (377020)', '');
$i->vxSetupBoard('f519688', '交银精选', 452, 452, 1, 2, '交银精选 (519688)', '');

// Elyisum
//$i->vxSetupSectionExtra('elysium', '极乐境');
$i->vxSetupBoard('music', '爱听音乐', 220, 220, 1, 2, '喜爱音乐的孩子不会变坏', '');
	$i->vxSetupRelatedByName('music', 'http://last.fm/', 'Last.fm');
$i->vxSetupBoard('gnr', "Guns N' Roses", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('lacrimosa', "Lacrimosa", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('gorillaz', "Gorillaz", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('jamesblunt', "James Blunt", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('metallica', "Metallica", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('radiohead', "Radiohead", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('blur', "Blur", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('u2', "U2", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('korn', "Korn", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('linkinpark', "Linkin Park", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('fortminor', "Fort Minor", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('feeder', "Feeder", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('dido', "Dido", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('cranberries', "The Cranberries", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('current93', "Current 93", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('scorpions', "Scorpions", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('50cent', "50 Cent", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('persephone', "Persephone", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('avrillavigne', "Avril Lavigne", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('bonjovi', "Bon Jovi", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('suede', "Suede", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('rhapsody', "Rhapsody", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('nirvana', "Nirvana", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('lakeoftears', "Lake of Tears", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('aqua', "Aqua", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('simpleplan', "Simple Plan", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('oasis', "Oasis", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('rem', "REM", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('pinkfloyd', "Pink Floyd", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('thedoors', "The Doors", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('police', "Police", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('limpbizkit', "Limp Bizkit", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('eagles', "Eagles", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('beatles', "The Beatles", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('coldplay', "Coldplay", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('christinaaguilera', 'Christina Aguilera', 220, 220, 1, 2, '', '');
$i->vxSetupBoard('enigma', 'Enigma', 220, 220, 1, 2, '', '');
$i->vxSetupBoard('damienrice', 'Damien Rice', 220, 220, 1, 2, '', '');
$i->vxSetupBoard('jaychou', "周杰伦", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('fayewong', "王菲", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('xuwei', "许巍", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('cheer', "陈绮贞", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('beyond', "Beyond", 220, 220, 1, 2, '', '');
$i->vxSetupBoard('davidtao', "陶喆", 220, 220, 1, 2, '', '');
	$i->vxSetupRelatedByName('davidtao', 'http://www.davidtao.com/', 'DavidTao.com');

//$i->vxSetupSectionExtra('sigil', '法印城');
// sigil
$i->vxSetupBoard('levis', "Levi's", 71, 71, 1, 2, '', '');
	$i->vxSetupRelatedByName('levis', 'http://www.levi.com.cn/', "Levi's 中国官方网站");
	$i->vxSetupRelatedByName('levis', 'http://www.levisstore.com/', "Levi's Store");
$i->vxSetupBoard('g-star', "G-STAR", 71, 71, 1, 2, 'RAW', '');
	$i->vxSetupRelatedByName('g-star', 'http://www.g-star.com/', "G-STAR RAW");
$i->vxSetupBoard('converse', "Converse", 71, 71, 1, 2, '', '');
	$i->vxSetupRelatedByName('converse', 'http://www.conslive.com/', '匡威网上专卖店');
$i->vxSetupBoard('gas', "GAS", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('nike', "Nike", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('superlovers', "SUPER LOVERS", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('izzue', "izzue", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('adidas', "Adidas", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('puma', "Puma", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('uniqlo', "UNIQLO", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('prada', "PRADA", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('fcuk', "FCUK", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('rbk', "Reebok", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('ck', "Calvin Klein", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('dior', "Dior", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('espirit', "Espirit", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('lee', "Lee", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('5thstreet', "5th Street", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('vans', "VANS", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('diesel', "DIESEL", 71, 71, 1, 2, '', '');
	$i->vxSetupRelatedByName('diesel', 'http://www.diesel.com/', 'D I E S E L');
$i->vxSetupBoard('kappa', "Kappa", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('westwood', "Vivienne Westwood", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('givenchy', "Givenchy", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('gucci', "Gucci", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('chanel', "Chanel", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('lanvin', "Lanvin", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('ysl', "Yves Saint Laurent", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('valentino', "Valentino", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('armani', "Giorgio Armani", 71, 71, 1, 2, '', '');
	$i->vxSetupRelatedByName('armani', 'http://www.armaniexchange.com/', 'Armani Exchange');
$i->vxSetupBoard('umbro', "Umbro", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('timberland', "Timberland", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('newbalance', "New Balance", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('cabbeen', "Cabbeen", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('zegna', "Ermenegildo Zegna", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('burberry', "Burberry", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('mango', "Mango", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('exr', "EXR", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('chevignon', "CHEVIGNON", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('colombia', "Colombia", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('northface', "North Face", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('cat', "CAT", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('montblanc', "Mont Blanc", 71, 71, 1, 2, '', '');
$i->vxSetupBoard('abercrombie', "Abercrombie & Fitch", 71, 71, 1, 2);

// limbo
$i->vxSetupBoard('kunming', '昆明', 2, 2, 1, 2, '', '');
	$i->vxSetupRelatedByName('kunming', 'http://www.ynu.edu.cn/', '云南大学');
	$i->vxSetupRelatedByName('kunming', 'http://kunming.kijiji.cn/', '客齐集昆明');
$i->vxSetupBoard('xiamen', '厦门', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('yangzhou', '扬州', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('shanghai', '上海', 2, 2, 1, 2, '', '');
	$i->vxSetupRelatedByName('shanghai', 'http://www.sjtu.edu.cn/', '上海交通大学');
	$i->vxSetupRelatedByName('shanghai', 'http://www.fudan.edu.cn/', '复旦大学');
	$i->vxSetupRelatedByName('shanghai', 'http://shanghai.kijiji.cn/', '客齐集上海');
$i->vxSetupBoard('harbin', '哈尔滨', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('qingdao', '青岛', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('weihai', '威海', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('haikou', '海口', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('sanya', '三亚', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('guilin', '桂林', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('fuzhou', '福州', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('rushan', '乳山', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('dalian', '大连', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('dongguan', '东莞', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('foshan', '佛山', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('lasa', '拉萨', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('jinan', '济南', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('shenzhen', '深圳', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('beijing', '北京', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('tianjin', '天津', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('changsha', '长沙', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('shenyang', '沈阳', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('zhengzhou', '郑州', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('yantai', '烟台', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('suzhou', '苏州', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('taiyuan', '太原', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('hefei', '合肥', 2, 2, 1, 2, '', '');
	$i->vxSetupRelatedByName('hefei', 'http://www.ahu.edu.cn/', '安徽大学');
$i->vxSetupBoard('shantou', '汕头', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('wulumuqi', '乌鲁木齐', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('qujing', '曲靖', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('taiyuan', '太原', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('shijiazhuang', '石家庄', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('wuhan', '武汉', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('tianjin', '天津', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('hongkong', '香港', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('macau', '澳门', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('taipei', '台北', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('chongqing', '重庆', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('chengdu', '成都', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('hangzhou', '杭州', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('xian', '西安', 2, 2, 1, 2, '', '');
	$i->vxSetupRelatedByName('xian', 'http://www.nwu.edu.cn/', '西北大学');
$i->vxSetupBoard('lijiang', '丽江', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('guangzhou', '广州', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('zhuhai', '珠海', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('ningbo', '宁波', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('nanjing', '南京', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('nanning', '南宁', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('nanchang', '南昌', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('tokyo', '东京', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('osaka', '大阪', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('london', 'London', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('toronto', 'Toronto', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('sydney', 'Sydney', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('paris', 'Paris', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('nyc', 'New York', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('chicago', 'Chicago', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('dali', '大理', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('guiyang', '贵阳', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('vatican', 'Vatican', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('dubai', 'Dubai', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('jerusalem', 'Jerusalem', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('tibet', '西藏', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('wenzhou', '温州', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('berlin', 'Berlin', 2, 2, 1, 2, '', '');
$i->vxSetupBoard('seoul', 'Seoul', 2, 2, 1, 2, '', '');

// mechanus
$i->vxSetupBoard('3dsmax', '3dsmax', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('maya', 'Maya', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('dreamweaver', 'Dreamweaver', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('fireworks', 'Fireworks', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('flash', 'Flash', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('photoshop', 'Photoshop', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('eva', 'Neon Genesis Evangelion', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('c', 'C/C++', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('csharp', 'C#', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('delphi', 'Delphi', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('logo', 'Logo', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('pascal', 'Pascal', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('wordpress', 'WordPress', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('bbpress', 'bbPress', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('adobe', 'Adobe', 3, 3, 1, 2, 'Adobe 产品讨论专区', '');
$i->vxSetupBoard('js', 'JavaScript', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('html', 'HTML', 3, 3, 1, 2, 'HTML 语言技术讨论专区', '');
$i->vxSetupBoard('mono', 'Mono', 3, 3, 1, 2, '', '');
	$i->vxSetupRelatedByName('mono', 'http://www.mono-project.com/', 'Mono');
	$i->vxSetupRelatedByName('mono', 'http://www.monodevelop.com/', 'MonoDevelop');
$i->vxSetupBoard('json', 'JavaScript Object Notation', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('yaml', "YAML Ain't Markup Language", 3, 3, 1, 2, '', '');
$i->vxSetupBoard('firefox', 'Mozilla Firefox', 3, 3, 1, 2, '<script type="text/javascript"><!--
google_ad_client = "pub-9823529788289591";
google_ad_output = "textlink";
google_ad_format = "ref_text";
google_cpa_choice = "CAAQqcu1_wEaCF2H5Hv651t_KOm84YcB";
google_ad_channel = "";
//--></script>
<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>', '');
$i->vxSetupBoard('thunderbird', 'Mozilla Thunderbird', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('camino', 'Camino', 3, 3, 1, 2, 'Mozilla power, Mac style.', 'Faster, Safer, Better');
	$i->vxSetupRelatedByName('camino', 'http://www.caminobrowser.org/', 'Camino');
$i->vxSetupBoard('fortran', 'Fortran', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('compiler', '编译器技术', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('zune', 'Zune', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('opera', 'Opera', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('seo', '搜索引擎优化', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('lucene', 'Apache Lucene', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('asm', '汇编语言', 3, 3, 1, 2, 'Be a real programmer.', 'x86 | arm | sparc | mips | ppc | s390');
$i->vxSetupBoard('c', 'C/C++', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('zope', 'Zope/Plone', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('portable', '移动设备技术', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('httpd', 'Apache HTTP Server', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('ant', 'Apache Ant', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('harmony', 'Apache Harmony', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('tomcat', 'Apache Tomcat', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('geronimo', 'Apache Geronimo', 3, 3, 1, 2, '', 'Welcome to Apache Geronimo, the J2EE server project of the Apache Software Foundation.');
$i->vxSetupBoard('db4o', 'db4o', 3, 3, 1, 2, 'db4o :: Native Java & .NET Object Database :: Open Source', 'db4objects');
$i->vxSetupBoard('sqlserver', 'SQL Server', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('firebird', 'Firebird', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('innodb', 'InnoDB', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('bdb', 'Berkeley DB', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('sybase', 'Sybase', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('db2', 'DB2', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('sqlite', 'SQLite', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('derby', 'Apache Derby', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('postgresql', 'PostgreSQL', 3, 3, 1, 2, "The world's most advanced open source database", '');
$i->vxSetupBoard('mysql', 'MySQL', 3, 3, 1, 2, 'All About MySQL', '够用就好的可爱数据库');
	$i->vxSetupRelatedByName('mysql', 'http://www.phpmyadmin.net/home_page/index.php', 'phpMyAdmin');
	$i->vxSetupRelatedByName('mysql', 'http://dev.mysql.com/', 'MySQL Developer Zone');
	$i->vxSetupChannelByName('mysql', 'http://www.planetmysql.org/rss20.xml');
	$i->vxSetupChannelByName('mysql', 'http://www.primebase.com/xt/pbxt.rss');
$i->vxSetupBoard('babel', 'Project Babel', 3, 3, 1, 2, 'way to explore | way too extreme', 'V2EX | software for internet');
	
	// RSS Feeds from Google Groups
	$i->vxSetupChannelByName('babel', 'http://groups.google.com/group/v2ex-commit/feed/rss_v2_0_msgs.xml?num=50');
	$i->vxSetupChannelByName('babel', 'http://groups.google.com/group/v2ex-issue/feed/rss_v2_0_msgs.xml?num=50');
	$i->vxSetupChannelByName('babel', 'http://groups.google.com/group/v2ex/feed/rss_v2_0_msgs.xml?num=50');
	
	// Sites on Google Groups
	$i->vxSetupRelatedByName('babel', 'http://groups.google.com/group/v2ex', 'V2EX Blacksmith');
	$i->vxSetupRelatedByName('babel', 'http://groups.google.com/group/v2ex-commit', 'V2EX Commit');
	$i->vxSetupRelatedByName('babel', 'http://groups.google.com/group/v2ex-issue', 'V2EX Issue');
	$i->vxSetupRelatedByName('babel', 'http://code.google.com/p/project-babel', 'V2EX Code');
	
	// Other interesting sites Livid likes
	$i->vxSetupChannelByName('babel', 'http://www.osnews.com/files/recent.xml');
	$i->vxSetupChannelByName('babel', 'http://rss.slashdot.org/Slashdot/slashdot');
	$i->vxSetupChannelByName('babel', 'http://www.betanews.com/rss2');
	$i->vxSetupChannelByName('babel', 'http://webkit.opendarwin.org/blog/?feed=rss2');
$i->vxSetupBoard('midgard', 'Project Midgard', 3, 3, 1, 2, 'way to explore | way too extreme', 'V2EX | software for internet');
$i->vxSetupBoard('zen', 'Project Zen', 3, 3, 1, 2, 'When time matters!', 'V2EX | software for internet');
$i->vxSetupBoard('olpc', 'One Laptop Per Child', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('mac', 'Mac', 3, 3, 1, 2, 'We are APPLEOHOLICS!', '');
	$i->vxSetupChannelByName('mac', 'http://www.sinomac.com/rss.php');
	$i->vxSetupChannelByName('mac', 'http://feeds.feedburner.com/com/WvuX');
	$i->vxSetupChannelByName('mac', 'http://macslash.org/rss/macslash.xml');
	$i->vxSetupChannelByName('mac', 'http://feeds.macworld.com/macworld/all');
	$i->vxSetupChannelByName('mac', 'http://www.macintouch.com/rss.xml');
	$i->vxSetupChannelByName('mac', 'http://www.macnn.com/macnn.rss');
	$i->vxSetupRelatedByName('mac', 'http://www.apple.com/', 'Apple');
$i->vxSetupBoard('palm', 'Palm', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('quantum', '量子物理', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('machine', '硬件讨论区', 3, 3, 1, 2, '乐趣无穷的计算机硬件，我们探索无限可能性！', '');
	$i->vxSetupChannelByName('machine', 'http://cn.engadget.com/rss.xml');
	$i->vxSetupRelatedByName('machine', 'http://www.newegg.com.cn/', '新蛋网');
$i->vxSetupBoard('solaris', 'Solaris', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('macosx', 'Mac OS X', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('plan9', 'Plan 9', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('beos', 'BeOS', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('dos', 'DOS', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('qnx', 'QNX', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('zeta', 'Zeta', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('syllable', 'Syllable', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('live', 'Windows Live', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('vista', 'Windows Vista', 3, 3, 1, 2, '', '');
	$i->vxSetupChannelByName('vista', 'http://windowsvistablog.com/blogs/MainFeed.aspx');
$i->vxSetupBoard('win2003', 'Windows 2003', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('winxp', 'Windows XP', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('office2003', 'Office 2003', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('office2007', 'Office 2007', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('ooo', 'OpenOffice.org', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('reactos', 'ReactOS', 3, 3, 1, 2, '', '');
	$i->vxSetupRelatedByName('reactos', 'http://www.reactos.org/', 'ReactOS');
$i->vxSetupBoard('darwin', 'Darwin', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('qt', 'QT', 3, 3, 1, 2, '少编程，多创造', 'CODE LESS. CREATE MORE.');
$i->vxSetupBoard('postfix', 'Postfix', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('osdev', 'OSDEV', 3, 3, 1, 2, '操作系统开发研究试验室', 'V2EX');
$i->vxSetupBoard('netbsd', 'NetBSD', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('freebsd', 'FreeBSD', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('openbsd', 'OpenBSD', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('svn', 'Subversion', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('cg', '计算机图形学', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('imagemagick', 'ImageMagick', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('3g', '3G', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('rss', 'RSS', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('samba', 'Samba', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('intype', 'Intype', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('xmpp', 'XMPP', 3, 3, 1, 2, '', "<small>eXtensible Messaging and Presence Protocol</small>");
	$i->vxSetupRelatedByName('xmpp', 'http://www.igniterealtime.org/', 'Ignite Realtime');
	$i->vxSetupRelatedByName('xmpp', 'http://www.jabber.org/', 'Jabber');
	$i->vxSetupRelatedByName('xmpp', 'http://talk.google.com/', 'Google Talk');
	$i->vxSetupRelatedByName('xmpp', 'http://www.xmpp.org/', 'XMPP Standards Foundation');
$i->vxSetupBoard('pageflakes', 'Pageflakes 飞鸽', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('vmware', 'VMware', 3, 3, 1, 2, '', '');
	$i->vxSetupRelatedByName('vmware', 'http://www.vmware.com/', 'VMware.com');
$i->vxSetupBoard('maemo', 'Maemo', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('linux', 'Linux', 3, 3, 1, 2, 'Better Work, Better Play', '');
	$i->vxSetupChannelByName('linux', 'http://www.linux.com/index.rss');
	$i->vxSetupChannelByName('linux', 'http://gnomefiles.org/gnomefiles.xml');
	$i->vxSetupChannelByName('linux', 'http://fridge.ubuntu.com/atom/feed');
	$i->vxSetupChannelByName('linux', 'http://www.howtoforge.com/node/feed');
	$i->vxSetupChannelByName('linux', 'http://blog.linux.org.tw/~jserv/index.xml');
	$i->vxSetupChannelByName('linux', 'http://linuxtoy.org/?feed=rss2');
	$i->vxSetupChannelByName('linux', 'http://ubuntucookbook.com/feed/rss2/');
	$i->vxSetupChannelByName('linux', 'http://www.markshuttleworth.com/feed/');
$i->vxSetupBoard('emacs', 'Emacs', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('fah', 'Folding@Home', 3, 3, 1, 2, '', '');
	$i->vxSetupRelatedByName('fah', 'http://folding.stanford.edu/', 'Folding@Home Distributed Computing');
	$i->vxSetupRelatedByName('fah', 'http://fah-web.stanford.edu/cgi-bin/main.py?qtype=teampage&teamnum=56514', 'Team V2EX');
$i->vxSetupBoard('vi', 'vi', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('php', 'PHP', 3, 3, 1, 2, '', '');
	$i->vxSetupRelatedByName('php', 'http://www.phpmyadmin.net/home_page/index.php', 'phpMyAdmin');
	$i->vxSetupChannelByName('php', 'http://feeds.feedburner.com/ZendDeveloperZone');
$i->vxSetupBoard('nokia', 'Nokia', 3, 3, 1, 2, 'Nokia 手机玩家用家科学家的家', '');
$i->vxSetupBoard('ruby', 'Ruby', 3, 3, 1, 2, 'Happy Hacking!', 'Enjoy Life!');
$i->vxSetupBoard('rexx', 'REXX', 3, 3, 1, 2, 'Happy Hacking!', 'Enjoy Life!');
$i->vxSetupBoard('rebol', 'Rebol', 3, 3, 1, 2, 'Happy Hacking!', 'Enjoy Life!');
$i->vxSetupBoard('smalltalk', 'Smalltalk', 3, 3, 1, 2, 'Happy Hacking!', 'Enjoy Life!');
$i->vxSetupBoard('haskell', 'Haskell', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('sql', 'SQL', 3, 3, 1, 2, 'Happy Hacking!', 'Standard Query Language');
$i->vxSetupBoard('basic', 'Basic', 3, 3, 1, 2, 'Happy Hacking!', 'Enjoy Life!');
$i->vxSetupBoard('eiffel', 'Eiffel', 3, 3, 1, 2, 'Happy Hacking!', 'Enjoy Life!');
$i->vxSetupBoard('perl', 'Perl', 3, 3, 1, 2, "There's more than one way to do it.", 'Get a life!');
	$i->vxSetupRelatedByName('perl', 'http://www.cpan.org/', 'CPAN');
$i->vxSetupBoard('cf', 'ColdFusion', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('erlang', 'Erlang', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('python', 'Python', 3, 3, 1, 2, 'Happy Hacking!', 'Enjoy Life!');
	$i->vxSetupChannelByName('python', 'http://www.python.org/channews.rdf');
$i->vxSetupBoard('java', 'Java', 3, 3, 1, 2, 'Everywhere!', '');
	$i->vxSetupChannelByName('java', 'http://www.javaworld.com/index.xml');
$i->vxSetupBoard('ideas', 'Ideas', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('openid', 'OpenID', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('mediatemple', '(mt) Media Temple', 3, 3, 1, 2, '', '');
	$i->vxSetupChannelByName('mediatemple', 'http://weblog.mediatemple.net/weblog/rss2/');
	$i->vxSetupRelatedByName('mediatemple', 'http://www.mediatemple.net/', '(mt) Media Temple');
$i->vxSetupBoard('pixel', '像素艺术', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('hosting', '寄托梦想', 3, 3, 1, 2, '<span class="tip_i">空间 - 域名 - 服务器 - 合租 | <a href="http://www.dreamhost.com/r.cgi?267137" target="_blank" class="t">DreamHost</a> | <a href="http://www.mediatemple.net/" target="_blank" class="t">(mt) Media Temple</a> | <a href="http://www.bluehost.com/track/livid/text1" class="t">Bluehost</a></span>', '<span class="tip_i"><a href="http://www.dreamhost.com/r.cgi?267137" target="_blank" class="t">DreamHost</a> | <a href="http://www.mediatemple.net/" target="_blank" class="t">(mt) Media Temple</a> | <a href="http://www.bluehost.com/track/livid/text1" class="t">Bluehost</a></span>');
	$i->vxSetupRelatedByName('hosting', 'http://www.dreamhost.com/r.cgi?267137', 'DreamHost');
	$i->vxSetupRelatedByName('hosting', 'http://www.bluehost.com/track/livid/text1', 'Bluehost');
	$i->vxSetupRelatedByName('hosting', 'http://www.mediatemple.net/', '(mt) Media Temple');
	$i->vxSetupChannelByName('hosting', 'http://www.dreamhoststatus.com/rss2/');
	$i->vxSetupChannelByName('hosting', 'http://weblog.mediatemple.net/weblog/rss2/');
$i->vxSetupBoard('startup', '互联网创业', 3, 3, 1, 2, '尽情讨论我们的发财计划吧，哈哈！', '');
	$i->vxSetupChannelByName('startup', 'http://feed.feedsky.com/iblogbeta');
	$i->vxSetupChannelByName('startup', 'http://feeds.feedburner.com/Wappblog');
	$i->vxSetupChannelByName('startup', 'http://www.cnbeta.com/backend.php');
	$i->vxSetupChannelByName('startup', 'http://www.wangtam.com/index.rss');
$i->vxSetupBoard('webdesigner', '网页设计师', 3, 3, 1, 2, '网页设计师的圈子，欢迎你的加入，期待看到你的精彩作品！', '我们有精湛的技术，我们有闪亮的生活。');
	$i->vxSetupChannelByName('webdesigner', 'http://blog.blueidea.com/rss');
	$i->vxSetupChannelByName('webdesigner', 'http://www.seaspace.cn/index.xml');
	$i->vxSetupRelatedByName('webdesigner', 'http://dev.opera.com/', 'Dev Opera');
$i->vxSetupBoard('asimo', 'ASIMO', 3, 3, 1, 2, "", '');
$i->vxSetupBoard('ipod', 'iPod', 3, 3, 1, 2, "What's on your iPod?", '');
$i->vxSetupBoard('psp', 'PlayStation Portable', 3, 3, 1, 2, "", '');
$i->vxSetupBoard('nds', 'Nintendo DS', 3, 3, 1, 2, "", '');
$i->vxSetupBoard('casio', 'CASIO', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('siemens', 'Siemens', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('benq', 'BenQ', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('asus', 'ASUS', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('dell', 'Dell', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('alienware', 'Alienware', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('3dfx', '3DFX', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('nvidia', 'nVIDIA', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('ati', 'ATI', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('intel', 'Intel', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('amd', 'AMD', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('ibm', 'IBM', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('canon', 'Canon', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('nikon', 'Nikon', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('olympus', 'Olympus', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('hp', 'HP', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('nec', 'NEC', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('apple', 'Apple', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('logitech', 'Logitech', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('kodak', 'Kodak', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('gnome', 'GNOME', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('samsung', 'Samsung', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('motorola', 'Motorola', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('seiko', 'SEIKO', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('opengl', 'OpenGL', 3, 3, 1, 2, "", '');
$i->vxSetupBoard('coder', '程序员', 3, 3, 1, 2, "", '');
	$i->vxSetupChannelByName('coder', 'http://feeds.feedburner.com/vitaminmasterfeed');
	$i->vxSetupRelatedByName('coder', 'http://www.sun.com/', 'Sun');
	$i->vxSetupRelatedByName('coder', 'http://www.tigris.org/', 'Tigris.org');
	$i->vxSetupRelatedByName('coder', 'http://www.microsoft.com/', 'Microsoft');
	$i->vxSetupRelatedByName('coder', 'http://msdn.microsoft.com/', 'MSDN');
	$i->vxSetupRelatedByName('coder', 'http://www.codegear.com/', 'CodeGear');
$i->vxSetupBoard('oracle', 'Oracle', 3, 3, 1, 2, "", '');
$i->vxSetupBoard('skype', 'Skype', 3, 3, 1, 2, "", '');
$i->vxSetupBoard('bmw', 'BMW', 3, 3, 1, 2, "", '');
$i->vxSetupBoard('audi', 'Audi', 3, 3, 1, 2, "", '');
$i->vxSetupBoard('citroen', 'CITROEN', 3, 3, 1, 2, "雪铁龙车友会", '');
$i->vxSetupBoard('auto', '车友会', 3, 3, 1, 2, '', '');
	$i->vxSetupRelatedByName('auto', 'http://www.autohome.com.cn/', '汽车之家');
$i->vxSetupBoard('ferrari', 'Ferrari', 3, 3, 1, 2, "", '');
$i->vxSetupBoard('astonmartin', 'Aston Martin', 3, 3, 1, 2, "", '');
$i->vxSetupBoard('maserati', 'Maserati', 3, 3, 1, 2, "", '');
$i->vxSetupBoard('chevrolet', 'Chevrolet', 3, 3, 1, 2, "", '');
$i->vxSetupBoard('volkswagen', 'Volkswagen', 3, 3, 1, 2, "", '');
$i->vxSetupBoard('toyota', 'TOYOTA', 3, 3, 1, 2, "", '');
$i->vxSetupBoard('lexus', 'LEXUS', 3, 3, 1, 2, "", '');
	$i->vxSetupRelatedByName('lexus', 'http://www.lexus.com/', 'Lexus.com Official USA Site');
$i->vxSetupBoard('nissan', 'NISSAN', 3, 3, 1, 2, "", '');
$i->vxSetupBoard('mitsubishi', 'Mitsubishi', 3, 3, 1, 2, "", '');
$i->vxSetupBoard('hummer', 'HUMMER', 3, 3, 1, 2, "", '');
$i->vxSetupBoard('ford', 'Ford', 3, 3, 1, 2, "", '');
$i->vxSetupBoard('volvo', 'Volvo', 3, 3, 1, 2, "", '');
$i->vxSetupBoard('landrover', 'Land Rover', 3, 3, 1, 2, "", '');
$i->vxSetupBoard('jaguar', 'Jaguar', 3, 3, 1, 2, "", '');
$i->vxSetupBoard('cadillac', 'Cadillac', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('chrysler', 'Chrysler', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('honda', 'Honda', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('jeep', 'JEEP', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('subaru', 'Subaru', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('ps3', 'PlayStation 3', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('wii', 'Wii', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('xbox360', 'Xbox 360', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('xbox', 'Xbox', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('simcity', 'SimCity', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('diablo', 'Diablo', 3, 3, 1, 2, '', '');
$i->vxSetupBoard('wow', 'World of Warcraft', 3, 3, 1, 2, '魔兽世界', '');
$i->vxSetupBoard('warcraft', 'Warcraft', 3, 3, 1, 2, '魔兽争霸', '');
$i->vxSetupBoard('starcraft', 'Starcraft', 3, 3, 1, 2, '星际争霸', '');
$i->vxSetupBoard('jetty', 'Jetty', 3, 3, 1, 2, "", '');
$i->vxSetupBoard('liferay', 'Liferay', 3, 3, 1, 2, "", '');
$i->vxSetupBoard('zend', 'Zend', 3, 3, 1, 2, "Zend 产品讨论专区", '');
	$i->vxSetupRelatedByName('zend', 'http://www.zend.com/', 'Zend.com');
	$i->vxSetupRelatedByName('zend', 'http://framework.zend.com/', 'Zend Framework');
$i->vxSetupBoard('eclipse', 'Eclipse', 3, 3, 1, 2, "", '');
	$i->vxSetupRelatedByName('eclipse', 'http://www.eclipse.org/', 'Eclipse.org');
	$i->vxSetupChannelByName('eclipse', 'http://www.eclipse.org/home/eclipseinthenews.rss');
$i->vxSetupBoard('xml', 'XML', 3, 3, 1, 2, "", '');
$i->vxSetupBoard('xslt', 'XSLT', 3, 3, 1, 2, "", '');
$i->vxSetupBoard('syncml', 'SyncML', 3, 3, 1, 2, "", '');

// thegraywaste
$i->vxSetupBoard('vivi', 'vivi', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('livid', 'Livid', 4, 4, 1, 2, 'All About Livid', 'Livid is My Name');
	$i->vxSetupChannelByName('livid', 'http://www.livid.cn/rss.php');
	$i->vxSetupChannelByName('livid', 'http://moon.livid.cn/?feed=rss2');
	$i->vxSetupRelatedByName('livid', 'http://www.livid.cn/', 'LIVID & REV');
	$i->vxSetupRelatedByName('livid', 'http://www.lividot.org/', 'Lividot');
	$i->vxSetupRelatedByName('livid', 'http://www.lividict.org/', 'Lividict');
	$i->vxSetupRelatedByName('livid', 'http://www.epeta.org/', 'ePeta');
$i->vxSetupBoard('elfe', 'Elfe', 4, 4, 1, 2, '', '');
	$i->vxSetupChannelByName('elfe', 'http://elfe.cn/?feed=rss2');
	$i->vxSetupRelatedByName('elfe', 'http://www.elfe.cn/', '阳光艾芙');
$i->vxSetupBoard('sai', 'SAi', 4, 4, 1, 2, '', '');
	$i->vxSetupRelatedByName('sai', 'http://blog.orzotl.com/?1', 'Nothing but SAi');
	$i->vxSetupChannelByName('sai', 'http://blog.orzotl.com/1/action_rss.html');
$i->vxSetupBoard('harukimurakami', '村上春树', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('jeanpaulsartre', 'Jean-Paul Sartre', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('m2099', 'm2099', 4, 4, 1, 2, '', '');
	$i->vxSetupRelatedByName('m2099', 'http://www.m2099.com/', 'm2099');
$i->vxSetupBoard('triangle', '三角地', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('fish-culture', '养鱼', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('8cups', '每天要喝八杯水', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('patriotism', '爱国主义教育基地', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('epeta', 'ePeta', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('nintendo', 'Nintendo', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('microsoft', 'Microsoft', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('sony', 'SONY', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('picturestory', '图片的故事', 4, 4, 1, 2, '', '');
	$i->vxSetupRelatedByName('picturestory', 'http://www.flickr.com/', 'Flickr');
$i->vxSetupBoard('sega', 'SEGA', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('capcom', 'CAPCOM', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('konami', 'KONAMI', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('blizzard', 'Blizzard', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('snk', 'SNK', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('civ', 'Civilization', 4, 4, 1, 2, '', '');
	$i->vxSetupRelatedByName('civ', 'http://www.2kgames.com/civ4/home.htm', 'Civilization IV');
$i->vxSetupBoard('boyfriend', '找男友', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('girlfriend', '找女友', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('boy', '男生话题', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('girl', '女生话题', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('man', '男人话题', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('lady', '女人话题', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('gonewiththewind', '飘', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('punk', '我们坐车不买票', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('paranoid', '偏执狂', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('pointless', '无要点', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('qna', '问与答', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('8r8c', '八荣八耻', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('english', 'English', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('branding', '品牌建立', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('1kg', '多背一公斤', 4, 4, 1, 2, '', '');
	$i->vxSetupRelatedByName('1kg', 'http://www.1kg.cn/', '多背一公斤');
$i->vxSetupBoard('blogbus', 'BlogBus', 4, 4, 1, 2, '', '');
	$i->vxSetupChannelByName('blogbus', 'http://hengge.blogbus.com/index.rdf');
	$i->vxSetupChannelByName('blogbus', 'http://blogbus.blogbus.com/index.rdf');
	$i->vxSetupChannelByName('blogbus', 'http://ittalks.blogbus.com/index.rdf');
	$i->vxSetupRelatedByName('blogbus', 'http://www.blogbus.com/', 'BlogBus');
$i->vxSetupBoard('blogger', 'Blogger', 4, 4, 1, 2, '', '');
	$i->vxSetupChannelByName('blogger', 'http://www.mozine.cn/feed/rss2/');
	$i->vxSetupChannelByName('blogger', 'http://memedia.cn/feed/');
	$i->vxSetupChannelByName('blogger', 'http://feeds.feedburner.com/TechCrunch');
	$i->vxSetupChannelByName('blogger', 'http://feeds.feedburner.com/PoseShow');
	$i->vxSetupChannelByName('blogger', 'http://feeds.feedburner.com/PlayinWithIt');
	$i->vxSetupChannelByName('blogger', 'http://feeds.feedburner.com/boingboing/iBag');
	$i->vxSetupChannelByName('blogger', 'http://feeds.feedburner.com/wangxiaofeng');
	$i->vxSetupChannelByName('blogger', 'http://www.caobian.info/?feed=rss2');
$i->vxSetupBoard('ecshop', 'ECShop', 4, 4, 1, 2, '', '');
	$i->vxSetupRelatedByName('ecshop', 'http://bbs.ecshop.com/', 'ECShop 支持论坛');
	$i->vxSetupRelatedByName('ecshop', 'http://www.ecshop.com/', 'ECShop 官方网站');
$i->vxSetupBoard('story', '我们一起讲故事', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('fairytale', '童话', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('human', '人之初', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('digg', 'digg', 4, 4, 1, 2, '', '');
	$i->vxSetupRelatedByName('digg', 'http://www.digg.com/', 'digg');
	$i->vxSetupChannelByName('digg', 'http://www.digg.com/rss/containertechnology.xml');
$i->vxSetupBoard('verycd', 'VeryCD', 4, 4, 1, 2, '', '');
	$i->vxSetupRelatedByName('verycd', 'http://www.verycd.com/', 'VeryCD');
	$i->vxSetupChannelByName('verycd', 'http://blog.verycd.com/dash/req=syndicate');
	$i->vxSetupChannelByName('verycd', 'http://www.xdanger.com/feed/');
$i->vxSetupBoard('douban', '豆瓣', 4, 4, 1, 2, '', '');
	$i->vxSetupRelatedByName('douban', 'http://www.douban.com/', '豆瓣');
	$i->vxSetupChannelByName('douban', 'http://www.douban.com/feed/review/book');
	$i->vxSetupChannelByName('douban', 'http://www.douban.com/feed/review/movie');
	$i->vxSetupChannelByName('douban', 'http://www.douban.com/feed/review/music');
	$i->vxSetupChannelByName('douban', 'http://www.douban.com/feed/group/v2ex/discussion');
	$i->vxSetupChannelByName('douban', 'http://blog.douban.com/feed/');
	$i->vxSetupChannelByName('douban', 'http://weekly.douban.org/index.php/feed/');
$i->vxSetupBoard('movie', '爱看电影', 4, 4, 1, 2, '用一百分钟切换到别人的生活', '');
	$i->vxSetupRelatedByName('movie', 'http://www.youtube.com/', 'YouTube');
$i->vxSetupBoard('superstar', '明星八卦', 4, 4, 1, 2, '', '');
	$i->vxSetupChannelByName('superstar', 'http://blog.sina.com.cn/myblog/index_rss.php?uid=1190363061');
	$i->vxSetupChannelByName('superstar', 'http://blog.sina.com.cn/myblog/index_rss.php?uid=1191258123');
	$i->vxSetupChannelByName('superstar', 'http://blog.sina.com.cn/myblog/index_rss.php?uid=1188552450');
	$i->vxSetupChannelByName('superstar', 'http://blog.sina.com.cn/myblog/index_rss.php?uid=1173538795');
	$i->vxSetupChannelByName('superstar', 'http://blog.sina.com.cn/myblog/index_rss.php?uid=1210603055');
	$i->vxSetupChannelByName('superstar', 'http://blog.sina.com.cn/myblog/index_rss.php?uid=1173544654');

$i->vxSetupBoard('cnbloggercon', '中文网志年会', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('wikipedia', 'Wikipedia', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('psychology', '心理学', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('noexam', '拒绝高考', 4, 4, 1, 2, '拒绝高考是我们的选择，也是我们的权利，恐怕更是我们的必然！', '');
$i->vxSetupBoard('getlaidtonight', '出活日当午', 4, 4, 1, 2, 'All we need is to get laid tonight.', 'But there is still hope.');
$i->vxSetupBoard('kijiji', 'Kijiji', 4, 4, 1, 2, '分类改变生活', '');
	$i->vxSetupChannelByName('kijiji', 'http://feeds.feedburner.com/wangjianshuo');
	$i->vxSetupChannelByName('kijiji', 'http://feeds.feedburner.com/livid');
	$i->vxSetupChannelByName('kijiji', 'http://www.bumo.cn/blog/feed/');
	$i->vxSetupChannelByName('kijiji', 'http://bulaoge.com/rss2.blg?uid=2');
	$i->vxSetupChannelByName('kijiji', 'http://echotao123.spaces.live.com/feed.rss');
	$i->vxSetupChannelByName('kijiji', 'http://feed.hejiachen.com/');
	$i->vxSetupChannelByName('kijiji', 'http://www.sundengjia.com/wordpress/feed/');
	$i->vxSetupChannelByName('kijiji', 'http://www.zhuhequn.com/?feed=rss2');
	$i->vxSetupChannelByName('kijiji', 'http://blog.kijiji.com.cn/index.xml');
	$i->vxSetupChannelByName('kijiji', 'http://feeds.feedburner.com/adolfpan');
	$i->vxSetupChannelByName('kijiji', 'http://spaces.msn.com/titi1017/feed.rss');
	$i->vxSetupChannelByName('kijiji', 'http://spaces.msn.com/emmetxu/feed.rss');
	$i->vxSetupChannelByName('kijiji', 'http://www.zishu.cn/blogrss1.asp');
	$i->vxSetupChannelByName('kijiji', 'http://home.wangjianshuo.com/index.xml');
	$i->vxSetupChannelByName('kijiji', 'http://www.shiweitao.cn/?feed=rss2');
	$i->vxSetupChannelByName('kijiji', 'http://www.yymeng.com/?feed=rss2');
	$i->vxSetupChannelByName('kijiji', 'http://feeds.feedburner.com/kjj-fuyuko');
	$i->vxSetupChannelByName('kijiji', 'http://hi.baidu.com/xhengheng/rss');
	$i->vxSetupRelatedByName('kijiji', 'http://www.kijiji.cn/', 'Kijiji.cn');
	$i->vxSetupRelatedByName('kijiji', 'http://www.kijiji.co.jp/', 'Kijiji.jp');
	$i->vxSetupRelatedByName('kijiji', 'http://www.kijiji.co.kr/', 'Kijiji.kr');
	$i->vxSetupRelatedByName('kijiji', 'http://www.kijiji.de/', 'Kijiji.de');
	$i->vxSetupRelatedByName('kijiji', 'http://www.kijiji.fr/', 'Kijiji.fr');
	$i->vxSetupRelatedByName('kijiji', 'http://www.kijiji.ca/', 'Kijiji.ca');
	$i->vxSetupRelatedByName('kijiji', 'http://www.kijiji.it/', 'Kijiji.it');
	$i->vxSetupRelatedByName('kijiji', 'http://www.kijiji.com/', 'Kijiji.com');
	$i->vxSetupRelatedByName('kijiji', 'http://www.kijiji.tw/', 'Kijiji.tw');
	$i->vxSetupRelatedByName('kijiji', 'http://www.gumtree.com/', 'Gumtree');
	$i->vxSetupRelatedByName('kijiji', 'http://www.marktplaats.nl/', 'Marktplaats');
	$i->vxSetupRelatedByName('kijiji', 'http://www.kijiji.in/', 'Kijiji.in');
	$i->vxSetupRelatedByName('kijiji', 'http://www.kijiji.ch/', 'Kijiji.ch');
	$i->vxSetupRelatedByName('kijiji', 'http://www.kijiji.at/', 'Kijiji.at');
	$i->vxSetupRelatedByName('kijiji', 'http://www.slando.ru/', 'Slando');
	$i->vxSetupRelatedByName('kijiji', 'http://www.intoko.com.tr/', 'Intoko');
	$i->vxSetupRelatedByName('kijiji', 'http://www.loquo.com/', 'Loquo');
$i->vxSetupBoard('adsense', 'Google AdSense', 4, 4, 1, 2, '', '');
	$i->vxSetupRelatedByName('adsense', 'http://www.google.com/adsense', 'Google AdSense');
$i->vxSetupBoard('adwords', 'Google AdWords', 4, 4, 1, 2, '', '');
	$i->vxSetupRelatedByName('adwords', 'http://www.google.com/adwords', 'Google AdWords');
$i->vxSetupBoard('ebay', 'eBay', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('google', 'Google', 4, 4, 1, 2, '', '');
	$i->vxSetupChannelByName('google', 'http://googlechinablog.com/atom.xml');
	$i->vxSetupChannelByName('google', 'http://code.google.com/feeds/updates.xml');
	$i->vxSetupChannelByName('google', 'http://googleblog.blogspot.com/atom.xml');
	$i->vxSetupChannelByName('google', 'http://googlewebmastercentral.blogspot.com/atom.xml');
	$i->vxSetupChannelByName('google', 'http://googlebase.blogspot.com/atom.xml');
	$i->vxSetupRelatedByName('google', 'http://www.google.com/reader', 'Reader');
	$i->vxSetupRelatedByName('google', 'http://pages.google.com/', 'Pages');
	$i->vxSetupRelatedByName('google', 'http://maps.google.com/', 'Maps');
	$i->vxSetupRelatedByName('google', 'http://www.google.com/base', 'Base');
	$i->vxSetupRelatedByName('google', 'http://www.writely.com/', 'Writely');
	$i->vxSetupRelatedByName('google', 'http://www.orkut.com/', 'Orkut');
$i->vxSetupBoard('myspace', 'MySpace', 4, 4, 1, 2, 'We all love Tom!', '');
$i->vxSetupBoard('baidu', '百度', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('yahoo', 'Yahoo!', 4, 4, 1, 2, '', '');
	$i->vxSetupRelatedByName('yahoo', 'http://www.yahoo.com.cn/', 'Y! China');
	$i->vxSetupRelatedByName('yahoo', 'http://www.yahoo.com/', 'Yahoo!');
	$i->vxSetupChannelByName('yahoo', 'http://ysearchblog.cn/index.xml');
$i->vxSetupBoard('qq', 'QQ', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('msn', 'MSN Spaces', 4, 4, 1, 2, '', '');
	$i->vxSetupChannelByName('msn', 'http://feeds.feedburner.com/poisoned');
$i->vxSetupBoard('lang', '学外语', 4, 4, 1, 2, '为了更好的沟通', 'For better communications.');
$i->vxSetupBoard('health', '玩电脑有害健康', 4, 4, 1, 2, '关掉你的电脑，多去大自然呼吸新鲜空气吧！', '每天都要减少那些不必要的计算机使用。');
$i->vxSetupBoard('nodrug', '吸毒有害健康', 4, 4, 1, 2, '', 'Marijuana Joint Hemp Cannabis Heroin Cocaine Hallucinogen');
$i->vxSetupBoard('wc2006', '2006 德国世界杯', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('wc2010', '2010 南非世界杯', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('doha2006', '2006 多哈亚运会', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('homo', 'homo', 4, 4, 1, 2, '我们的爱', 'all my love');
$i->vxSetupBoard('homme', 'homme', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('play', '努力工作拼命玩', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('photo', '摄影爱好者', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('mood', '你今天心情好吗？', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('math', '数学', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('physics', '物理', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('chemistry', '化学', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('delicious', '美食 . 好酒 . 生活', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('travel', '龙门客栈', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('dnd', '龙与地下城', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('bulaoge', '不老歌', 4, 4, 1, 2, '', '');
	$i->vxSetupRelatedByName('bulaoge', 'http://www.bulaoge.com/', '不老歌');
	$i->vxSetupChannelByName('bulaoge', 'http://bulaoge.com/rss2.blg?uid=2');
	$i->vxSetupChannelByName('bulaoge', 'http://bulaoge.com/rss2.blg?uid=939');
$i->vxSetupBoard('news', 'NeWs', 4, 4, 1, 2, 'News for nerds, stuff that matters', '');
$i->vxSetupBoard('galaxy', '银河系漫游指南', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('reading', '爱读书', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('20s', '二十年代', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('30s', '三十年代', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('40s', '四十年代', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('50s', '五十年代', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('60s', '六十年代', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('70s', '七十年代', 4, 4, 1, 2, '', '');
	$i->vxSetupRelatedByName('70s', 'http://www.houhai.com/', '后海');
	$i->vxSetupRelatedByName('70s', 'http://www.i70s.com/', '柒零派');
$i->vxSetupBoard('80s', '八十年代', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('90s', '九十年代', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('middle-year-crisis', '中年危机', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('2006', '2006', 4, 4, 1, 2, '2006', '');
$i->vxSetupBoard('2007', '2007', 4, 4, 1, 2, '2007', '');
$i->vxSetupBoard('electronic-guitar', "电吉他", 4, 4, 1, 2, '', '');
$i->vxSetupBoard('ynsdfz', '云南师大附中', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('gxsdfz', '广西师大附中', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('gezhi', '上海格致中学', 4, 4, 1, 2, '', '');
$i->vxSetupBoard('lomo', 'LOMO', 4, 4, 1, 2, '.: 我们的乐摸生活 :.', '');
$i->vxSetupBoard('blacksmith', 'V2EX Blacksmith', 4, 4, 1, 2, 'For V2EX core hackers only', 'V2EX | software for internet');
$i->vxSetupBoard('v2ex', 'V2EX', 4, 4, 1, 2, 'Latest from V2EX', 'V2EX | software for internet');
	$i->vxSetupRelatedByName('v2ex', 'http://www.v2ex.org/', 'V2EX Blog');
	$i->vxSetupChannelByName('v2ex', 'http://v2ex.org/?feed=rss2');
	$i->vxSetupChannelByName('v2ex', 'http://www.clockwork.cn/?feed=rss2');
	$i->vxSetupChannelByName('v2ex', 'http://www.v2ex.com/feed/v2ex.rss');
	$i->vxSetupRelatedByName('v2ex', 'http://io.v2ex.com/', 'V2EX::IO');
$i->vxSetupBoard('io', 'IO', 4, 4, 1, 2, '', '');
	$i->vxSetupRelatedByName('io', 'http://io.v2ex.com/', 'V2EX::IO');
$i->vxSetupBoard('autistic', '自言自语', 4, 4, 1, 2, '在这里我们自己和自己玩，不欢迎别人的回帖。', '');
$i->vxSetupBoard('show', 'SHOW', 4, 4, 1, 2, '欢迎你在这里贴自己的照片！', '');
$i->vxSetupBoard('50ren', '50人杂志', 4, 4, 1, 2, '一本神奇杂志的诞生，需要你的好奇……', '你是否跟我一样，正在关注并创造着《50人》？');
	$i->vxSetupChannelByName('50ren', 'http://www.uuzone.com/rss/blog_category/yezi/22563.xml');
$i->vxSetupBoard('skypacer', '尔曼', 4, 4, 1, 2, '尔曼，外号用了10年，超重了10年，奋斗目标是外号用 100 年，明年不超重', '');
	$i->vxSetupChannelByName('skypacer', 'http://news.baidu.com/n?cmd=4&class=civilnews&pn=1&tn=rss');
	$i->vxSetupChannelByName('skypacer', 'http://news.baidu.com/n?cmd=4&class=internews&pn=1&tn=rss');
	$i->vxSetupChannelByName('skypacer', 'http://news.baidu.com/n?cmd=4&class=sportnews&pn=1&tn=rss');
	$i->vxSetupChannelByName('skypacer', 'http://news.baidu.com/n?cmd=4&class=enternews&pn=1&tn=rss');
	$i->vxSetupChannelByName('skypacer', 'http://news.baidu.com/n?cmd=4&class=internet&pn=1&tn=rss');
	$i->vxSetupChannelByName('skypacer', 'http://news.baidu.com/n?cmd=4&class=technnews&pn=1&tn=rss');
	$i->vxSetupChannelByName('skypacer', 'http://news.baidu.com/n?cmd=4&class=finannews&pn=1&tn=rss');
	$i->vxSetupChannelByName('skypacer', 'http://news.baidu.com/n?cmd=4&class=socianews&pn=1&tn=rss');
	$i->vxSetupChannelByName('skypacer', 'http://rss.xinhuanet.com/rss/native.xml');
	$i->vxSetupChannelByName('skypacer', 'http://rss.xinhuanet.com/rss/world.xml');
	$i->vxSetupChannelByName('skypacer', 'http://rss.xinhuanet.com/rss/fortune.xml');
	$i->vxSetupChannelByName('skypacer', 'http://rss.xinhuanet.com/rss/sports.xml');
	$i->vxSetupChannelByName('skypacer', 'http://rss.xinhuanet.com/rss/mil.xml');
	$i->vxSetupChannelByName('skypacer', 'http://rss.xinhuanet.com/rss/it.xml');
	$i->vxSetupChannelByName('skypacer', 'http://rss.xinhuanet.com/rss/science.xml');
	$i->vxSetupChannelByName('skypacer', 'http://rss.xinhuanet.com/rss/ent.xml');
	$i->vxSetupChannelByName('skypacer', 'http://rss.xinhuanet.com/rss/edu.xml');
	$i->vxSetupChannelByName('skypacer', 'http://rss.xinhuanet.com/rss/photos.xml');
	$i->vxSetupChannelByName('skypacer', 'http://rss.xinhuanet.com/rss/legal.xml');
	$i->vxSetupChannelByName('skypacer', 'http://www.365key.com/rss/keso/');
	$i->vxSetupChannelByName('skypacer', 'http://blog.guykawasaki.com/rss.xml');
	$i->vxSetupChannelByName('skypacer', 'http://blog.verycd.com/dash/req=syndicate');
	$i->vxSetupChannelByName('skypacer', 'http://divx.thu.cn/rss/rss_feed.php');
	$i->vxSetupChannelByName('skypacer', 'http://www.donews.net/mainfeed.aspx');
	$i->vxSetupChannelByName('skypacer', 'http://www.donews.com/rss.xml');
	$i->vxSetupChannelByName('skypacer', 'http://www.donews.com/GroupFeed.aspx?G=5B1D5178-138D-4D42-B370-5198FDF5AF34');
	$i->vxSetupChannelByName('skypacer', 'http://www.donews.com/GroupFeed.aspx?G=481BCC18-7F72-40E3-953E-5BB6545B3828');
	$i->vxSetupChannelByName('skypacer', 'http://www.donews.com/GroupFeed.aspx?G=E10F17D2-05A1-4724-B488-E0B29E4C0E94');
	$i->vxSetupChannelByName('skypacer', 'http://www.donews.com/GroupFeed.aspx?G=EE56026E-534D-4B37-BA7F-19AE41B09904');
	$i->vxSetupChannelByName('skypacer', 'http://www.flypig.org/index.xml');
	$i->vxSetupChannelByName('skypacer', 'http://googlechinablog.com/atom.xml');
	$i->vxSetupChannelByName('skypacer', 'http://google.blognewschannel.com/index.php/feed/');
	$i->vxSetupChannelByName('skypacer', 'http://blog.podlook.com/rss.aspx');
	$i->vxSetupChannelByName('skypacer', 'http://feeds.feedburner.com/laobaisBlog');
	$i->vxSetupChannelByName('skypacer', 'http://www.seovista.com/rss.xml');
	$i->vxSetupChannelByName('skypacer', 'http://electricpulp.com/blog/feed/atom/');
	$i->vxSetupChannelByName('skypacer', 'http://home.wangjianshuo.com/index.xml');
	$i->vxSetupChannelByName('skypacer', 'http://spaces.msn.com/members/mranti/feed.rss');
	$i->vxSetupChannelByName('skypacer', 'http://lydon.yculblog.com/rss.xml');
	$i->vxSetupChannelByName('skypacer', 'http://blog.donews.com/keso/rss.aspx');
	$i->vxSetupChannelByName('skypacer', 'http://podcast.kijiji.com.cn/podcast.xml');
	$i->vxSetupChannelByName('skypacer', 'http://blog.donews.com/liuren/Rss.aspx');
	$i->vxSetupChannelByName('skypacer', 'http://blog.donews.com/maitian99/rss.aspx');
	$i->vxSetupChannelByName('skypacer', 'http://feeds.feedburner.com/wangjianshuo');
	$i->vxSetupChannelByName('skypacer', 'http://blog.donews.com/chinabright/rss.aspx');
	$i->vxSetupChannelByName('skypacer', 'http://rss.sina.com.cn/news/marquee/ddt.xml');
	$i->vxSetupChannelByName('skypacer', 'http://www.mpdaogou.com/Discount/Emporium/rss.xml');
	$i->vxSetupChannelByName('skypacer', 'http://www.weamax.com/xml/rss_weamax_news.php');
	$i->vxSetupChannelByName('skypacer', 'http://blog.donews.com/bingshu/Rss.aspx');
	$i->vxSetupChannelByName('skypacer', 'http://www.luoyonghao.net/Blog/RssHandler.ashx?id=laoluo');
	$i->vxSetupChannelByName('skypacer', 'http://blog.kijiji.com.cn/index.xml');
	$i->vxSetupChannelByName('skypacer', 'http://www.lifepop.com/asp/rss.asp?domain=yyse');
	$i->vxSetupChannelByName('skypacer', 'http://zhaomu.blog.sohu.com/rss');
	$i->vxSetupChannelByName('skypacer', 'http://feeds.feedburner.com/livid');
	$i->vxSetupChannelByName('skypacer', 'http://www.hi-pda.com/drupal//?q=node/feed');
	$i->vxSetupChannelByName('skypacer', 'http://www.bumo.cn/blog/feed/');
	$i->vxSetupChannelByName('skypacer', 'http://www.boingboing.net/index.rdf');
?>