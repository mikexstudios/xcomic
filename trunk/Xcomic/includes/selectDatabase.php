<?php
/***************************************************************************
 *                                 db.php
 *                            -------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id$
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

if ( !defined('IN_XCOMIC') )
{
	die("Hacking attempt");
}

switch($dbms)
{
	case 'mysql':
		include($xcomicRootPath . 'includes/Database/mysql.'.$phpEx);
		break;

	case 'mysql4':
		include($xcomicRootPath . 'includes/Database/mysql4.'.$phpEx);
		break;

	case 'postgres':
		include($xcomicRootPath . 'includes/Database/postgres7.'.$phpEx);
		break;

	case 'mssql':
		include($xcomicRootPath . 'includes/Database/mssql.'.$phpEx);
		break;

	case 'oracle':
		include($xcomicRootPath . 'includes/Database/oracle.'.$phpEx);
		break;

	case 'msaccess':
		include($xcomicRootPath . 'includes/Database/msaccess.'.$phpEx);
		break;

	case 'mssql-odbc':
		include($xcomicRootPath . 'includes/Database/mssql-odbc.'.$phpEx);
		break;
}

?>