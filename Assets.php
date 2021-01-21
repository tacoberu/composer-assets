<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\ComposerScripts;

use Composer\Script\Event;
use LogicException;


/**
 * Copies files from vendor to www directory. The list of files is stored in a json file.
 * To composer.config append option 'www-dir' for location of www directory.
 * To composer.config append option 'assets-definition' for location of list of assets.
 */
class CopyAssetsToPublic
{
	static function process(Event $ev)
	{
		$vendorDir = $ev->getComposer()->getConfig()->get('vendor-dir');
		$wwwDir = self::requireWwwDir($ev->getComposer()->getConfig()->get('www-dir'));
		$definitionFile = self::requireLocation($ev->getComposer()->getConfig()->get('assets-definition'));

		// seskupení
		$tree = [];
		foreach (json_decode(file_get_contents($definitionFile)) as $src => $desc) {
			if ( ! array_key_exists($desc, $tree)) {
				$tree[$desc] = [];
			}
			$tree[$desc][] = $src;
		}

		// zápis podle skupin
		foreach ($tree as $desc => $items) {
			$ev->getIO()->write("\tcopy-to: '$desc'");
			$content = [];
			foreach ($items as $x) {
				$content[] = file_get_contents($vendorDir . '/' . $x);
			}
			file_put_contents(self::requireDir($wwwDir, dirname($desc)) . '/' . basename($desc), implode("\n\n\n", $content));
		}

		$ev->getIO()->write("\tdone");
	}



	private static function requireWwwDir($path)
	{
		if (file_exists($path)) {
			return realpath($path);
		}

		$path2 = realpath(__dir__ . '/../' . $path);
		if (file_exists($path2)) {
			return $path2;
		}

		throw new LogicException("Unknow path '$path'.");
	}



	private static function requireLocation($loc)
	{
		if (file_exists($loc)) {
			return realpath($loc);
		}
		throw new LogicException("Unknow location '$loc'.");
	}



	private static function requireDir($base, $path)
	{
		$paths = explode('/', $path);
		$ret = $base;
		foreach ($paths as $x) {
			$ret .= '/' . $x;
			if ( ! file_exists($ret)) {
				mkdir($ret);
			}
		}
		return $ret;
	}

}
