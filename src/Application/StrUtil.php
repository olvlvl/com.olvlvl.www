<?php

namespace App\Application;

class StrUtil
{
	/**
	 * Creates an excerpt of an HTML string.
	 *
	 * The following tags are preserved: A, P, CODE, DEL, EM, INS, and STRONG.
	 *
	 * @param string $str HTML string.
	 * @param int $limit The maximum number of words.
	 */
	public static function excerpt(string $str, int $limit = 55): string
	{
		static $allowed_tags = [
			'a',
			'p',
			'code',
			'del',
			'em',
			'ins',
			'strong'
		];
		$str = strip_tags(trim($str), '<' . implode('><', $allowed_tags) . '>');
		$str = preg_replace('#(<p>|<p\s+[^\>]+>)\s*</p>#', '', $str);
		$parts = preg_split('#<([^\s>]+)([^>]*)>#m', $str, 0, PREG_SPLIT_DELIM_CAPTURE);
		# i+0: text
		# i+1: markup ('/' prefix for closing markups)
		# i+2: markup attributes
		$rc = '';
		$opened = [];
		foreach ($parts as $i => $part) {
			if ($i % 3 == 0) {
				$words = preg_split('#(\s+)#', $part, 0, PREG_SPLIT_DELIM_CAPTURE);
				foreach ($words as $w => $word) {
					if ($w % 2 == 0) {
						if (!$word) // TODO-20100908: strip punctuation
						{
							continue;
						}
						$rc .= $word;
						if (!--$limit) {
							break;
						}
					} else {
						$rc .= $word;
					}
				}
				if (!$limit) {
					break;
				}
			} elseif ($i % 3 == 1) {
				if ($part[0] == '/') {
					$rc .= '<' . $part . '>';
					array_shift($opened);
				} else {
					array_unshift($opened, $part);
					$rc .= '<' . $part . $parts[$i + 1] . '>';
				}
			}
		}
		if (!$limit) {
			$rc .= ' <span class="excerpt-warp">[…]</span>';
		}
		if ($opened) {
			$rc .= '</' . implode('></', $opened) . '>';
		}
		return $rc;
	}
}
