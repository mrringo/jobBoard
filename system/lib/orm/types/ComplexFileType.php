<?php

class SJB_ComplexFileType extends SJB_Type
{
	private $files;
	private $complexEnum;
	private $values;
	
	function SJB_ComplexFileType($property_info)
	{
		parent::SJB_Type($property_info);
		$this->default_template = 'file.tpl';
	}

	function isEmpty() 
	{
		if ($this->getComplexParent()) {
			return !SJB_UploadFileManager::isFileReadyForUpload($this->getComplexParent());
		}
		return parent::isEmpty() && !SJB_UploadFileManager::isFileReadyForUpload($this->property_info['id']);
	}
	
	function getPropertyVariablesToAssign()
	{
		$upload_manager = new SJB_UploadFileManager();
		$upload_manager->setFileGroup("files");
		if (!empty($this->values))
			$this->property_info['value'] = $this->values;
		if (is_array($this->property_info['value'])) {
			$value = array();
			foreach ($this->property_info['value'] as $key => $fileId) {
				$value[$key] = array(
							'file_url' => $upload_manager->getUploadedFileLink($fileId),
							'file_name' => $upload_manager->getUploadedFileName($fileId),
							'saved_file_name' => $upload_manager->getUploadedSavedFileName($fileId),
							'file_id' => $fileId
						);
			}
			return array(
				'id' 	=> $this->property_info['id'],
				'filesInfo' => $value,
				'value' => $value
			);
		}
		
		$value = array(
			'file_url'        => $upload_manager->getUploadedFileLink($this->property_info['value']),
			'file_name'       => $upload_manager->getUploadedFileName($this->property_info['value']),
			'saved_file_name' => $upload_manager->getUploadedSavedFileName($this->property_info['value']),
			'file_id'         => $this->property_info['value'],
		);
		
		return array(
			'id'        => $this->property_info['id'],
			'filesInfo' => $value,
			'value'     => $value
		);
	}

	function getValue()
	{
        $upload_manager = new SJB_UploadFileManager();
		if (!empty($_FILES[$this->getComplexParent()]['name'][$this->property_info['id']])) {
			if (!empty($this->property_info['value']))
				$this->values = $this->property_info['value'];
			$files = $_FILES[$this->getComplexParent()]['name'][$this->property_info['id']];
			$this->files = $_FILES[$this->getComplexParent()];
			return $files;
		}
		elseif (is_array($this->property_info['value'])) {
			$value = array();
			foreach ($this->property_info['value'] as $key => $fileId) {
				$value[$key] = array(
							'file_url' => $upload_manager->getUploadedFileLink($fileId),
							'file_name' => $upload_manager->getUploadedFileName($fileId),
							'saved_file_name' => $upload_manager->getUploadedSavedFileName($fileId),
							'file_id' => $fileId,
						);
			}
			return $value;
		}
		return array(
			'file_url' 	=> $upload_manager->getUploadedFileLink($this->property_info['value']),
			'file_name' => $upload_manager->getUploadedFileName($this->property_info['value']),
			'saved_file_name' => $upload_manager->getUploadedSavedFileName($this->property_info['value']),
			'file_id' => $this->property_info['value'],
		);
	}

	function isValid()
	{
		$upload_manager = new SJB_UploadFileManager();
		if (!empty($this->property_info['max_file_size'])) 
			$upload_manager->setMaxFileSize($this->property_info['max_file_size']);

		if ($this->getComplexParent()) {
			if ($upload_manager->isValidUploadedFile($this->getComplexParent(), $this->property_info['id']))
				return true;
		} else if ($upload_manager->isValidUploadedFile($this->property_info['id'])) {
			return true;
		}

		return $upload_manager->getError();
	}
	
	function getSQLValue($file_id = null)
	{
		// fix to allow checks unique file id (fix for ajax_file_upload_handler.php and complexfile)
		if ($file_id === null) {
			$file_id = $this->getComplexParent().":".$this->property_info['id'].":". $this->complexEnum . "_" .$this->object_sid;
		}
		if (isset($this->files)){
			foreach ($this->files as $key => $value) {
				$_FILES[$this->property_info['id']][$key] = $value[$this->property_info['id']][$this->complexEnum];
			}
		}
		$this->property_info['value'] = $file_id;
		$this->values[$this->complexEnum] = $file_id;
		$upload_manager = new SJB_UploadFileManager();
		$upload_manager->setFileGroup("files");
		$upload_manager->setUploadedFileID($file_id);
		$upload_manager->uploadFile($this->property_info['id']);
		if (SJB_UploadFileManager::doesFileExistByID($file_id)) {
			return $file_id;
		}
		return '';
	}

	public static function getFieldExtraDetails()
	{
		return array(
			array(
				'id'		=> 'max_file_size',
				'caption'	=> 'Maximum File Size', 
				'comment'   => 'Server configuration upload max filesize '.ini_get('upload_max_filesize'),
				'type'		=> 'float',
				'length'	=> '20',
				'minimum'	=> '0',
				'signs_num' => '2',
				),
		);
	}
	
	function setComplexEnum($value) 
	{
		$this->complexEnum = $value;
	}
	
	function getKeywordValue()
	{
		$keywords = '';
		if (!self::isEmpty() && SJB_Settings::getSettingByName('get_keyword_from_file')) {
			$fileId = $this->getComplexParent().":".$this->property_info['id'].":". $this->complexEnum . "_" .$this->object_sid;
			$fileInfo = SJB_UploadFileManager::getUploadedFileInfo($fileId);
			if ($fileInfo) {
				$uploadManager = new SJB_UploadFileManager();
				$uploadManager->setFileGroup("files");
				$fileUrl = $uploadManager->getUploadedFileLink($fileId, $fileInfo, true);
				$fileExtension = substr(strrchr($fileInfo['saved_file_name'], "."), 1);
				if (file_exists($fileUrl)) {
					switch ($fileExtension) {
						case 'doc':
							$doc = new doc();
						    $doc->read($fileUrl);
						    $keywords = preg_replace('/[\n\r]/', '', strip_tags($doc->parse()));
							break;
						case 'docx':
							$keywords = SJB_HelperFunctions::docx2text($fileUrl);
							$keywords = preg_replace('/[\n\r]/', '', strip_tags(html_entity_decode($keywords)));
							break;
						case 'xls':
						case 'xlsx':
							$fileInfo['tmp_name'] = $fileUrl;
							$fileObj = new SJB_ImportFileXLS($fileInfo);
							$fileObj->parse();
							$data = $fileObj->getData();
							$keywords = '';
							foreach ($data as $val) {
								$val = array_unique($val);
								$val = array_diff($val, array(''));
								$keywords .= implode(' ', $val);
							}
							$keywords = preg_replace("/[[:punct:]^\s]/ui", " ", $keywords);
							break;
						case 'pdf':
							$outFilename = str_replace(".".$fileExtension, '.txt', $fileUrl);
							exec("pdftotext {$fileUrl} {$outFilename}");
							if (file_exists($outFilename)) {
								$keywords = file_get_contents($outFilename);
								$keywords = preg_replace('/[\n\r]/', '', strip_tags(html_entity_decode($keywords)));
								unlink($outFilename);
							}
							break;
						case 'txt':
							$keywords = file_get_contents($fileUrl);
							$keywords = preg_replace('/[\n\r]/', '', strip_tags(html_entity_decode($keywords)));
							break;
					}
				}
			}
		}
		return $keywords;
	}
}
