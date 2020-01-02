<?php

namespace Popcorn\PHPLambda;

use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class ZipFactory
{
	protected $zipName;
	protected $dirName;
	protected $zip;

	public function __construct($dirname, array $options = [])
	{
		$this->dirName = realpath($dirname);
		$this->options = $options;
		$this->zip = new ZipArchive;
	}

	public function zip($zipName, $exclude = [])
	{
		print_r($this->dirName);
		//Set output zip name, and zip options
		$this->zip->open($zipName, ZipArchive::CREATE | ZipArchive::OVERWRITE);

		// Create recursive directory iterator
		/** @var SplFileInfo[] $files */
		$files = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($this->dirName),
			RecursiveIteratorIterator::LEAVES_ONLY
		);

		//Add all files in directory to the zip resource
		foreach ($files as $name => $file) {
			// Skip directories (they would be added automatically)
			if (!$file->isDir()) {
				// Get real and relative path for current file
				$filePath = $file->getRealPath();
				$relativePath = substr($filePath, strlen($this->dirName) + 1);


				//Build the zip file, passing in the output dir if set
				if (isset($this->options['outputDir'])) {
					echo "";
					$this->zip->addFile($filePath, $this->options['outputDir'] . '/' . $relativePath);
				} else {
					$this->zip->addFile($filePath, $relativePath);
				}
			}
		}
		$this->zip->close();
	}
}
