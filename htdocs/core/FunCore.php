<?php
/* Project Babel
*  Author: Livid Torvalds
*  File: /htdocs/core/FunCore.php
*  Usage: Fun Class
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

if (@V2EX_BABEL != 1) {
	die('<strong>Project Babel</strong><br /><br />Made by <a href="http://www.v2ex.com/">V2EX</a> | software for internet');
}

/* S Fun class */

class Fun {
	var $elements;
	var $pointer;
	var $o;

	public function __construct() {
		$this->elements = array(
'屍毒',
'御宅氣',
'高手高手高高手',
'雜魚',
'高頻雜訊',
'黑暗',
'死靈怨影',
'光',
'性慾',
'心中的翡翠森林',
'心中的斷背山',
'大宇宙的意志',
'燃燒的小宇宙',
'反物質',
'三鋰水晶',
'空間扭曲',
'時空斷層',
'微型黑洞',
'微波雷射',
'化屍水',
'王水',
'海水',
'一江春水',
'花痴',
'夢',
'烈日之心',
'友愛',
'愛心光束',
'命運的相逢',
'巨大蘿蔔',
'高張力鋼',
'米諾夫斯基粒子',
'G3毒氣',
'三倍速',
'彈幕',
'沙林毒氣',
'新人類',
'恨',
'鬼東西',
'歌聲',
'腦殘',
'墮落',
'飢渴',
'戀童癖',
'自戀',
'戀父情結',
'戀母情結',
'戀兄情結',
'戀妹情結',
'愛','愛','愛','愛',
'沒創意',
'髒空氣',
'不良思想',
'反動思想',
'細肩帶小女孩不加辣',
'細肩帶小男孩不加辣',
'渣渣',
'成為豆腐的覺悟',
'撞豆腐自殺的勇氣',
'被受害人折斷的決心',
'義理巧克力',
'星之雨',
'腦麻',
'變態',
'嘴砲',
'信念',
'微妙',
'莫名奇妙',
'巨大怪獸',
'人體暖爐',
'智慧',
'天然呆',
'生命之水',
'天邊一朵雲',
'糟糕',
'心機',
'超合金',
'乙醯膽鹼',
'氫氟酸',
'絨毛',
'碎碎念',
'怨念',
'宿便','宿便',
'毒電波','毒電波','毒電波','毒電波',
'正義之心',
'腦漿',
'膿','膿','膿',
'海之冰',
'狗血',
'核子反應原料',
'反應爐冷卻水',
'高性能炸藥',
'對艦大型雷爆彈',
'國造六六火箭彈',
'超音波',
'觀世音',
'天下第一舉世無雙絕對無敵真正非常超越超級震古鑠今空前絕後刀槍不入無堅不摧無所不能好厲害',
'謎'
		);
		
		$this->pointer = 0;
		$this->o = array();
	}

	public function vxGetComponents($nick) {
		$input_string = strtoupper($nick);
		$input_hash = md5($input_string);

		$hase_length = strlen($input_hash);
		$chunks = str_split($input_hash,2);

		$this->o['s'] = $this->vxGetStory($chunks, $nick);
		
		$total_quantity = 0;
		for ($i=0; $i < count($chunks); $i+=2) {
			$current_component = $this->vxGetElement("0x{$chunks[$i]}");
			eval("\$current_quantity=0x{$chunks[$i+1]};");
			$current_quantity*=$current_quantity*$current_quantity;
			$total_quantity+=$current_quantity;
			
			if (isset($elist[$current_component])) {
				$elist[$current_component] += $current_quantity;
			} else {
				$elist[$current_component] = $current_quantity;
			}
		}
		arsort($elist);
		
		$this->o['c'] = array();
		foreach($elist as $k => $v){
			$percent=number_format( 100*$elist[$k]/$total_quantity, 2);
			if( ereg('0.0[0-9]',$percent) ) continue;
			else{
				$this->o['c'][] = "{$k}: {$percent}%";
			}
		}
		return $this->o;
	}
	
	private function vxGetElement($id) {
		if (!is_numeric($id)) return 'Error!';
		else {
			eval("\$this->pointer+=$id;");
			$this->pointer%=count($this->elements);
			return $this->elements[$this->pointer];
		}
	}
	
	public function vxGetStory($chunks, $input_string) {
		@eval("\$pointer2+=0x{$chunks[1]};");

		switch($pointer2 % 26){
			case 1:
				$result_string=<<<ST1
<p>.....疲憊的{$input_string}側身倒在床上，烏溜溜的長髮遮掩不住衣衫不整的身體。</p>
<p>"為什麼他不要我了？"</p>
<p>想到這裡{$input_string}不禁悲從中來，對著自己鏡中的紫色眼影垂淚。</p>
<p>分析：{$input_string}被吃乾扒淨了，什麼都沒剩。</p>
ST1;
				break;
			case 2:
				$result_string=<<<ST2
<p>{$input_string}的真實成分：</p>
<ul>
<li>七分之四十九的變態</li>
<li>八分之五十六的大變態</li>
<li>九分之六十三的淫蟲</li>
</ul>
<p>路邊的小女孩也說了：{$input_string}是變態。</p>
ST2;
				break;
			case 3:
				$result_string="<p>那一邊飛翔一邊口吐光束的{$input_string}，彷彿在凌虐著巨大怪獸一般，在怪獸身上劃出一道一道的傷口。怪獸滿身鮮血倒地不起的畫面，實在讓人不忍。</p>";
				break;
			case 4:
				$result_string="<p>可愛的{$input_string}，其實我喜歡你很久了，跟我交往好不好？我是認真的，真的。</p>";
				break;
			case 5:
				$result_string="<p>{$input_string}一脸坏笑地把半个身子挤进门来，手里捏了一套扑克牌慈祥地说道，“不玩就把你吃掉！”</p>";
				break;
			case 6:
				$result_string="<p>不要以為你可以阻礙我們的愛情，{$input_string}。我今天就算拼了這條命，也要跟他結婚！</p>";
				break;
			case 7:
				$result_string="<p>請溫柔的對待我，不過，如果是{$input_string}的話，我會忍耐的.....</p>";
				break;
			case 8:
				$result_string="<p>就決定是你了，出來吧，{$input_string}！用你的愛征服我吧！</p>";
				break;
			case 9:
				$result_string="<p>機器人堅持著要繼續讓咖啡廳營業，等待他的主人{$input_string}回來的那一天。</p>";
				break;
			case 10:
				$result_string="<p>由於大型反應爐以及護盾技術的發明，造就了新世代宇宙戰艦的巨大化。</p>\n<p>其中最具代表性者，就是由{$input_string}指揮的長風艦隊所屬的第四世代強襲戰鬥艦。</p>";
				break;
			case 11:
				$result_string="<p>我的{$input_string}小壞蛋，來追我呀～<br /><br />－出自[沙灘上的追逐]第兩千五百八十九集</p>";
				break;
			case 12:
				$result_string=<<<ST12
<p>警官A：學長，為什麼你跟{$input_string}的關係這麼好呢？</p>
<p>警官B：因為只要有{$input_string}在的地方，就會有凶殺案。跟他打好關係，就不用怕沒業績啦。</p>
<p>警官A：喔～原來如此阿。</p>
ST12;
				break;
			case 13:
				$result_string="<p>{$input_string}，我不是說過了，好的老師帶你上天堂，不好的老師讓你住套房。\n<p>早跟你說過了，有沒有？有沒有？有沒有？你沒在聽嘛！(摔筆)</p>";
				break;
			case 14:
				$result_string="<p>我是貓熊。</p>";
				break;
			case 15:
				$result_string="<p>就這樣，{$input_string}帶著他的銀河鐵道無限車票，踏上了前往大仙女座的旅程。</p>";
				break;
			case 16:
				$result_string=<<<ST16
<pre>從前有個農夫名叫{$input_string}，他們家世世代代以種植奇異果為生

有一天，他發現樹上有一顆奇異果長得特別大
他感到非常的訝異，特別的照顧這顆奇異果

後來，這顆奇異果越長越大，越長越大
長大到這顆樹無法支撐這顆奇異果的重量的時候，奇異果掉了下來
{$input_string}把奇異果搬回家以後，把奇異果剖開
結果裡面竟然是一個多汁(!?)的奇異果少女

但是奇異果牽到哪裡都還是奇異果
再多汁的奇異果少女，一定會有毛太多的問題.... (奇異果真的都是毛嘛...)
{$input_string}看到了這奇異果太妹不禁感嘆："人說種瓠瓜生菜瓜，為什麼我種奇異果生芭樂呢？"

奇異果少女因為全身都是毛，大家都討厭他欺侮他
最後受不了種種的屈辱而跳堐自盡
想不到奇異果爛掉以後，種子發芽，長出好多好多的奇異果少女
他們組成奇異果軍團，要攻佔地球，讓地球全部變成奇異果

聯合國很緊張，陸海空軍都對付不了他們，只好找世界上最了解奇異果的{$input_string}來
但是{$input_string}搞不定(啥?你沒有照顧好我們的媽媽?姊妹們，上!!)

{$input_string}被打了個亂七八糟以後，知道自己不應該不愛惜水果
於是跟奇異果們真情告白，奇異果們大受感動，於是決定跟{$input_string}和解

就在這個時候，奇蹟發生了!!

奇異果少女們的毛竟然都掉了，變成了奇異果美少女(原來的....奇異果毛少女 XD)
{$input_string}這才領悟到一件他一直沒有注意到的事

奇異果是不能連皮吃的!!!

最後，{$input_string}繼續種他的奇異果，但是不在賣水果
而改當人口販子，跟奇異果們過著幸福快樂的生活


可喜可賀...可喜可賀....嗎!?</pre>
ST16;
				break;
			case 17:
				$result_string=<<<ST17
<p>{$input_string}一起穿拉拉褲，先穿一隻腳，一起說：嗨。</p>
<p>再穿另外一隻腳，說你好呀，你會穿嗎？你會穿嗎？</p>
<p>拉一拉拉拉褲拉到小肚肚，{$input_string}一起穿拉拉褲？</p>
ST17;
				break;
			case 18:
				$result_string=<<<ST18
<pre>所謂的{$input_string}
是在水溝等陰暗處
死去的蟑螂老鼠小貓小狗和十塊錢硬幣
他們的怨氣怨靈在長久時間的催化下
所型成的一種東西

初型成的{$input_string}是白色霧狀的
被太陽曬到就會馬上蒸發
但是隨著年齡的增加
吸收了足夠的死靈怨氣
霧氣越來越濃
漸漸的可以稍微抵擋陽光的照射

等到吸收了相當程度的死靈怨氣
達到最高的境界時
不但完全不怕陽光
還可以將自己的霧氣濃縮
化為人型

也許，{$input_string}就在你身邊....</pre>
ST18;
				break;
			case 19:
				$result_string=<<<ST19
<p>不要隨便對別人說情人節快樂，尤其是在你什麼都不知道的時候。</p>
<p>就像你不會對一個孤兒說母親節快樂，這是一種慈悲。</p>
<p>　　　　　　　　　　　　　　　　　－摘自"{$input_string}自傳-我不好吃"</p>
ST19;
				break;
			case 20:
				$result_string=<<<ST20
<p>"你要走的話，就拿開那玻璃罩吧。我可是朵玫瑰花，夜晚的涼風對我很好～"，{$input_string}這麼跟小王子說。</p>
<p>"對～夜晚的風一點都不冷～你看，我不會冷，還可以伸展我的刺呢～"</p>
<p>"哈啾"</p>
ST20;
				break;
			case 21:
				$result_string="<p>\"怕什麼？不過就是小貓小狗小豬小老虎跟幾座火山嘛。我可是朵有刺的玫瑰呢\"。{$input_string}一邊這樣說著，一邊張牙舞爪的伸展他的四根刺。</p>";
				break;
			case 22:
				$result_string=<<<ST22
<p>"姐....你不會死的，不會死的..."</p>
<p>話還沒說完，一生波折的兩人已經相擁而泣。</p>
<p>"{$input_string}，姐已經不行了。等姐姐死了以後，你拿我留給你的那些錢，好好一個人過生活..."</p>
<p>"錢？什麼錢？"</p>
<p>天有不測風雲，人有旦夕禍福。凡事總是先準備，才有保障。<br />
林老師人壽關心你，特別推出吃人不吐骨頭的食人壽險。</p>
<p>每個月只要繳交九十九萬九，你往生後便可向本公司領取金山銀山乙座。</p>
<p>門檻低，給款快速，現在加入還贈送高貴大方的水果刀與麻繩組。</p>
<p><br />"對了，姐，你的鬍子刺的我好痛。"</p>
ST22;
				break;
			case 23:
				$result_string="<p>說這種分析程式準確，就如同拿辭海來隨便翻一頁，然後在那一頁裡面找到自己的名字一樣的不可思議。</p>";
				break;
			case 24:
				$result_string="<p>{$input_string}，你要記得，這是你專用的神聖咒語：阿布拉鼻孔，逼逼布逼逼。</p>";
				break;
			case 25:
				$result_string=<<<ST25
<p>毒磨菇：10元</p>
<p>鍵盤：299元</p>
<p>PHP聖經本：500元</p>
<p>付費虛擬主機：1680元</p>
<p>提神用的細肩帶小女孩不加辣：3680元</p>
<p>用自己的腦殘讓{$input_string}跟其他十萬人變成笨蛋，還順便弄爛別人的機器：無價</p>
ST25;
				break;
			default:
				$result_string='<p>我很忙，沒空，你不用來了....下一位。</p>';
		}
		return $result_string;
	}
}

/* E Fun class */
?>