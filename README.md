PHP-File-Crawler
================

PHP based file crawler initially for archiving data off old hard drives for a client


Goals
-----

- file crawler with robust filtering (directory, filename, directory depth, etc)
- aggregator to give stats and breakdowns (# files, folders, per extension, etc)
- reporting for dumping this to different formats (csv, email, etc)

To Do
-----

- move filtering out, maybe a Strategy pattern
- memory handling on a BIG scan (think linked to multiple NAS or something)
- no reporting yet
- SimpleObserver needs to handle duplicates properly

Progress Notes
--------------

<p>
Observer pattern in full force. Need to clean up the data exposure though. Not
happy with the STATUS_* constants being in the Observed interface. Need a more
detailed abstract observer to handle real data crunching.
</p>
<p>
Include/exclude purged form current working version DirectorySearch but new
version is solid and laid out much better. Will add including/excluding back
next.
</p>


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