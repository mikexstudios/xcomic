<?php
/**
Xcomic

$Id$
*/

//Xcomic settings
define('IN_XCOMIC', true);
$xcomicRootPath = "../";

require_once($xcomicRootPath.'initialize.php');	//Include all page common settings
							//Creates $xcomicDb connection. Grabs config info.
include_once('UserManagement.'.$classEx); //Login/Logout
include_once($xcomicRootPath.'includes/Security.'.$classEx);
include_once('AdminDisplay.'.$classEx);


								
class XcomicAdminDriver {
	
	var $security; //Object
	var $userManagement;
	var $mode; //Input
	var $adminDisplay; //Object
	var $adminDir = 'admin';
	var $thisFilename = 'index.php';
	
	//Constructor
	function XcomicAdminDriver() {
		global $xcomicTemplate, $xcomicRootPath, $message;
		
		//Create Security object
		$this->security = new Security();
		//Create User Management object
		$this->userManagement = new UserManagement();
		//Set message object to admin
		$message->setAdmin(true);
		
		$this->mode=(!empty($_REQUEST[MODE_INURL])) ? $this->security->allowOnlyChars($_REQUEST[MODE_INURL]) : 'main'; //Default to main page
		
		//Set global template variables
		$xcomicTemplate->setVars(array(
			'ACTION_URL' => $this->thisFilename,
			'PATH_TO_ADMIN_TEMPLATES' => $xcomicRootPath.'templates/admin',
			'POST_COMIC_LINK' => $this->thisFilename.'?mode=showpostcomic',
			'EDIT_COMIC_LINK' => $this->thisFilename.'?mode=showeditcomic',
			'COMIC_STATUS_LINK' => $this->thisFilename.'?mode=showcomicstatus',
			'POST_NEWS_LINK' => $this->thisFilename.'?mode=showpostnews',
			'EDIT_NEWS_LINK' => $this->thisFilename.'?mode=showeditnews',
			'NEWS_CAT_LINK' => $this->thisFilename.'?mode=shownewscat',
			'USERS_LINK' => $this->thisFilename.'?mode=showusers',
			'OPTIONS_LINK' => $this->thisFilename.'?mode=showoptions',
			'LOGOUT_LINK' => $this->thisFilename.'?mode=logout'
			)
		);
		
		//Peliminary mode switch - for functions not requiring admin access
		switch($this->mode) 
		{
			case 'login':
				$this->processLogin();
				break;
			case 'showregister':
				$this->showRegisterUser();
				break;
			case 'register':
				$this->registerUser();
				break;
		}

		
		//Check to see if user has the privelidge to access this
		if($this->userManagement->isAlreadyLoggedIn())
		{	
			//User has access. Execute the script
			$this->executeMode();
		}
		else
		{
			//User does not have access. Display login page.
			$this->showLogin();
		}

	}
	
	
	function executeMode() {
		//Post Comic Edit Comic Post News Edit News Users Options Logout
			switch($this->mode) 
			{
				/* Make main page the show post comic page
				case 'main':
					$this->showMainPage();
					break;
				*/
					
				//--------------------
				case 'showpostcomic':
					$this->showPostComic();
					break;
					/*
				case 'showeditcomic':
					$this->showEditComic();
					break;
					*/
				case 'postcomic':
					$this->postComic();
					break;
					/*
				case 'editcomic':
					$this->editComic();
					break;
					*/
				case 'showcomicstatus':
					$this->showComicStatus();
					break;
				case 'changecomicstatus':
					$this->changeComicStatus();
					break;
				//--------------------
				
				//--------------------
				case 'showpostnews':
					$this->showPostNews();
					break;
					/*
				case 'showeditnews':
					$this->showEditNews();
					break;
					*/
				case 'postnews':
					$this->postNews();
					break;
					/*
				case 'editnews':
					$this->editNews();
					break;
					*/
					/*
				case 'shownewscat':
					$this->showNewsCat();
					break;
					*/
					/*
				case 'editnewscat':
					$this->editNewsCat();
					break;
					*/
				//--------------------
				
				
				case 'showusers':
					$this->showUsers();
					break;
				case 'adduser':
					$this->addUser();
					break;
				case 'deleteuser':
					$this->deleteUser();
					break;
					/*
				case 'editusers':
					$this->editUsers();
					break;
				case 'showoptions':
					$this->showOptions();
					break;
					*/
				case 'logout':
					$this->logout();
					break;
					
				//----------------------------
				default:
					//Make showPostComic the main page
					$this->showPostComic();	
			}	
		
	}
	
	function showLogin() {
		global $xcomicTemplate;
		
		$xcomicTemplate->setVars(array(
			'SECTION_TITLE' => 'Login',
			'MODE' => 'login',
			'INLOGIN_USERNAME' => AUTHIN_USERNAME,
			'INLOGIN_PASSWORD' => AUTHIN_PASSWORD
			)
		);
		
		$adminDisplay = new AdminDisplay($this->adminDir.'/loginBox.tpl', 'outsideHeader');
		$adminDisplay->showFullPage();	
	}
	
	function processLogin() {
		global $message;
		
		//Get input from form
		$inUsername=(!empty($_REQUEST[AUTHIN_USERNAME])) ? $this->security->allowOnlyChars($_REQUEST[AUTHIN_USERNAME]) : NULL;
		$inPassword=(!empty($_REQUEST[AUTHIN_PASSWORD])) ? $this->security->allowOnlyChars($_REQUEST[AUTHIN_PASSWORD]) : NULL;
		
		//Set them in User Management
		$this->userManagement->setUsername($inUsername);
		$this->userManagement->setPassword($inPassword);	
		
		//Process login information
		if($this->userManagement->processLogin('remember'))
		{
			$message->say('You have been successfully logged in.');
		}
		else
		{
			$message->setAdminHeader('outsideHeader');
			$message->error('Login failure: Incorrect username or password.');
		}
		
	}
	
	function logout() {
		global $message;
		
		$this->userManagement->logout();
		
		//Set a new header for message
		$message->setAdminHeader('outsideHeader');
		$message->say('You have been successfully logged out. Click <a href="'.$this->thisFilename.'">here to log back in</a>.');
		
	}
	
	function showPostComic() {
		global $xcomicTemplate;
		
		$xcomicTemplate->setVars(array(
			'SECTION_TITLE' => 'Post New Comic',
			'MODE' => 'postcomic',
			)
		);
		
		$adminDisplay = new AdminDisplay($this->adminDir.'/postComic.tpl');
		$adminDisplay->showFullPage();	
	}
	
	function showPostNews() {
		global $xcomicTemplate, $classEx;
		
		$categoryOptionsCode=''; //HTML options code for each category
		
		include_once('NewsCategories.'.$classEx);
		
		//Get news categories
		$newsCategories = new NewsCategories();
		$catList = $newsCategories->getCategoryNames();
		
		reset($catList);
		
		//Get the first one and set it to selected
			list($key, $value) = each($catList);
			$categoryOptionsCode .= '<option name="'.$value.'" selected>'.$value."</option>\n";
		
		while(list($key, $value) = each($catList))
		{
			$categoryOptionsCode .= '<option name="'.$value.'">'.$value."</option>\n";
		}
		
		$xcomicTemplate->setVars(array(
			'SECTION_TITLE' => 'Post New News',
			'MODE' => 'postnews',
			'CATEGORY_OPTIONS' => $categoryOptionsCode,
			'TITLE' => '',
			'CONTENT' => '',
			)
		);
		
		$adminDisplay = new AdminDisplay('admin/postNews.tpl');
		$adminDisplay->showFullPage();	
	}
	
	function postNews() {
		global $message, $classEx;
		
		include_once('PostNews.'.$classEx);
		
		$newsTitle=(!empty($_REQUEST['title'])) ? $this->security->secureText($_REQUEST['title']) : NULL;
		$newsCategory=(!empty($_REQUEST['category'])) ? $this->security->secureText($_REQUEST['category']) : 'default'; //Default
		$newsContent=(!empty($_REQUEST['content'])) ? $this->security->secureText($_REQUEST['content']) : NULL;
		
		//Check for error
		if($newsTitle==NULL)
			$message->error('The news title was left blank. Please click back and fill it in.');

		if($newsContent==NULL)
			$message->error('The news content was left blank. Please click back and fill it in.');
		
		//Texturize. Convert into HTML
		include_once('Syntax.'.$classEx);
		$syntax = new Syntax();
		$newsContent = $syntax->parse($newsContent);
		
		//Actually post the news
		$postNews = new PostNews($newsContent, $newsTitle, $newsCategory, $this->userManagement->getUsername());
		$postNews->sendToDatabase();
		
		//Display success page
		$message->say('News has been sucessfully posted.');		
	}
	
	function postComic() {
		global $message, $classEx;
		
		include_once('PostComic.'.$classEx);
		
		$comicTitle=(!empty($_REQUEST['title'])) ? $this->security->secureText($_REQUEST['title']) : NULL;
		$comicFile=(!empty($_FILES['comicFile'])) ? $_FILES['comicFile'] : NULL; //Default to left
		
		//Check for error
		if($comicTitle==NULL)
			$message->error('The comic title was left blank. Please click back and fill it in.');

		if($comicFile==NULL)
			$message->error('No comic file was uploaded. Please click back and correct this mistake.');
		
		//echo $comicFile['tmp_name'];
		
		//Actually post the news
		$postComic = new PostComic($comicFile, $comicTitle);
		if($postComic->saveFile())
			$postComic->sendToDatabase();
		
		//Display success page
		$message->say('The comic has been sucessfully posted.');		
	}
	
	function showUsers() {
		global $xcomicDb, $xcomicTemplate, $message;
		
		$listOfUsers=''; //HTML code for the list of users
		
		//Get list of users
		$sql = 'SELECT username 
			FROM '.XCOMIC_USERS_TABLE.';';

		if( !($result = $xcomicDb->sql_query($sql)) )
		{
			$message->error("Could not get users list.");
		}
		
		while ( $row = $xcomicDb->sql_fetchrow($result) )
		{
			//For each user, generate HTML
			$listOfUsers.='
			<tr class="each-user">
				<td class="list-username">'.$row['username'].'</td>
				<td class="list-userfunc"><a href="'.$this->thisFilename.'?mode=edituser&'.GETIN_USERNAME.'='.$row['username'].'">Edit</a></td>
				<td class="list-userfunc"><a href="'.$this->thisFilename.'?mode=deleteuser&'.GETIN_USERNAME.'='.$row['username'].'">Delete</a></td>
			</tr>
			';
		}
		
		$xcomicTemplate->setVars(array(
			'SECTION_TITLE' => 'User Functions',
			'MODE' => 'adduser',
			'LIST_OF_USERS' => $listOfUsers,
			'ADDIN_USERNAME' => ADDIN_USERNAME,
			'ADDIN_PASSWORD' => ADDIN_PASSWORD,
			'ADDIN_EMAIL' => ADDIN_EMAIL
			)
		);
		
		$adminDisplay = new AdminDisplay('admin/showUsers.tpl');
		$adminDisplay->showFullPage();	
	}
	
	function addUser() {
		global $message;
		
		$username=(!empty($_REQUEST[ADDIN_USERNAME])) ? $this->security->secureText($_REQUEST[ADDIN_USERNAME]) : NULL;
		$password=(!empty($_REQUEST[ADDIN_PASSWORD])) ? $this->security->secureText($_REQUEST[ADDIN_PASSWORD]) : NULL;
		$email=(!empty($_REQUEST[ADDIN_EMAIL])) ? $this->security->allowOnlyEmail($_REQUEST[ADDIN_EMAIL]) : NULL;
		
		//Check for error
		if(empty($username))
			$message->error('The username was left blank. Please click back and fill it in.');
		if(empty($password))
			$message->error('The password was left blank. Please click back and fill it in.');
		if(empty($email))
			$message->error('The email address was left blank. Please click back and fill it in.');
		
		//Register user
		$registerUser = new UserManagement($username, $password);
		$registerUser->registerUser();
		
		//Add extra info
		$registerUser->editUserInfo($email);
			
		//Display success
		$message->say('New user has been sucecssfully added.');
	}
	
	function deleteUser() {
		global $message;
		
		$username=(!empty($_REQUEST[GETIN_USERNAME])) ? $this->security->allowOnlyChars($_REQUEST[GETIN_USERNAME]) : NULL;
		
		//Check for error
		if(empty($username))
			$message->error('The username was left blank or invalidly filled.');
		
		//Delete user using User Management class
		$deleteUser = new UserManagement($username);
		$deleteUser->deleteUser();
		
		//Display success
		$message->say('User has been sucecssfully deleted.');
	}
	
	function showComicStatus() {
		global $xcomicRootPath, $xcomicTemplate, $classEx;
		
		include_once($xcomicRootPath.'includes/ComicStatusDisplay.'.$classEx);
		
		$statusDisplay = new ComicStatusDisplay();
		
		//Get date
		$date = getdate($statusDisplay->getNextDate());
		
		$xcomicTemplate->setVars(array(
			'SECTION_TITLE' => 'Next Comic Status',
			'MODE' => 'changecomicstatus',
			'NEXT_COMIC_MONTH' => NEXT_COMIC_MONTH,
			'NEXT_COMIC_DAY' => NEXT_COMIC_DAY,
			'NEXT_COMIC_YEAR' => NEXT_COMIC_YEAR,
			'NEXT_COMIC_HOUR' => NEXT_COMIC_HOUR,
			'NEXT_COMIC_MINUTES' => NEXT_COMIC_MINUTES,
			'NEXT_COMIC_PERCENT' => NEXT_COMIC_PERCENT,
			'NEXT_COMIC_COMMENT' => NEXT_COMIC_COMMENT,
			'CURR_COMIC_MONTH' => $date['mon'],
			'CURR_COMIC_DAY' => $date['mday'],
			'CURR_COMIC_YEAR' => $date['year'],
			'CURR_COMIC_HOUR' => $date['hours'],
			'CURR_COMIC_MINUTES' => $date['minutes'],
			'CURR_COMIC_PERCENT' => $statusDisplay->getPercentStatus(),
			'CURR_COMIC_COMMENT' => $statusDisplay->getComment()
			)
		);
		
		$adminDisplay = new AdminDisplay($this->adminDir.'/comicStatus.tpl');
		$adminDisplay->showFullPage();	
	}
	
	function changeComicStatus() {
		global $xcomicDb, $message, $classEx;
		
		$month=(!empty($_REQUEST[NEXT_COMIC_MONTH])) ? $this->security->allowOnlyNumbers($_REQUEST[NEXT_COMIC_MONTH]) : NULL;
		$day=(!empty($_REQUEST[NEXT_COMIC_DAY])) ? $this->security->allowOnlyNumbers($_REQUEST[NEXT_COMIC_DAY]) : NULL;
		$year=(!empty($_REQUEST[NEXT_COMIC_YEAR])) ? $this->security->allowOnlyNumbers($_REQUEST[NEXT_COMIC_YEAR]) : NULL;
		$hour=(!empty($_REQUEST[NEXT_COMIC_HOUR])) ? $this->security->allowOnlyNumbers($_REQUEST[NEXT_COMIC_HOUR]) : NULL;
		$minutes=(!empty($_REQUEST[NEXT_COMIC_MINUTES])) ? $this->security->allowOnlyNumbers($_REQUEST[NEXT_COMIC_MINUTES]) : NULL;
		$pctStatus=(!empty($_REQUEST[NEXT_COMIC_PERCENT])) ? $this->security->allowOnlyNumbers($_REQUEST[NEXT_COMIC_PERCENT]) : NULL;
		$comments=(!empty($_REQUEST[NEXT_COMIC_COMMENT])) ? $this->security->secureText($_REQUEST[NEXT_COMIC_COMMENT]) : NULL;
		
		//Check for error
		if(empty($month))
			$message->error('The month was left blank. Please click back and fill it in.');
		if(empty($day))
			$message->error('The day was left blank. Please click back and fill it in.');
		if(empty($year))
			$message->error('The year was left blank. Please click back and fill it in.');
		if(empty($hour))
			$message->error('The hour was left blank. Please click back and fill it in.');
		if(empty($minutes))
			$message->error('The minutes were left blank. Please click back and fill it in.');
		if(empty($pctStatus))
			$message->error('The percent status was left blank. Please click back and fill it in.');
			/*
		if(empty($comments))
			$message->error('The comments were left blank. Please click back and fill it in.');
			*/
		
		//Texturize. Convert into HTML
		include_once('Syntax.'.$classEx);
		$syntax = new Syntax();
		$comments = $syntax->parse($comments);
		
		//Set date
		$date = mktime ($hour,$minutes,0,$month,$day,$year);
		
		//Update status
		include_once('NextComicStatus.'.$classEx);
		$updateStatus = new NextComicStatus($date, $pctStatus, $comments);
		$updateStatus->changeStatus();
			
		//Display success
		$message->say('The next comic status has been sucecssfully changed.');
	}
	
	/*
	function showOptions() {
		global $xcomicTemplate;
		
		$xcomicTemplate->setVars(array(
			'SECTION_TITLE' => 'Next Comic Status',
			'MODE' => 'changecomicstatus',
			'NEXT_COMIC_MONTH' => NEXT_COMIC_MONTH,
			'NEXT_COMIC_DAY' => NEXT_COMIC_DAY,
			'NEXT_COMIC_YEAR' => NEXT_COMIC_YEAR,
			'NEXT_COMIC_HOUR' => NEXT_COMIC_HOUR
			)
		);
		
		$adminDisplay = new AdminDisplay($this->adminDir.'/showOptions.tpl');
		$adminDisplay->showFullPage();
	}
	*/
	
}


//Test XcomicDriver
$x = new XcomicAdminDriver();

?>