<?php 
$id   = isset($_GET['id'])?$_GET['id'] : null;
$q    = isset($_GET['q'])?$_GET['q'] : null;
$v    = isset($_GET['v'])?$_GET['v'] : null;
$do    = isset($_GET['do'])?$_GET['do'] : null;

$page = isset($_GET['page'])?$_GET['page'] : null;
$hostname	= $_SERVER['HTTP_HOST'];
$homeurl	= "https://$hostname";
$useragent	= $_SERVER['HTTP_USER_AGENT'];
$refferer	= $_SERVER['HTTP_REFERER'];
$path		= $_SERVER['REQUEST_URI'];
class _Ocim{
        function canonical($seo = true, $base_url = false){
                $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
                $sp = strtolower($_SERVER["SERVER_PROTOCOL"]);
                $protocol = substr($sp, 0, strpos($sp, "/")) . $s;
                $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);

                if ($base_url){
                        return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port;
                }

                if ( ! $seo){
                        $url = $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['SCRIPT_NAME'];
                        $url .= ($_SERVER['QUERY_STRING'] != '') ? '?'. $_SERVER['QUERY_STRING'] : '';
                        return rtrim($url, "?&");
                }
                return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
        }

        function home_url(){
                return $this->canonical(false, true);
        }
        function is_home(){
                $host = $this->home_url().'/';
                if( $this->canonical == $host ){
                        return true;
                } else {
                        return false;
                }
        }
        function get_domain($url){
                $pieces = parse_url($url);
                $domain = isset($pieces['host']) ? $pieces['host'] : '';
                if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
                        return $regs['domain'];
                }
                return false;
        }
        function permalink($str, $delimiter = '-', $options = array()) {
	        // Make sure string is in UTF-8 and strip invalid UTF-8 characters
	        $str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
	
	        $defaults = array(
		        'delimiter' =>  $delimiter,
		        'limit' => null,
		        'lowercase' => true,
		        'replacements' => array(),
		        'transliterate' => true,
	        );
	
	        // Merge options
	        $options = array_merge($defaults, $options);
	
		$char_map = array(
		// Latin
		'??' => 'A', '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'AE', '??' => 'C', 
		'??' => 'E', '??' => 'E', '??' => 'E', '??' => 'E', '??' => 'I', '??' => 'I', '??' => 'I', '??' => 'I', 
		'??' => 'D', '??' => 'N', '??' => 'O', '??' => 'O', '??' => 'O', '??' => 'O', '??' => 'O', '??' => 'O', 
		'??' => 'O', '??' => 'U', '??' => 'U', '??' => 'U', '??' => 'U', '??' => 'U', '??' => 'Y', '??' => 'TH', 
		'??' => 'ss', 
		'??' => 'a', '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'ae', '??' => 'c', 
		'??' => 'e', '??' => 'e', '??' => 'e', '??' => 'e', '??' => 'i', '??' => 'i', '??' => 'i', '??' => 'i', 
		'??' => 'd', '??' => 'n', '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'o', 
		'??' => 'o', '??' => 'u', '??' => 'u', '??' => 'u', '??' => 'u', '??' => 'u', '??' => 'y', '??' => 'th', 
		'??' => 'y',

		// Latin symbols
		'??' => '(c)',

		// Greek
		'??' => 'A', '??' => 'B', '??' => 'G', '??' => 'D', '??' => 'E', '??' => 'Z', '??' => 'H', '??' => '8',
		'??' => 'I', '??' => 'K', '??' => 'L', '??' => 'M', '??' => 'N', '??' => '3', '??' => 'O', '??' => 'P',
		'??' => 'R', '??' => 'S', '??' => 'T', '??' => 'Y', '??' => 'F', '??' => 'X', '??' => 'PS', '??' => 'W',
		'??' => 'A', '??' => 'E', '??' => 'I', '??' => 'O', '??' => 'Y', '??' => 'H', '??' => 'W', '??' => 'I',
		'??' => 'Y',
		'??' => 'a', '??' => 'b', '??' => 'g', '??' => 'd', '??' => 'e', '??' => 'z', '??' => 'h', '??' => '8',
		'??' => 'i', '??' => 'k', '??' => 'l', '??' => 'm', '??' => 'n', '??' => '3', '??' => 'o', '??' => 'p',
		'??' => 'r', '??' => 's', '??' => 't', '??' => 'y', '??' => 'f', '??' => 'x', '??' => 'ps', '??' => 'w',
		'??' => 'a', '??' => 'e', '??' => 'i', '??' => 'o', '??' => 'y', '??' => 'h', '??' => 'w', '??' => 's',
		'??' => 'i', '??' => 'y', '??' => 'y', '??' => 'i',

		// Turkish
		'??' => 'S', '??' => 'I', '??' => 'C', '??' => 'U', '??' => 'O', '??' => 'G',
		'??' => 's', '??' => 'i', '??' => 'c', '??' => 'u', '??' => 'o', '??' => 'g', 

		// Russian
		'??' => 'A', '??' => 'B', '??' => 'V', '??' => 'G', '??' => 'D', '??' => 'E', '??' => 'Yo', '??' => 'Zh',
		'??' => 'Z', '??' => 'I', '??' => 'J', '??' => 'K', '??' => 'L', '??' => 'M', '??' => 'N', '??' => 'O',
		'??' => 'P', '??' => 'R', '??' => 'S', '??' => 'T', '??' => 'U', '??' => 'F', '??' => 'H', '??' => 'C',
		'??' => 'Ch', '??' => 'Sh', '??' => 'Sh', '??' => '', '??' => 'Y', '??' => '', '??' => 'E', '??' => 'Yu',
		'??' => 'Ya',
		'??' => 'a', '??' => 'b', '??' => 'v', '??' => 'g', '??' => 'd', '??' => 'e', '??' => 'yo', '??' => 'zh',
		'??' => 'z', '??' => 'i', '??' => 'j', '??' => 'k', '??' => 'l', '??' => 'm', '??' => 'n', '??' => 'o',
		'??' => 'p', '??' => 'r', '??' => 's', '??' => 't', '??' => 'u', '??' => 'f', '??' => 'h', '??' => 'c',
		'??' => 'ch', '??' => 'sh', '??' => 'sh', '??' => '', '??' => 'y', '??' => '', '??' => 'e', '??' => 'yu',
		'??' => 'ya',

		// Ukrainian
		'??' => 'Ye', '??' => 'I', '??' => 'Yi', '??' => 'G',
		'??' => 'ye', '??' => 'i', '??' => 'yi', '??' => 'g',

		// Czech
		'??' => 'C', '??' => 'D', '??' => 'E', '??' => 'N', '??' => 'R', '??' => 'S', '??' => 'T', '??' => 'U', 
		'??' => 'Z', 
		'??' => 'c', '??' => 'd', '??' => 'e', '??' => 'n', '??' => 'r', '??' => 's', '??' => 't', '??' => 'u',
		'??' => 'z', 

		// Polish
		'??' => 'A', '??' => 'C', '??' => 'e', '??' => 'L', '??' => 'N', '??' => 'o', '??' => 'S', '??' => 'Z', 
		'??' => 'Z', 
		'??' => 'a', '??' => 'c', '??' => 'e', '??' => 'l', '??' => 'n', '??' => 'o', '??' => 's', '??' => 'z',
		'??' => 'z',

		// Latvian
		'??' => 'A', '??' => 'C', '??' => 'E', '??' => 'G', '??' => 'i', '??' => 'k', '??' => 'L', '??' => 'N', 
		'??' => 'S', '??' => 'u', '??' => 'Z',
		'??' => 'a', '??' => 'c', '??' => 'e', '??' => 'g', '??' => 'i', '??' => 'k', '??' => 'l', '??' => 'n',
		'??' => 's', '??' => 'u', '??' => 'z'
		);
	
	        // Make custom replacements
	        $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
	
	        // Transliterate characters to ASCII
	        if ($options['transliterate']) {
		        $str = str_replace(array_keys($char_map), $char_map, $str);
	        }
	
	        // Replace non-alphanumeric characters with our delimiter
	        $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
	
	        // Remove duplicate delimiters
	        $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
	
	        // Truncate slug to max. characters
	        $str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : strlen($str)), 'UTF-8');
	
	        // Remove delimiter from ends
	        $str = trim($str, $options['delimiter']);
	
                return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
        }
        function get_contents($url) {
                if (function_exists('curl_exec')){ 
                $ch = curl_init();

                $header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
                $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
                $header[] = "Cache-Control: max-age=0";
                $header[] = "Connection: keep-alive";
                $header[] = "Keep-Alive: 300";
                $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
                $header[] = "Accept-Language: en-us,en;q=0.5";
                $header[] = "Pragma: ";

                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5 );
                curl_setopt($ch, CURLOPT_REFERER, "https://www.facebook.com");
                curl_setopt($ch, CURLOPT_AUTOREFERER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
          //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/".rand(3,5).".".rand(0,3)." (Windows NT ".rand(3,5).".".rand(0,2)."; rv:2.0.1) Gecko/20100101 Firefox/".rand(3,5).".0.1");

                $url_get_contents_data = curl_exec($ch);
                curl_close($ch);
                        if ($url_get_contents_data == false){
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                                curl_setopt($ch, CURLOPT_HEADER, 0);
                              //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                                curl_setopt($ch, CURLOPT_URL, $url);
                                $url_get_contents_data = curl_exec($ch);
                                curl_close($ch);
                        }
                }elseif(function_exists('file_get_contents')){
                        $url_get_contents_data = @file_get_contents($url);
                }elseif(function_exists('fopen') && function_exists('stream_get_contents')){
                        $handle = fopen ($url, "r");
                        $url_get_contents_data = stream_get_contents($handle);
                }else{
                        $url_get_contents_data = false;
                }
                return $url_get_contents_data;
        }
        function short($text, $len = 150, $more = '...') {
                $txt = ltrim(strip_tags($text));
                if (strlen($txt) > $len) {
                        $text = substr($txt, 0, $len);
                        $txt = substr($text, 0, strrpos($text, ' ')).$more;
                }
                return $txt;
        }
	function remove_repeating_chars($object){
		return preg_replace("/[^a-zA-Z0-9\s.?!\/]/", "", $object);
	}
	function fix_json( $j ){
        	$j = trim( $j );
        	$j = ltrim( $j, '(' );
        	$j = rtrim( $j, ')' );
        	$a = preg_split('#(?<!\\\\)\"#', $j );
        
        	for( $i=0; $i < count( $a ); $i+=2 ){
                	$s = $a[$i];
                	$s = preg_replace('#([^\s\[\]\{\}\:\,]+):#', '"\1":', $s );
                	$a[$i] = $s;
        	}
        
        	$j = implode( '"', $a );
        
        	return $j;
	}
	function slugify($text,$strict = false) {
        	$text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        	// replace non letter or digits by -
        	$text = preg_replace('~[^\\pL\d.]+~u', ' ', $text);

        	// trim
        	$text = trim($text, ' ');
        	setlocale(LC_CTYPE, 'en_GB.utf8');
        	// transliterate
        	if (function_exists('iconv')) {
			$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        	}

        	// lowercase
        	$text = strtolower($text);
        	// remove unwanted characters
        	$text = preg_replace('~[^-\w.]+~', ' ', $text);
        	if (empty($text)) {
			return 'empty_$';
        	}
        	if ($strict) {
			$text = str_replace(".", "_", $text);
        	}
        	return $text;
	}
	function removeDuplicates($sSearch, $sReplace, $sSubject){
		$i=0;
		do {
			$sSubject=str_replace($sSearch, $sReplace, $sSubject);         
			$pos=strpos($sSubject, $sSearch);
         
			$i++;
			if($i>100)
			{
				die('removeDuplicates() loop error');
			}
         
		}
		while($pos!==false);
		return $sSubject;
	}
	function strposa($haystack, $needle, $offset=0) {
        	if(is_array($needle)):
        	foreach($needle as $query) {
                	if(!empty($query)):
                        	if(strpos( (string) $haystack, $query, $offset) !== false) return true; // stop on first true result
                	endif;
        	}
        	endif;
        	return false;
	}
}
?>