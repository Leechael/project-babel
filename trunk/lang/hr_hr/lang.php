<?php
class lang implements Language {
	public function lang() {
		return 'Hrvatski';
	}
	
	public function login() {
		return 'Prijavite Se';
	}
	
	public function signed_in($site) {
		return 'Prijavili Ste Se ' . $site;
	}
	
	public function logout() {
		return 'Odjavite se';
	}
	
	public function now_auto_redirecting($site) {
		return '<small>Upravo Vas preusmjeravamo na <a href="/">' . $site . '</a> po�etnu stranicu. Ako �elite, kliknite <a href="/">ovdje</a> da bi ste se ru�no preusmjerili.</small>';
	}
	
	public function sign_in_again() {
		return 'Ponovno Se Prijavite';
	}
	
	public function you_have_signed_out($site) {
		return 'Odjavili ste se sa stranice ' . $site;
	}
	
	public function privacy_ok() {
		return '<small>Na ovom ra�unalu sada nije spremljen nijedan osobni podatak.</small>';
	}
	
	public function welcome_back_anytime() {
		return 'Bilo kada ste ponovno dobrodo�li!';
	}
	
	public function return_home($site) {
		return 'Vratite se na po�etnu stranicu';
	}
	
	public function shuffle_home() {
		return 'Razbacajte Po�etnu Stranicu';
	}
	
	public function remix_home() {
		return 'Pomje�ajte Po�etnu Stranicu';
	}
	
	public function home($site) {
		return $site . ' Home';
	}
	
	public function help() {
		return 'Pomo�';
	}
	
	public function new_features() {
		return 'Nove osobine';
	}
	
	public function user_id() {
		return 'Korisni�ka indentifikacija';
	}
	
	public function password() {
		return 'Lozinka';
	}
	
	public function take_a_tour() {
		return 'Napravite turu';
	}
	
	public function about($site) {
		return 'O ' . $site;
	}
	
	public function rss() {
		return 'RSS';
	}
	
	public function copper($i) {
		if ($i > 10000) {
			$g = floor($i / 10000);
			$r = $i - (10000 * $g);
			if ($r > 100) {
				$s = floor($r / 100);
				$r2 = $r - (100 * $s);
				if ($r2 > 0) {
					return '<small>' . $g . '</small>g <small>' . $s . '</small>s <small>' . $r2 . '</small>c';
				} else {
					return '<small>' . $g . '</small>g <small>' . $s . '</small>s';
				}
			} else {
				return '<small>' . $i . '</small> c';
			}
		} else {
			if ($i > 100) {
				$s = floor($i / 100);
				$r = $i - (100 * $s);
				if ($r > 0) {
					return '<small>' . $s . '</small> s <small>' . $r . '</small> c';
				} else {
					return '<small>' . $s . '</small> s';
				}
			} else {
				return '<small>' . $i . '</small> c';
			}
		}
	}
	
	public function register() {
		return 'U�lanite Se';
	}
	
	public function search() {
		return 'Pretraga';
	}
	
	public function ref_search() {
		return 'Pretraga Referenci';
	}
	
	public function tools() {
		return 'Alati';
	}
	
	public function members_total() {
		return 'Ukupni Broj �lanova';
	}
	
	public function discussions() {
		return 'Diskusije';
	}
	
	public function favorites() {
		return 'Omiljeno';
	}
	
	public function Savepoints() {
		return 'Spremljena mjesta';
	}
	
	public function ing_updates() {
		return 'Obnovljeni Ingovi';
	}
	
	public function weblogs() {
		return 'Weblogovi';
	}
	
	public function online_total() {
		return 'Ukupno Online';
	}
	
	public function anonymous() {
		return 'Anonimac';
	}
	
	public function registered() {
		return 'U�lanjen';
	}
	
	public function system_status() {
		return 'Stanje Sistema';
	}
	
	public function online_count($i) {
		return $i . ' Na Stranicama';
	}
	
	public function session_count($i) {
		return '' . $i . ' Stranica Posje�eno';
	}
	
	
	
	public function login_history() {
		return 'Povijest Prijava';
	}
	
	public function upload_portrait() {
		return 'Prebacite Portret Gore';
	}
	
	public function settings() {
		return 'Pode�avanja';
	}
	
	public function set_location() {
		return 'Postavite Mjesto';
	}
	
	public function password_recovery() {
		return 'Povratite Lozinku';
	}
	
	public function timtowtdi() {
		return "Postoje vi�e rije�enja.";
	}
	
	public function reply() {
		return 'Odgovorite';
	}
	
	public function login_and_reply() {
		return 'Prijavite se i odgovorite';
	}
	
	public function switch_description() {
		return 'Zamjenite Opis';
	}
	
	public function jump_to_replies() {
		return 'Sko�ite na odgovore';
	}
	
	public function hits($i) {
		return $i . ' pogodaka';
	}
	
	public function posts($i) {
		return $i . ' odgovora';
	}
	
	public function expenses() {
		return 'Tro�kovi';
	}
	
	public function my_profile($site) {
		return 'Moj Profil';
	}
	
	public function my_topics() {
		return 'Moje Teme';
	}
	
	public function my_blogs() {
		return 'Moji Weblogovi';
	}
	
	public function my_messages() {
		return 'Moje Poruke';
	}
	
	public function my_friends() {
		return 'Moji Prijatelji';
	}
	
	public function my_favorites() {
		return 'Moje Omiljeno';
	}
	
	public function send_money() {
		return 'Po�aljite Novac';
	}
	
	public function top_wealth() {
		return 'Top Lista Bogatih';
	}
	
	public function top_topics() {
		return 'Top Lista Tema';
	}
	
	public function latest_topics() {
		return 'Zadnje Teme';
	}
	
	public function latest_replied() {
		return 'Zadnji Odgovori';
	}
	
	public function latest_unanswered() {
		return 'Zadnji Neogovoreni';
	}
	
	public function latest_members() {
		return 'Zadnji �lanovi';
	}
	
	public function join_discussion() {
		return 'Pridru�ite se Raspravi';
	}
	
	public function browse_node($name, $title) {
		return 'Pretra�ite <a href="' . urlencode($name) . '" class="regular">' . make_plaintext($title) . '</a>';
	}
	
	public function more_hot_topics() {
		return 'Vi�e Aktualnih Tema';
	}
	
	public function member_show() {
		return 'Pogledajte �lana';
	}
	
	public function create_new_topic() {
		return 'Stvorite Novu Temu';
	}
	
	public function create_new_topic_in($title) {
		return '<small>CStvorite Novu Temu u ' . $title . '</small>';
	}
	
	public function favorite_this_topic() {
		return 'Stavite temu u Omiljene';
	}
	
	public function be_the_first_one_to_reply() {
		return 'Za sada nema odgvora. Da li �ete Vi biti prvi?';
	}
	
	public function who_adds_me() {
		return '<small>Tko me je dodao kao prijatelja?</small>';
	}
}
?>
