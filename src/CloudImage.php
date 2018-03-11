<?php 

namespace Clover;
/**
 * Clover s3 image
 * Upload Image to amazon s3 cloud servce
 */
use Storage;
use Exception;

abstract class CloudImage
{
	protected $filename;
	protected $directory;
	protected $url = 'https://images.iclover.net/';
	protected $builder;

	abstract public function builder();

	function __construct()
	{
		$this->directory = env('AWS_DIRECTORY', 'clover').'/';
		$this->builder = $this->builder();
	}


	public function store($file) 
	{
		$this->upload($file);
		$this->storeRecord();
		return $this->filename;
	}

	public function rollback() 
	{
		$this->delete();
		$this->deleteRecord();
	}

	public function upload($file, $filename = null) 
	{
		$this->filename = empty($filename)? str_random(64). '.'. $file->clientExtension(): $filename;
		Storage::disk('s3')->put(
			$this->path(), 
			file_get_contents($file->getRealPath())
		);
	}

	public function delete($filename = null) 
	{
		Storage::disk('s3')->delete($this->path($filename));
		$this->deleteRecordByiFilename($filename);
	}

	public function has($filename) 
	{
		return Storage::disk('s3')
			->has($this->path($filename));
	}

	public function get($filename) 
	{
		return Storage::has('public/'. $filename)? asset('storage/'. $filename): $this->url($filename);
	}

	public function url($filename) 
	{
		return $this->url. $this->path($filename);
	}

	public function path($filename = null) 
	{
		return !empty($filename)? $this->directory. $filename: $this->directory. $this->filename;
	}


	protected function storeRecord() 
	{
		$this->builder->filename = $this->filename;
		$this->builder->save();
	}

	protected function deleteRecord() 
	{
		$this->builder->delete();
	}

	protected function deleteRecordByiFilename($filename) 
	{
		$this->builder->whereFilename($filename)->delete();
	}
}
