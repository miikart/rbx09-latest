<?php

	class context {

		public static function updated($time) {
		if (is_numeric($time)) {
    $timestampbutaccuratefornow = $time + 3600; 
} else {
    $timestampbutaccuratefornow = strtotime($time) + 3600; 
}

$diff = time() - $timestampbutaccuratefornow;

	$sec = $diff;
			$min = round($diff / 60 );
			$hrs = round($diff / 3600);
			$days = round($diff / 86400 );
			$weeks = round($diff / 604800);
			$mnths = round($diff / 2600640 );
			$yrs = round($diff / 31207680 );
if ($sec < 60) {
    if ($sec == 1) {
        return "1 second ago";
    } else {
        return "$sec seconds ago";
    }
} elseif ($min < 60) {
    if ($min == 1) {
        return "1 minute ago";
    } else {
        return "$min minutes ago";
    }
} elseif ($hrs < 24) {
    if ($hrs == 1) {
        return "1 hour ago";
    } else {
        return "$hrs hours ago";
    }
} elseif ($days < 7) {
    if ($days == 1) {
        return "1 day ago";
    } else {
        return "$days days ago";
    }
} elseif ($weeks < 4.3) { // 4.3 weeks approximates 1 month
    if ($weeks == 1) {
        return "1 week ago";
    } else {
        return "$weeks weeks ago";
    }
} elseif ($mnths < 12) {
    if ($mnths == 1) {
        return "1 month ago";
    } else {
        return "$mnths months ago";
    }
} else {
    if ($yrs == 1) {
        return "1 year ago";
    } else {
        return "$yrs years ago";
    }
}
}
		
public static function sendDiscordMessage($message, $thumbnailUrl = null) {
    $url = "https://discord.com/api/webhooks/1272164173761216574/qNgk5AC5_MgEuLr10JeOjqHokKx2xU5GVNExDvNNqM8nlB-_gp4DgPx-tS-jCYSpICrQ";

    $dataArray = array('content' => $message, 'allowed_mentions' => [ "parse" => [] ]);

    if ($thumbnailUrl) {
       
        $dataArray['embeds'] = [
            [
                'image' => [
                    'url' => $thumbnailUrl
                ]
            ]
        ];
    }

    $jsonData = json_encode($dataArray);

    $httpOptions = array(
        'http' => array(
            'header'  => "Content-Type: application/json\r\n",
            'method'  => "POST",
            'content' => $jsonData,
            'ignore_errors' => true  
        )
    );

    $context = stream_context_create($httpOptions);
    $result = @file_get_contents($url, false, $context);

		}

		public static function boughtitem($message, $thumbnailUrl = null) {
    $url = "https://discord.com/api/webhooks/1272164173761216574/qNgk5AC5_MgEuLr10JeOjqHokKx2xU5GVNExDvNNqM8nlB-_gp4DgPx-tS-jCYSpICrQ";

    $dataArray = array('content' => $message, 'allowed_mentions' => [ "parse" => [] ]);

     if ($thumbnailUrl) {
        $dataArray['embeds'] = [
            [
                'image' => [
                    'url' => $thumbnailUrl
                ]
            ]
        ];
    }
    $jsonData = json_encode($dataArray);

    $httpOptions = array(
        'http' => array(
            'header'  => "Content-Type: application/json\r\n",
            'method'  => "POST",
            'content' => $jsonData,
            'ignore_errors' => true  
        )
    );

    $context = stream_context_create($httpOptions);
    $result = @file_get_contents($url, false, $context);

		}
	public static function rccthing($message, $thumbnailUrl = null) {
    $url = "https://discord.com/api/webhooks/1272164173761216574/qNgk5AC5_MgEuLr10JeOjqHokKx2xU5GVNExDvNNqM8nlB-_gp4DgPx-tS-jCYSpICrQ";

    $dataArray = array('content' => $message, 'allowed_mentions' => [ "parse" => [] ]);

     if ($thumbnailUrl) {
        $dataArray['embeds'] = [
            [
                'image' => [
                    'url' => $thumbnailUrl
                ]
            ]
        ];
    }
    $jsonData = json_encode($dataArray);

    $httpOptions = array(
        'http' => array(
            'header'  => "Content-Type: application/json\r\n",
            'method'  => "POST",
            'content' => $jsonData,
            'ignore_errors' => true  
        )
    );

    $context = stream_context_create($httpOptions);
    $result = @file_get_contents($url, false, $context);

		}
	public static function commentlog($message, $thumbnailUrl = null) {
    $url = "https://discord.com/api/webhooks/1272164173761216574/qNgk5AC5_MgEuLr10JeOjqHokKx2xU5GVNExDvNNqM8nlB-_gp4DgPx-tS-jCYSpICrQ";

    $dataArray = array('content' => $message, 'allowed_mentions' => [ "parse" => [] ]);

     if ($thumbnailUrl) {
        $dataArray['embeds'] = [
            [
                'image' => [
                    'url' => $thumbnailUrl
                ]
            ]
        ];
    }
    $jsonData = json_encode($dataArray);

    $httpOptions = array(
        'http' => array(
            'header'  => "Content-Type: application/json\r\n",
            'method'  => "POST",
            'content' => $jsonData,
            'ignore_errors' => true  
        )
    );

    $context = stream_context_create($httpOptions);
    $result = @file_get_contents($url, false, $context);

		}
	public static function userlog($message, $thumbnailUrl = null) {
    $url = "https://discord.com/api/webhooks/1272164173761216574/qNgk5AC5_MgEuLr10JeOjqHokKx2xU5GVNExDvNNqM8nlB-_gp4DgPx-tS-jCYSpICrQ";

    $dataArray = array('content' => $message, 'allowed_mentions' => [ "parse" => [] ]);

     if ($thumbnailUrl) {
        $dataArray['embeds'] = [
            [
                'image' => [
                    'url' => $thumbnailUrl
                ]
            ]
        ];
    }
    $jsonData = json_encode($dataArray);

    $httpOptions = array(
        'http' => array(
            'header'  => "Content-Type: application/json\r\n",
            'method'  => "POST",
            'content' => $jsonData,
            'ignore_errors' => true  
        )
    );

    $context = stream_context_create($httpOptions);
    $result = @file_get_contents($url, false, $context);

		}
		public static function favoritelog($message, $thumbnailUrl = null) {
    $url = "https://discord.com/api/webhooks/1272164173761216574/qNgk5AC5_MgEuLr10JeOjqHokKx2xU5GVNExDvNNqM8nlB-_gp4DgPx-tS-jCYSpICrQ";

    $dataArray = array('content' => $message, 'allowed_mentions' => [ "parse" => [] ]);

     if ($thumbnailUrl) {
        $dataArray['embeds'] = [
            [
                'image' => [
                    'url' => $thumbnailUrl
                ]
            ]
        ];
    }
    $jsonData = json_encode($dataArray);

    $httpOptions = array(
        'http' => array(
            'header'  => "Content-Type: application/json\r\n",
            'method'  => "POST",
            'content' => $jsonData,
            'ignore_errors' => true  
        )
    );

    $context = stream_context_create($httpOptions);
    $result = @file_get_contents($url, false, $context);

		}
		public static function contains($str, array $arr) {
			foreach($arr as $a) {
				if (stripos($str,$a) !== false) return true;
			}
			return false;
		}
		
	

        public static function sanitizestring($username){
            $username = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $username);
            $username = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $username);
            $username = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $username);
            $username = html_entity_decode($username, ENT_COMPAT, 'UTF-8');

            $username = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $username);

            $username = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $username);
            $username = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $username);
            $username = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $username);

            $username = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $username);
            $username = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $username);
            $username = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $username);

            $username = preg_replace('#</*\w+:\w[^>]*+>#i', '', $username);

            do
            {
                $oldusername = $username;
                $username = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $username);
            }
            while ($oldusername !== $username);

            return $username;
        }
        
     
        
    }

    class Check
{
    const SEPARATOR_PLACEHOLDER = '{!!}';

    /**
     * Escaped separator characters
     */
    protected $escapedSeparatorCharacters = [
        '\s',
    ];

    /**
     * Unescaped separator characters.
     * @var array
     */
    protected $separatorCharacters = [
        '@',
        '#',
        '%',
        '&',
        '_',
        ';',
        "'",
        '"',
        ',',
        '~',
        '`',
        '|',
        '!',
        '$',
        '^',
        '*',
        '(',
        ')',
        '-',
        '+',
        '=',
        '{',
        '}',
        '[',
        ']',
        ':',
        '<',
        '>',
        '?',
        '.',
        '/',
    ];


    /**
     * List of potential character substitutions as a regular expression.
     *
     * @var array
     */
    protected $characterSubstitutions = [
        '/a/' => [
            'a',
            '4',
            '@',
            'Á',
            'á',
            'À',
            'Â',
            'à',
            'Â',
            'â',
            'Ä',
            'ä',
            'Ã',
            'ã',
            'Å',
            'å',
            'æ',
            'Æ',
            'α',
            'Δ',
            'Λ',
            'λ',
        ],
        '/b/' => ['b', '8', '\\', '3', 'ß', 'Β', 'β'],
        '/c/' => ['c', 'Ç', 'ç', 'ć', 'Ć', 'č', 'Č', '¢', '€', '<', '(', '{', '©'],
        '/d/' => ['d', '\\', ')', 'Þ', 'þ', 'Ð', 'ð'],
        '/e/' => ['e', '3', '€', 'È', 'è', 'É', 'é', 'Ê', 'ê', 'ë', 'Ë', 'ē', 'Ē', 'ė', 'Ė', 'ę', 'Ę', '∑'],
        '/f/' => ['f', 'ƒ'],
        '/g/' => ['g', '6', '9'],
        '/h/' => ['h', 'Η'],
        '/i/' => ['i', '!', '|', ']', '[', '1', '∫', 'Ì', 'Í', 'Î', 'Ï', 'ì', 'í', 'î', 'ï', 'ī', 'Ī', 'į', 'Į'],
        '/j/' => ['j'],
        '/k/' => ['k', 'Κ', 'κ'],
        '/l/' => ['l', '!', '|', ']', '[', '£', '∫', 'Ì', 'Í', 'Î', 'Ï', 'ł', 'Ł'],
        '/m/' => ['m'],
        '/n/' => ['n', 'η', 'Ν', 'Π', 'ñ', 'Ñ', 'ń', 'Ń'],
        '/o/' => [
            'o',
            '0',
            'Ο',
            'ο',
            'Φ',
            '¤',
            '°',
            'ø',
            'ô',
            'Ô',
            'ö',
            'Ö',
            'ò',
            'Ò',
            'ó',
            'Ó',
            'œ',
            'Œ',
            'ø',
            'Ø',
            'ō',
            'Ō',
            'õ',
            'Õ',
        ],
        '/p/' => ['p', 'ρ', 'Ρ', '¶', 'þ'],
        '/q/' => ['q'],
        '/r/' => ['r', '®'],
        '/s/' => ['s', '5', '$', '§', 'ß', 'Ś', 'ś', 'Š', 'š'],
        '/t/' => ['t', 'Τ', 'τ'],
        '/u/' => ['u', 'υ', 'µ', 'û', 'ü', 'ù', 'ú', 'ū', 'Û', 'Ü', 'Ù', 'Ú', 'Ū'],
        '/v/' => ['v', 'υ', 'ν'],
        '/w/' => ['w', 'ω', 'ψ', 'Ψ'],
        '/x/' => ['x', 'Χ', 'χ'],
        '/y/' => ['y', '¥', 'γ', 'ÿ', 'ý', 'Ÿ', 'Ý'],
        '/z/' => ['z', 'Ζ', 'ž', 'Ž', 'ź', 'Ź', 'ż', 'Ż'],
    ];

    /**
     * List of profanities to test against.
     *
     * @var array
     */
    protected $profanities = [];
    protected $separatorExpression;
    protected $characterExpressions;

    /**
     * @param null $config
     */
    public function __construct($config = null)
    {
        if ($config === null) {
            $config = __DIR__ . '/list.php';
        }

        if (is_array($config)) {
            $this->profanities = $config;
        } else {
            $this->profanities = $this->loadProfanitiesFromFile($config);
        }

        $this->separatorExpression = $this->generateSeparatorExpression();
        $this->characterExpressions = $this->generateCharacterExpressions();
    }

    /**
     * Load 'profanities' from config file.
     *
     * @param $config
     *
     * @return array
     */
    private function loadProfanitiesFromFile($config)
    {
        /** @noinspection PhpIncludeInspection */
        return include($config);
    }

    /**
     * Generates the separator regular expression.
     *
     * @return string
     */
    private function generateSeparatorExpression()
    {
        return $this->generateEscapedExpression($this->separatorCharacters, $this->escapedSeparatorCharacters);
    }

    /**
     * Generates the separator regex to test characters in between letters.
     *
     * @param array $characters
     * @param array $escapedCharacters
     * @param string $quantifier
     *
     * @return string
     */
    private function generateEscapedExpression(
        array $characters = [],
        array $escapedCharacters = [],
        $quantifier = '*?'
    ) {
        $regex = $escapedCharacters;
        foreach ($characters as $character) {
            $regex[] = preg_quote($character, '/');
        }

        return '[' . implode('', $regex) . ']' . $quantifier;
    }

    /**
     * Generates a list of regular expressions for each character substitution.
     *
     * @return array
     */
    protected function generateCharacterExpressions()
    {
        $characterExpressions = [];
        foreach ($this->characterSubstitutions as $character => $substitutions) {
            $characterExpressions[$character] = $this->generateEscapedExpression(
                    $substitutions,
                    [],
                    '+?'
                ) . self::SEPARATOR_PLACEHOLDER;
        }

        return $characterExpressions;
    }

    /**
     * Obfuscates string that contains a 'profanity'.
     *
     * @param $string
     *
     * @return string
     */
public function obfuscateIfProfane($string)
{
    // Split the string into words
    $words = preg_split('/(\b)/u', $string, -1, PREG_SPLIT_DELIM_CAPTURE);

    // Loop through each word
    foreach ($words as &$word) {
        // Check if the word itself is profane
        if ($this->hasProfanity($word)) {
            // Replace the profane word with #
            $word = str_repeat("#", strlen($word));
        }
    }

    // Reconstruct the string with obfuscated words
    return implode("", $words);
}



    /**
     * Checks string for profanities based on list 'profanities'
     *
     * @param $string
     *
     * @return bool
     */
    public function hasProfanity($string)
    {
        if (empty($string)) {
            return false;
        }

        $profanities = [];
        $profanityCount = count($this->profanities);

        for ($i = 0; $i < $profanityCount; $i++) {
            $profanities[$i] = $this->generateProfanityExpression(
                $this->profanities[$i],
                $this->characterExpressions,
                $this->separatorExpression
            );
        }

        foreach ($profanities as $profanity) {
            if ($this->stringHasProfanity($string, $profanity)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Generate a regular expression for a particular word
     *
     * @param $word
     * @param $characterExpressions
     * @param $separatorExpression
     *
     * @return mixed
     */
    protected function generateProfanityExpression($word, $characterExpressions, $separatorExpression)
    {
        $expression = '/' . preg_replace(
                array_keys($characterExpressions),
                array_values($characterExpressions),
                $word
            ) . '/i';

        return str_replace(self::SEPARATOR_PLACEHOLDER, $separatorExpression, $expression);
    }

    /**
     * Checks a string against a profanity.
     *
     * @param $string
     * @param $profanity
     *
     * @return bool
     */
    private function stringHasProfanity($string, $profanity)
    {
        return preg_match($profanity, $string) === 1;
    }
}

class ExploitPatch {
	public static function remove($data) {
		$data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
		$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
		$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
		$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

		$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

		$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

		$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

		do
		{
				$old_data = $data;
				$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
		}
		while ($old_data !== $data);

		return $data;
	}
	public static function charclean($string) {
		return preg_replace("/[^A-Za-z0-9 ]/", '', $string);
	}
	public static function numbercolon($string){
		return preg_replace("/[^0-9,-]/", '', $string);
	}
	public static function number($string){
		return preg_replace("/[^0-9]/", '', $string);
	}

        function encrypt ($pure_string, $encryption_key) {
        $cipher     = 'AES-256-CBC';
        $options    = OPENSSL_RAW_DATA;
        $hash_algo  = 'sha256';
        $sha2len    = 32;
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($pure_string, $cipher, $encryption_key, $options, $iv);
        $hmac = hash_hmac($hash_algo, $ciphertext_raw, $encryption_key, true);
        return $iv.$hmac.$ciphertext_raw;
    }
    function decrypt ($encrypted_string, $encryption_key) {
        $cipher     = 'AES-256-CBC';
        $options    = OPENSSL_RAW_DATA;
        $hash_algo  = 'sha256';
        $sha2len    = 32;
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = substr($encrypted_string, 0, $ivlen);
        $hmac = substr($encrypted_string, $ivlen, $sha2len);
        $ciphertext_raw = substr($encrypted_string, $ivlen+$sha2len);
        $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $encryption_key, $options, $iv);
        $calcmac = hash_hmac($hash_algo, $ciphertext_raw, $encryption_key, true);
        if(function_exists('hash_equals')) {
            if (hash_equals($hmac, $calcmac)) return $original_plaintext;
        } else {
            if ($this->hash_equals_custom($hmac, $calcmac)) return $original_plaintext;
        }
    }
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function capitalizeAndPluralize($input) {
    // Make the first character uppercase
    $input = ucfirst($input);

    // Check if the last character is a vowel, and add 's' for pluralization
    $lastChar = strtolower(substr($input, -1));
    if (in_array($lastChar, ['s'])) {
    } else {
        $input .= 's';
    }

    return $input;
}

    ?>