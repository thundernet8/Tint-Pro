<?php
/*
Copyright (c) 2016 Janis Elsts

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

/**
 * TLDExtract accurately extracts subdomain, domain and TLD components from URLs.
 *
 * Usage:
 *
 * $extract = new TLDExtract();
 * $components = $extract('http://forums.news.cnn.com/'); //PHP 5.3 style. In PHP 5.2, use $extract->extract().
 *
 * echo $components->subdomain; // forum.news
 * echo $components->domain;    // cnn
 * echo $components->tld;       // com
 *
 * // Array access syntax also works.
 * $components = $extract('http://forums.bbc.co.uk/');
 * echo $components['tld']; // co.uk
 *
 * @see TLDExtractResult for more information on the returned data structure.
 * @see tldextract() Handy shortcut function.
 */
class TLDExtract {
	const SCHEME_RE = '#^([a-zA-Z][a-zA-Z0-9+\-.]*:)?//#';

	private $fetch;
	private $cacheFile;
	private $extractor = null;

	/**
	 * Construct an extractor instance.
	 *
	 * If $fetch is TRUE (the default) and no cached TLD set is found, the extractor will
	 * fetch the Public Suffix List live over HTTP on first use. Set to FALSE to disable
	 * this behaviour. Either way, if the TLD set can't be read, the extractor will fall
	 * back to the included snapshot.
	 *
	 * Specifying $cacheFile will override the location of the cached TLD set.
	 * Defaults to /path/to/tldextractphp/.tld_set.
	 *
	 * @param bool $fetch
	 * @param string $cacheFile
	 */
	public function __construct($fetch = true, $cacheFile = '') {
		$this->fetch = $fetch;
		$this->cacheFile = !empty($cacheFile) ? $cacheFile : dirname(__FILE__) . DIRECTORY_SEPARATOR . '.tld_set';
	}

	/**
	 * Make it possible to call the extractor instance directly.
	 *
	 * $extract = new TLDExtract();
	 * echo $extract('http://www.google.co.uk/');
	 * //Output: TLDExtractResult(subdomain='www', domain='google', tld='co.uk')
	 *
	 * @param string $url
	 * @return TLDExtractResult
	 */
	public function __invoke($url) {
		return $this->extract($url);
	}

	/**
	 * Extract the subdomain, domain, and gTLD/ccTLD components from a URL.
	 *
	 * @param string $url
	 * @return TLDExtractResult
	 */
	public function extract($url) {
		$host = $this->getHost($url);
		$extractor = $this->getTldExtractor();
		list($registeredDomain, $tld) = $extractor->extract($host);

		//Check for IPv4 and IPv6 addresses.
		if ( empty($tld) && $this->isIp($host) ) {
			return new TLDExtractResult('', $host, '');
		}

		$lastDot = strrpos($registeredDomain, '.');
		if ( $lastDot !== false ) {
			$subdomain = substr($registeredDomain, 0, $lastDot);
			$domain = substr($registeredDomain, $lastDot + 1);
		} else {
			$subdomain = '';
			$domain = $registeredDomain;
		}

		return new TLDExtractResult($subdomain, $domain, $tld);
	}

	/**
	 * Extract the hostname from a URL.
	 *
	 * @param string $url
	 * @return string
	 */
	private function getHost($url) {
		//Remove scheme and path.
		$host = preg_replace(self::SCHEME_RE, '', $url);
		list($host, ) = explode('/', $host, 2);

		//Remove username and password.
		$pieces = explode('@', $host, 2);
		if ( count($pieces) == 2 ) {
			$host = $pieces[1];
		}

		//Check for IPv6 literals like "[3ffe:2a00:100:7031::1]"
		//See http://www.ietf.org/rfc/rfc2732.txt
		$closingBracket = strrpos($host, ']');
		if ( $this->startsWith($host, '[') && $closingBracket ) {
			$host = substr($host, 0, $closingBracket + 1);
		} else {
			//This is either a normal hostname or an IPv4 address. Just remove the port.
			list($host, ) = explode(':', $host);
		}

		return $host;
	}

	/**
	 * @return PublicSuffixListTLDExtractor
	 */
	private function getTldExtractor() {
		if ( $this->extractor !== null ) {
			return $this->extractor;
		}

		//Load the public suffix list from the cache, if possible.
		$serializedTlds = @file_get_contents($this->cacheFile);
		if ( !empty($serializedTlds) ) {
			$this->extractor = new PublicSuffixListTLDExtractor(unserialize($serializedTlds));
			return $this->extractor;
		}

		//Or attempt to download it.
		$tlds = array();
		if ( $this->fetch ) {
			$tlds = $this->fetchTldList();
		}

		if ( empty($tlds) ) {
			//If all else fails, try the local snapshot.
			$snapshotFile = dirname(__FILE__) . DIRECTORY_SEPARATOR . '.tld_set_snapshot';
			$serializedTlds = @file_get_contents($snapshotFile);
			if ( !empty($serializedTlds) ) {
				$this->extractor = new PublicSuffixListTLDExtractor(unserialize($serializedTlds));
				return $this->extractor;
			}
		} else {
			//Update the cache.
			@file_put_contents($this->cacheFile, serialize($tlds));
		}

		$this->extractor = new PublicSuffixListTLDExtractor($tlds);
		return $this->extractor;
	}

	private function fetchTldList() {
		$page = $this->fetchPage('http://mxr.mozilla.org/mozilla-central/source/netwerk/dns/effective_tld_names.dat?raw=1');
		$tlds = array();
		if ( !empty($page) && preg_match_all('@^(?P<tld>[.*!]*\w[\S]*)@um', $page, $matches) ) {
			$tlds = array_fill_keys($matches['tld'], true);
		}
		return $tlds;
	}

	private function fetchPage($url) {
		if( ini_get('allow_url_fopen') ) {
			return @file_get_contents($url);
		} else if ( is_callable('curl_exec') ) {
			$handle = curl_init($url);
			curl_setopt_array($handle, array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HEADER => false,
				CURLOPT_FAILONERROR => true,
			));
			$content = curl_exec($handle);
			curl_close($handle);
			return $content;
		}
		return '';
	}

	/**
	 * Check if the input is a valid IP address.
	 * Recognizes both IPv4 and IPv6 addresses.
	 *
	 * @param string $host
	 * @return bool
	 */
	private function isIp($host) {
		//Strip the wrapping square brackets from IPv6 addresses
		if ( $this->startsWith($host, '[') && $this->endsWith($host, ']') ) {
			$host = substr($host, 1, -1);
		}
		return (bool)filter_var($host, FILTER_VALIDATE_IP);
	}

	private function startsWith($haystack, $needle) {
		$length = strlen($needle);
		return (substr($haystack, 0, $length) === $needle);
	}

	private function endsWith($haystack, $needle) {
	    $length = strlen($needle);
	    if ($length == 0) {
	        return true;
	    }
	    return (substr($haystack, -$length) === $needle);
	}
}

/**
 * This class holds the components of a domain name.
 *
 * @property string $subdomain The subdomain. For example, the subdomain of "a.b.google.com" is "a.b".
 * @property string $domain The registered domain. For example, in "a.b.google.com" the registered domain is "google".
 * @property string $tld The top-level domain / public suffix. For example: "com", "co.uk", "act.edu.au".
 *
 * You can access the components using either property syntax or array syntax. For example,
 * "echo $result->tld" and "echo $result['tld']" will both work and output the same string.
 *
 * All properties are read-only.
 */
class TLDExtractResult implements ArrayAccess {
	private $fields;

	public function __construct($subdomain, $domain, $tld) {
		$this->fields = array(
			'subdomain' => $subdomain,
			'domain'    => $domain,
			'tld'       => $tld,
		);
	}

	public function __get($name) {
		if ( array_key_exists($name, $this->fields) ) {
			return $this->fields[$name];
		}
		throw new OutOfRangeException(sprintf('Unknown field "%s"', $name));
	}

	public function __isset($name) {
		return array_key_exists($name, $this->fields);
	}

	public function __set($name, $value) {
		throw new LogicException('Can\'t modify an immutable object.');
	}

	public function __toString() {
		return sprintf('%s(subdomain=\'%s\', domain=\'%s\', tld=\'%s\')', __CLASS__, $this->subdomain, $this->domain, $this->tld);
	}

	public function offsetExists($offset) {
		return array_key_exists($offset, $this->fields);
	}

	public function offsetGet($offset) {
		return $this->__get($offset);
	}

	public function offsetSet($offset, $value) {
		throw new LogicException(sprintf('Can\'t modify an immutable object. You tried to set "%s".', $offset));
	}

	public function offsetUnset($offset) {
		throw new LogicException(sprintf('Can\'t modify an immutable object. You tried to unset "%s".', $offset));
	}

	/**
	 * Get the domain name components as a native PHP array.
	 * The returned array will contain these keys: 'subdomain', 'domain' and 'tld'.
	 *
	 * @return array
	 */
	public function toArray() {
		return $this->fields;
	}
}

/**
 * This class splits domain names into the registered domain and public suffix components
 * using the TLD rule set from the Public Suffix List project.
 */
class PublicSuffixListTLDExtractor {
	private $tlds;

	/**
	 * @param array $tlds The TLD set from PSL.
	 */
	public function __construct($tlds) {
		$this->tlds = $tlds;
	}

	/**
	 * @param string $host
	 * @return array An array with two items - the reg. domain (possibly with subdomains) and the public suffix.
	 */
	public function extract($host) {
		$parts = explode('.', $host);

		for ($i = 0; $i < count($parts); $i++) {
			$maybeTld = join('.', array_slice($parts, $i));
			$exceptionTld = '!' . $maybeTld;
			if ( array_key_exists($exceptionTld, $this->tlds) ) {
				return array(
					join('.', array_slice($parts, 0, $i + 1)),
					join('.', array_slice($parts, $i + 1)),
				);
			}

			$wildcardTld = '*.' . join('.', array_slice($parts, $i + 1));
			if ( array_key_exists($wildcardTld, $this->tlds) || array_key_exists($maybeTld, $this->tlds) ) {
				return array(
					join('.', array_slice($parts, 0, $i)),
					$maybeTld
				);
			}
		}

		return array($host, '');
	}
}

/**
 * Extract the subdomain, domain and TLD components from a URL.
 * A convenient alias for TLDExtract::extract().
 *
 * @uses TLDExtract::extract()
 *
 * @param string $url
 * @return TLDExtractResult
 */
function tldextract($url) {
	static $tldExtractor = null;
	if ( $tldExtractor === null ) {
		$tldExtractor = new TLDExtract();
	}
	return $tldExtractor->extract($url);
}