<?php
class Weblog {
	public function __construct($weblog_id) {
		$sql = "SELECT blg_id, blg_uid, blg_name, blg_title, blg_description, blg_portrait, blg_theme, blg_entries, blg_comments, blg_created, blg_lastupdated, blg_lastbuilt, usr_id, usr_nick, usr_gender, usr_portrait, usr_created, usr_brief FROM babel_weblog, babel_user WHERE blg_uid = usr_id AND blg_id = {$weblog_id}";
		$rs = mysql_query($sql);
		if (mysql_num_rows($rs) == 1) {
			$this->weblog = true;
			$_weblog = mysql_fetch_array($rs);
			$this->blg_id = intval($_weblog['blg_id']);
			$this->blg_uid = intval($_weblog['blg_uid']);
			$this->blg_name = $_weblog['blg_name'];
			$this->blg_title = $_weblog['blg_title'];
			$this->blg_description = $_weblog['blg_description'];
			$this->blg_portrait = $_weblog['blg_portrait'];
			$this->blg_theme = $_weblog['blg_theme'];
			$this->blg_entries = intval($_weblog['blg_entries']);
			$this->blg_comments = intval($_weblog['blg_comments']);
			$this->blg_created = intval($_weblog['blg_created']);
			$this->blg_lastupdated = intval($_weblog['blg_lastupdated']);
			$this->blg_lastbuilt = intval($_weblog['blg_lastbuilt']);
			$this->usr_nick = $_weblog['usr_nick'];
			mysql_free_result($rs);
			unset($_weblog);
		} else {
			$this->weblog = false;
		}
	}
	
	public function __destruct() {
	}
	
	public function vxAddBuild() {
		$sql = "UPDATE babel_weblog SET blg_builds = blg_builds + 1 WHERE blg_id = {$this->blg_id}";
		mysql_unbuffered_query($sql);
	}
	
	public function vxTouchBuild() {
		$now = time();
		$sql = "UPDATE babel_weblog SET blg_lastbuilt = {$now} WHERE blg_id = {$this->blg_id}";
		mysql_unbuffered_query($sql);
	}
	
	public static function vxBuild($user_id, $weblog_id) {
		$start = microtime(true);
		$Weblog = new Weblog($weblog_id);
		if (($start - $Weblog->blg_lastbuilt) < 100) {
			$_SESSION['babel_message_weblog'] = _vo_ico_silk('clock') . ' 距离上次构建时间尚不足 100 秒，本次操作取消，请等待 ' . (100 - intval($start - $Weblog->blg_lastbuilt)) . ' 秒之后再试验';
		} else {
			$bytes = 0;
			$files = 0;
			
			/* check user home directory */
			$usr_dir = BABEL_WEBLOG_PREFIX . '/htdocs/' . $Weblog->blg_name;
			if (!file_exists($usr_dir)) {
				mkdir($usr_dir);
			}
			$file_index = $usr_dir . '/index.html';
			$file_style = $usr_dir . '/style.css';
			
			$s = new Smarty();
			$s->template_dir = BABEL_PREFIX . '/res/weblog/themes/' . $Weblog->blg_theme;
			$s->compile_dir = BABEL_PREFIX . '/tplc';
			$s->cache_dir = BABEL_PREFIX . '/cache/smarty';
			$s->config_dir = BABEL_PREFIX . '/cfg';
			
			$s->assign('site_theme', $Weblog->blg_theme);
			$s->assign('site_static', BABEL_WEBLOG_SITE_STATIC);
			$s->assign('site_weblog_root', 'http://' . BABEL_WEBLOG_SITE . '/' . $Weblog->blg_name . '/');
			$s->assign('site_title', make_plaintext($Weblog->blg_title));
			$s->assign('built', date('Y-n-j G:i:s T', time()));
			$s->assign('user_nick', $Weblog->usr_nick);
			$o_index = $s->fetch('index.smarty');
			$files++;
			$bytes += file_put_contents($file_index, $o_index);
			$s->left_delimiter = '[';
			$s->right_delimiter = ']';
			$o_style = $s->fetch('style.smarty');
			$files++;
			$bytes += file_put_contents($file_style, $o_style);
			$s->left_delimiter = '{';
			$s->right_delimiter = '}';
			$Weblog->vxAddBuild();
			$Weblog->vxTouchBuild();
			$end = microtime(true);
			$elapsed = $end - $start;
			$_SESSION['babel_message_weblog'] = _vo_ico_silk('tick') . ' 博客网站 ' . make_plaintext($Weblog->blg_title) . ' 重新构建成功，' . $files . ' 个文件共写入了 ' . $bytes . ' 字节，共耗时 <small>' . $elapsed . '</small> 秒，<a href="http://' . BABEL_WEBLOG_SITE . '/' . $Weblog->blg_title . '" class="t" target="_blank">现在查看</a> <img src="/img/ext.png" align="absmiddle" />';
		}
	}
}
?>