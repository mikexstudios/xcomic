<?php
/**
Xcomic

//Template-
//Wrapper class for the underlying template

$Id$
*/

//Xcms settings
define('IN_XCOMIC', true);
//$xcmsRootPath = "./";

//Include YapterX
include_once('Template/YapterX.'.$classEx);

				
class Template {
	
	//Class variables
	var $extTemplateObj; //Object for the external template system
	
	var $templatesRootDir; //Specifies the root directory for the templates
	
	function Template() {
		//Construct the external Template object
		//In this case, a Yapter object
		$this->extTemplateObj = new YapterX();
	}
	
	//For assigning a template var or array.
	function setVars($nameOrArray, $value=NULL) {
		
		if(is_array($nameOrArray))
		{
			//Send the template variables into assign_vars
			$this->extTemplateObj->setVars($nameOrArray);	
		}
		//Else, it is a variable
		else if(!empty($nameOrArray)&&!empty($value))
		{
			$this->extTemplateObj->set($nameOrArray, $value);
		}
	}
	
	function setRootPath($inRootDir) {
	
		$this->templatesRootDir = $inRootDir;
		
	}
	
	function getRootPath() {
	
		return $this->templatesRootDir;	
	}
	
	function setFile($inTemplateName, $inFilename) {
		
		//echo $this->templatesRootDir.'/'.$inFilename;
		$this->extTemplateObj->setTemplateFile($inTemplateName, $this->templatesRootDir.'/'.$inFilename);
		
	}
	
	//Returns the contents
	function parse($inTemplateName) {
		
		$this->extTemplateObj->parse($inTemplateName);
		return $this->extTemplateObj->getContents($inTemplateName);
		
	}

}

/*
//Test Template
$x = new Template();
$x->setRootPath('./templates/mXtenchi');
$x->setFile('content_page', 'content_page.tpl');
$x->setVars('PAGE_CONTENT','If this shows up, then it worked.');
$x->setVars('PAGE_HEADER','Header');
echo $x->parse('content_page');
*/



?>