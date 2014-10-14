PHP-File-Crawler
================

PHP based file crawler initially for archiving data off old hard drives for a client


Goals
-----

- file crawler with filtering by directory and filename (regex & simple list)
- data handler to handle the full file names (iterable with helpers/filters)
- aggregator to give stats and breakdowns (# files, folders, per extension, etc)

To Do
-----

- split denied from doesn't exist on directory scan
- better state handling/passing, don't like the STATUS_* constants
- move filtering out, maybe a Strategy pattern
- memory handling on a BIG scan (think linked to multiple NAS or something)

Progress Notes
--------------

Simple observer pattern in place with basic data handler (simple array for now)
File includes and directory excludes in place (using regex)
detecting and ignoring symlinks when scanning (via param, ignored by default)

Example File Match
------------------

Kind of a "find anything the client may want to keep" match criteria. With a criteria this loose you'll want to be limiting the directories you search.

```php
$file_includes = array( 
	'/\.jpg$/i',  
	'/\.jpeg$/i', 
	'/\.gif$/i', 
	'/\.tif$/i', 
	'/\.tiff$/i', 
	'/\.png$/i', 
	'/\.psd$/i', 
	'/\.doc$/i', 
	'/\.docx$/i', 
	'/\.mp4$/i', 
	'/\.mpg$/i', 
	'/\.mov$/i', 
	'/\.wmv$/i', 
	'/\.pdf$/i', 
	'/\.xls$/i', 
	'/\.xlsx$/i', 
	'/\.zip$/i', 
);
```

Example Directory Exclude
-------------------------

This one is just blocking hidden directories. Would be fairly easy to expand that though.

```php
$dir_excludes = array( 
	'/^\./', 				# excluding any hidden directories
);
```