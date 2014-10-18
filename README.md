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

- memory handling on a BIG scan (think linked to multiple NAS or something)
- no reporting yet
- SimpleObserver needs to handle duplicates properly

Progress Notes
--------------

<p>
File filtering in place now (default filter is everything). Includes C/M/A
times, link and regex for filenames. This is using an interface to decouple
and a simple class implementation to handle the bulk of the work. Need to use
another of these for directory excluding. Same one would work most likely.
</p>
<p>
New method crawls through entire drives like a beast but memory is going to be
an issue if the Observers store too much data (entire SplFileInfo for example).
Also, nesting can be an issue (had to bump xdebug up to 200) since there are
about three function levels per directory level.
</p>


Example
-------

Kind of a "find anything the client may want to keep" match criteria.
```php
	$patterns = array(
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
		'/\.zap$/i',
	);
	$filter = new php_file_crawler\includes\FileInfoFilterBase();
	// this clears any previous patterns
	$filter->setRegExes( $patterns );
	// can add/remove after though (or skip the bulk thing entirely)
	$filter->addRegEx( '/\.htm[l]*$/i' );
	$filter->addRegEx( '/\.css$/i' );
	$filter->removeRegEx( '/\.zap$/i' );
	// only files modifed in the last 30 days
	$filter->setMTimeAfter( time() - ( 60 * 60 * 24 * 30 ) );
	// create our search
	$search = new php_file_crawler\DirectorySearch( $filter );
	// subscribe an observer
	$matched = new php_file_crawler\MatchedObserver( $search );
	// do some searches
	$search->scanDirectory( '/home/thomas/Downloads' );
	$search->scanDirectory( '/home/thomas/Documents' );
	// the matcher is only logging the matches so display them
	$line = 0;
	foreach( $matched->getResults() as $data ) {
		printf(
			'%04d: %s %10s %s' . PHP_EOL,
			++$line,
			($data->getFileInfo()->IsLink() ? 'Y' : 'N' ),
			$data->getStatus(),
			$data->getFileInfo()->getPathname()
		);
	}

```
