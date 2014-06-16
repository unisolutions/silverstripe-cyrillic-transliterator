<?php

/**
 * Support class for converting cyrilic (Russian) and unicode strings into a suitable 7-bit ASCII equivalent.
 *
 * It supports these systems:
 * - Passport (2013), ICAO (http://en.wikipedia.org/wiki/Romanization_of_Russian#Transliteration_table)
 * - BGN/PCGN (http://en.wikipedia.org/wiki/Romanization_of_Russian#Transliteration_table)
 * - ISO 9:1995, or GOST 7.79 System B (http://en.wikipedia.org/wiki/ISO_9#GOST_7.79_System_B.5B2.5D)
 *
 * By default it uses <b>Passport (2013), ICAO</b> system.
 *
 * Usage:
 *
 * <code>
 * $tr = new CyrillicTransliterator();
 * $ascii = $tr->toASCII($unicode);
 * </code>
 *
 * @package cyrillic-transliterator
 */
class CyrillicTransliterator extends SS_Transliterator {
	/**
	 * @config
	 * @var boolean Allow the use of iconv() to perform transliteration.  Set to false to disable.
	 * Even if this variable is true, iconv() won't be used if it's not installed.
	 */
	private static $use_iconv = false;

	/**
	 * @config
	 * @var string Transliteration system to use.
	 */
	private static $transliteration_system = 'passport2013';

	/**
	 * Convert the given utf8 string to a safe ASCII source
	 */
	public function toASCII($source) {
		if(function_exists('iconv') && $this->config()->use_iconv) return $this->useIconv($source);
		else return $this->useStrTr($source);
	}

	/**
	 * Transliteration using strtr() and a lookup table
	 */
	protected function useStrTr($source) {
		$table = array(

			'passport2013' => array(
				'А' => 'A', 'а' => 'a',
				'Б' => 'B', 'б' => 'b',
				'В' => 'V', 'в' => 'v',
				'Г' => 'G', 'г' => 'g',
				'Д' => 'D', 'д' => 'd',
				'Е' => 'E', 'е' => 'e',
				'Ё' => 'E', 'ё' => 'e',
				'Ж' => 'Zh', 'ж' => 'zh',
				'З' => 'Z', 'з' => 'z',
				'И' => 'I', 'И' => 'I', 'и' => 'i',
				'Й' => 'I', 'й' => 'i',
				'К' => 'K', 'к' => 'k',
				'Л' => 'L', 'л' => 'l',
				'М' => 'M', 'м' => 'm',
				'Н' => 'N', 'н' => 'n',
				'О' => 'O', 'о' => 'o',
				'П' => 'P', 'п' => 'p',
				'Р' => 'R', 'р' => 'r',
				'С' => 'S', 'с' => 's',
				'Т' => 'T', 'т' => 't',
				'У' => 'U', 'у' => 'u',
				'Ф' => 'F', 'ф' => 'f',
				'Х' => 'Kh', 'х' => 'kh',
				'Ц' => 'Ts', 'ц' => 'ts',
				'Ч' => 'Ch', 'ч' => 'ch',
				'Ш' => 'Sh', 'ш' => 'sh',
				'Щ' => 'Shch', 'щ' => 'shch',
				'Ъ' => 'Ie', 'ъ' => 'ie',
				'Ы' => 'Y', 'ы' => 'y',
				'Ь' => '', 'ь' => '',
				'Э' => 'E', 'э' => 'e',
				'Ю' => 'Iu', 'ю' => 'iu',
				'Я' => 'Ia', 'я' => 'ia',
			),

			'bgn_pcgn' => array(
				'А' => 'A', 'а' => 'a',
				'Б' => 'B', 'б' => 'b',
				'В' => 'V', 'в' => 'v',
				'Г' => 'G', 'г' => 'g',
				'Д' => 'D', 'д' => 'd',
				'Е' => 'E', 'е' => 'e',  // TODO (e (ye)) Digraph ye is used to indicate iotation at the beginning of a word and after vowels й, ъ or ь.
				'Ё' => 'E', 'ё' => 'e',  // TODO (ë (yë)) Digraph yë is used to indicate iotation at the beginning of a word and after vowels й, ъ or ь.
				'Ж' => 'Zh', 'ж' => 'zh',
				'З' => 'Z', 'з' => 'z',
				'И' => 'I', 'и' => 'i',
				'Й' => 'Y', 'й' => 'y',
				'К' => 'K', 'к' => 'k',
				'Л' => 'L', 'л' => 'l',
				'М' => 'M', 'м' => 'm',
				'Н' => 'N', 'н' => 'n',
				'О' => 'O', 'о' => 'o',
				'П' => 'P', 'п' => 'p',
				'Р' => 'R', 'р' => 'r',
				'С' => 'S', 'с' => 's',
				'Т' => 'T', 'т' => 't',
				'У' => 'U', 'у' => 'u',
				'Ф' => 'F', 'ф' => 'f',
				'Х' => 'Kh', 'х' => 'kh',
				'Ц' => 'Ts', 'ц' => 'ts',
				'Ч' => 'Ch', 'ч' => 'ch',
				'Ш' => 'Sh', 'ш' => 'sh',
				'Щ' => 'Shch', 'щ' => 'shch',
				'Ъ' => 'ˮ', 'ъ' => 'ˮ',
				'Ы' => 'Y', 'ы' => 'y',
				'Ь' => 'ʼ', 'ь' => 'ʼ',
				'Э' => 'E', 'э' => 'e',
				'Ю' => 'Yu', 'ю' => 'yu',
				'Я' => 'Ya', 'я' => 'ya',
			),

			'iso9' => array(
				'А' => 'A', 'а' => 'a',
				'Б' => 'B', 'б' => 'b',
				'В' => 'V', 'в' => 'v',
				'Г' => 'G', 'г' => 'g',
				'Ѓ' => 'G`', 'ѓ' => 'g`', // in Macedonian
				'Ґ' => 'G`', 'ґ' => 'g`', // in Ukrainian
				'Д' => 'D', 'д' => 'd',
				'Е' => 'E', 'е' => 'e',
				'Ё' => 'Yo', 'ё' => 'yo', // in Russian and Belarusian
				'Є' => 'Ye', 'є' => 'ye', // in Ukrainian
				'Ж' => 'Zh', 'ж' => 'zh',
				'З' => 'Z', 'з' => 'z',
				'Ѕ' => 'Z`', 'ѕ' => 'z`', // in Macedonian
				'И' => 'I', 'и' => 'i', // not in Belarusian
				// TODO
				//'И' => 'Y`', 'и' => 'y`', // in Ukrainian
				'Й' => 'Y', 'й' => 'y',
				'Ј' => 'J', 'ј' => 'j', // in Macedonian
				// TODO ?
				'І' => 'I', 'і' => 'i', // i` before vowels for Old Russian and Old Bulgarian
				'Ї' => 'Yi', 'ї' => 'yi', // in Ukrainian
				'К' => 'K', 'к' => 'k',
				'Ќ' => 'K`', 'ќ' => 'k`', // in Macedonian
				'Л' => 'L', 'л' => 'l',
				'Љ' => 'L`', 'љ' => 'l`', // in Macedonian
				'М' => 'M', 'м' => 'm',
				'Н' => 'N', 'н' => 'n',
				'Њ' => 'N`', 'њ' => 'n`', // in Macedonian
				'О' => 'O', 'о' => 'o',
				'П' => 'P', 'п' => 'p',
				'Р' => 'R', 'р' => 'r',
				'С' => 'S', 'с' => 's',
				'Т' => 'T', 'т' => 't',
				'У' => 'U', 'у' => 'u',
				'Ў' => 'U`', 'ў' => 'u`', // in Belarusian
				'Ф' => 'F', 'ф' => 'f',
				'Х' => 'X', 'х' => 'x',
				// TODO
				'Ц' => 'Cz', 'ц' => 'cz', // c before i, e, y, j
				'Ч' => 'Ch', 'ч' => 'ch',
				'Џ' => 'Dh', 'џ' => 'dh', // in Macedonian
				'Ш' => 'Sh', 'ш' => 'sh',
				'Щ' => 'Shh', 'щ' => 'shh', // for Russian and Ukrainian
				//TODO
				//'Щ' => 'Sht', 'щ' => 'sht', // for Bulgarian
				'Ъ' => '``', 'ъ' => '``', // for Russian
				// TODO
				//'Ъ' => 'A`', 'ъ' => 'a`', // for Bulgarian
				'Ы' => 'Y`', 'ы' => 'y`', // in Russian and Belarusian
				'Ь' => '`', 'ь' => '`',
				'Э' => 'E`', 'э' => 'e`', // in Russian and Belarusian
				'Ю' => 'Yu', 'ю' => 'yu', // not in Macedonian
				'Я' => 'Ya', 'я' => 'ya', // not in Macedonian
				'’' => '\'',
				'Ѣ' => 'Ye', 'ѣ' => 'ye', // in Old Russian and Old Bulgarian
				'Ѳ' => 'Fh', 'ѳ' => 'fh', // in Old Russian and Old Bulgarian
				'Ѵ' => 'Yh', 'ѵ' => 'yh', // in Old Russian and Old Bulgarian
				'Ѫ' => 'О`', 'ѫ' => 'о`', // in Old Bulgarian
				'№' => '#',
			),

		);

		if (isset($table[$this->config()->transliteration_system])) {
			return strtr($source, $table[$this->config()->transliteration_system]);
		} else {
			return $source;
		}
	}

	/**
	 * Transliteration using iconv()
	 */
	protected function useIconv($source) {
		return iconv("utf-8", "us-ascii//IGNORE//TRANSLIT", $source);
	}
}
