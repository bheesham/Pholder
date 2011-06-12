<?php
/*
	The one file folder manager.
	Copyright (C) 2011  Bheesham Persaud

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */


// a request for css / javascript
if ( isset( $_GET['res'] ) ) {
	switch( $_GET['res'] ) {
		case '.css':
			header( 'Content-Type: text/css' );
			echo "body { width: 600px; margin-left: auto; margin-right: auto; } \r\n";
			echo "h1,h2,h3,h4 { margin: 0px; padding: 0px; color: #555555; } \r\n";
			echo "ul { list-style: none; display: inline; }";
			break;
		case '.js':
			header( 'Content-Type: text/javascript' );
			break;
		default:
			break;
	}
	exit;
}

// takes away the slash at the end of a string
function end_slash( $str ) {
	if ( substr( $str, -1, 1 ) == '/' ) {
		$str = substr( $str, 0, -1 );
	}
	return $str;
}

// takes away the slash at the begining and at the end of a string
function slash( $str ) {
	if ( $str == null ) {
		return null;
	}
	if ( substr( $str, 0, 1 ) == '/' ) {
		$str = substr( $str, 1 );
	}
	if ( substr( $str, -1, 1 ) == '/' ) {
		$str = substr( $str, 0, -1 );
	}
	return $str;
}

// root directory
$root = dirname(__FILE__);
$root = end_slash( $root );

// current directory
$cd = ( isset( $_GET['cd'] ) && !empty( $_GET['cd'] ) ) ? ( $_GET['cd'] ) : null;
$cd = slash( $cd );

# root/current directory/  (root with current directory appended)
if ( $cd != null ) {
	$root_cd = "$root/$cd/";
} else {
	$root_cd = "$root/";
}

// location of the upper directory
if ( $cd != null ) {
	$up = realpath( $root_cd . '/..' );
	$up = str_replace( $root, '', $up );
	$up = end_slash( $up );
}

$folders = array();
$files = array();

# iterate over the folder, separate files and folders
if ( $handle = opendir( $root_cd ) ) {
    while ( false !== ( $file = readdir( $handle ) ) ) {
        if ( is_dir( $root_cd . $file ) ) {
			if ( $file == '.' || $file == '..' ) {
				continue;
			}
			$folders[ $root_cd . $file ] = array(
										md5( $root_cd . $file ), # used for the name and id
										urlencode( $cd . '/' . $file ), 
										substr( sprintf( '%o', fileperms( $root_cd . $file ) ), -4 ),
										fileatime( $root_cd . $file ),
									);
		} else {
			$files[ $file ] = array(
									md5( $root_cd . $file ), # used for the name and id
									filesize( $root_cd . $file ),
									substr( sprintf( '%o', fileperms( $root_cd . $file ) ), -4 ),
									fileatime( $root_cd . $file ),
									);
		}
    }
	closedir( $handle );
}
?>
<!doctype html> 
<html lang="en"> 
	<head>
		<title>Pholder</title> 
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
		<link rel="stylesheet" type="text/css" href="?res=.css" /> 
		<script type="text/javascript" src="?res=.js"></script> 
	</head>
	<body>
		<h1>Pholder</h1>
		<h2><?php echo $cd; ?></h2>
		<ul>
			<?php if ( $cd != null ) { ?> <li><a href="?cd=<?php echo $up; ?>">Up a directory</a></li> <?php } ?>
			<?php foreach ( $folders as $folder => $info ) { ?>
				<li><input type="checkbox" name="folder[<?php echo $info[0]; ?>]" id="folder[<?php echo $info[0]; ?>]" /><label for="folder[<?php echo $info[0]; ?>]"><a href="?cd=<?php echo $info[1]; ?>"><?php echo $folder; ?></a> - Perms: <?php echo $info[2]; ?> - <?php echo date( "F d Y H:i:s.", $info[3] ); ?></label></li>
			<?php } ?>
			<?php foreach ( $files as $file => $info ) { ?>
				<li><input type="checkbox" name="file[<?php echo $info[0]; ?>]" id="file[<?php echo $info[0]; ?>]" /><label for="file[<?php echo $info[0]; ?>]"><?php echo $file; ?> - (<?php echo $info[1]; ?> KB) - Perms: <?php echo $info[2]; ?> - <?php echo date( "F d Y H:i:s.", $info[3] ); ?></label></li>
			<?php } ?>
		</ul>
	</body>
</html>