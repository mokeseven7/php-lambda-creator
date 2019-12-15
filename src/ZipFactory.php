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
		$this->dirName = $dirname;
		$this->zip = new ZipArchive;
	}

	public function zip($zipName, $exclude = [])
	{
		//Get full path of directory to zip
		$directoryPath = realpath($this->dirName);

		//Set output zip name, and zip options
		$this->zip->open($zipName, ZipArchive::CREATE | ZipArchive::OVERWRITE);

		// Create recursive directory iterator
		/** @var SplFileInfo[] $files */
		$files = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($directoryPath),
			RecursiveIteratorIterator::LEAVES_ONLY
		);

		//Add all files in directory to the zip resource
		foreach ($files as $name => $file) {
			// Skip directories (they would be added automatically)
			if (!$file->isDir()) {
				// Get real and relative path for current file
				$filePath = $file->getRealPath();
				$relativePath = substr($filePath, strlen($directoryPath) + 1);

				// Add current file to archive
				$this->zip->addFile($filePath, $relativePath);
			}
		}
		$this->zip->close();
	}
}



// // Enter the name of directory 
// $pathdir = "Directory Name/";  
// // Enter the name to creating zipped directory 
// $zipcreated = "Name of Zip.zip";  
// // Create new zip class 
// $zip = new ZipArchive; 
// if($zip -> open($zipcreated, ZipArchive::CREATE ) === TRUE) {  
//     // Store the path into the variable 
//     $dir = opendir($pathdir);   
//     while($file = readdir($dir)) { 
//         if(is_file($pathdir.$file)) { 
//             $zip -> addFile($pathdir.$file, $file); 
//         } 
//     } 
//     $zip ->close(); 
// } 
