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

// --- CONFIG ---

// Set a password here so that outsiders don't intrude.
// Leave it blank if you want it to be public.
$password = 'blah';

// Do not ed... go ahead. Edit it.

$root = getcwd();


// add code for directory changes here!

// add code for sorting properties here!

if ( $hand = opendir( $root ) ) {
    while (false !== ($file = readdir($hand))) {
       if ( is_dir( $file ) ) {
			echo "Folder: $file \r\n";
	   } else {
			echo "File: $file \r\n";
	   }
    }
}
?>