PHP-File-Crawler
================

PHP based file crawler initially for archiving data off old hard drives for a client


Goals
-----

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
New method crawls through entire drives like a beast but memory is going to be
an issue if the Observers store too much data (entire SplFileInfo for example).
Also, nesting can be an issue (had to bump xdebug up to 200) since there are
about three function levels per directory level.
</p>


Example
-------

There are two filters to create. Just creating filters and using them will
get you everything. In most cases you'll want to at least exclude linked
directories and add some kind of filtering to your files (extension for example).

Below is a simple run to get an idea. It will find all .pdf and .doc files in
my Downloads and Documents directories. It will skip any hidden directories,
linked directories, any directories named "extract" and will ignore anything
deeper than 7 directories deep. Since the basic observers just log things
without actually taking action I will dump them to console to show what they
observed.

```php
	// file filter is a match all filter...
	// Everything must match, including 1 regex if any are set, to match
	$file_filter = new php_file_crawler\includes\FileInfoFilterBase();
	$file_filter->addRegEx( '/\.pdf$/i' );	// find pdfs
	$file_filter->addRegEx( '/\.doc$/i' );	// and docs
	// dir filter is a match any filter...
	// If anything matches the directory is excluded from the search
	$dir_filter = new php_file_crawler\includes\FileInfoFilterBase();
	$dir_filter->setIsLink( TRUE );			// no linked directories
	$dir_filter->addRegEx( '/^\./' );		// no hidden directories
	$dir_filter->addRegEx( '/extract/' );	// my Download's extract directory
		// create our search, last param is depth and is optional
	$search = new php_file_crawler\DirectorySearch( $file_filter, $dir_filter, 7 );
	// subscribe a observers
	$matched = new php_file_crawler\MatchedObserver( $search );
	$skipped = new php_file_crawler\SkippedDirObserver( $search );
	// do some searches
	$search->scanDirectory( '/home/thomas/Downloads' );
	$search->scanDirectory( '/home/thomas/Documents' );
	// matched observer just logs it so dump the log
	$line = 0;
	print '************************ Matched' . PHP_EOL;
	foreach( $matched->getResults() as $data ) {
		printf(
			'%04d: %s %10s %s' . PHP_EOL,
			++$line,
			($data->getFileInfo()->IsLink() ? 'Y' : 'N' ),
			$data->getStatus(),
			$data->getFileInfo()->getPathname()
		);
	}
	// skipped observer just logs it so dump the log
	$line = 0;
	print '************************ Skipped' . PHP_EOL;
	foreach( $skipped->getResults() as $data ) {
		printf(
			'%04d: %s %10s %s' . PHP_EOL,
			++$line,
			($data->getFileInfo()->IsLink() ? 'Y' : 'N' ),
			$data->getStatus(),
			$data->getFileInfo()->getPathname()
		);
	}
```

And the resulting output (your's would vary obviously):
```
************************ Matched
0001: N    matched /home/thomas/Downloads/Clean-Code-Cheat-Sheet-V1.3.pdf
0002: N    matched /home/thomas/Downloads/designpatternscard1.pdf
0003: N    matched /home/thomas/Downloads/Order-Form-New-Zealand-copy1.pdf
0004: N    matched /home/thomas/Downloads/munin-dev.pdf
0005: N    matched /home/thomas/Downloads/progit.en.pdf
0006: N    matched /home/thomas/Downloads/Blank-frame-for-mi-handout.pdf
0007: N    matched /home/thomas/Downloads/JTE-Order-Form-Rv-7-13.pdf
0008: N    matched /home/thomas/Downloads/FishCheatSheet.xlsx - Fish Stats.pdf
0009: N    matched /home/thomas/Downloads/Blank-frame-for-mi-handout(1).pdf
0010: N    matched /home/thomas/Downloads/phpdoc_cheatsheet.pdf
0011: N    matched /home/thomas/Downloads/CF285.pdf
0012: N    matched /home/thomas/Documents/sams.pdf
0013: N    matched /home/thomas/Documents/ccna-reschedule.pdf
************************ Skipped
0001: N   excluded /home/thomas/Downloads/extract
0002: N   excluded /home/thomas/Downloads/teamviewer8/profile/.tweak
0003: N    toodeep /home/thomas/Downloads/teamviewer8/profile/drive_c/windows/system32/spool/printers
0004: N    toodeep /home/thomas/Downloads/teamviewer8/profile/drive_c/windows/system32/spool/drivers
0005: N    toodeep /home/thomas/Downloads/teamviewer8/profile/drive_c/windows/system32/gecko/plugin
0006: N    toodeep /home/thomas/Downloads/teamviewer8/profile/drive_c/users/Public/Application Data/Microsoft
0007: N    toodeep /home/thomas/Downloads/teamviewer8/profile/drive_c/users/Public/Start Menu/Programs
0008: N    toodeep /home/thomas/Downloads/teamviewer8/profile/drive_c/users/thomas/Temp/TeamViewer
0009: Y   excluded /home/thomas/Downloads/teamviewer8/profile/drive_c/users/thomas/My Music
0010: N    toodeep /home/thomas/Downloads/teamviewer8/profile/drive_c/users/thomas/Local Settings/Temporary Internet Files
0011: N    toodeep /home/thomas/Downloads/teamviewer8/profile/drive_c/users/thomas/Local Settings/Application Data
0012: N    toodeep /home/thomas/Downloads/teamviewer8/profile/drive_c/users/thomas/Local Settings/History
0013: N    toodeep /home/thomas/Downloads/teamviewer8/profile/drive_c/users/thomas/Start Menu/Programs
0014: Y   excluded /home/thomas/Downloads/teamviewer8/profile/drive_c/users/thomas/My Documents
0015: Y   excluded /home/thomas/Downloads/teamviewer8/profile/drive_c/users/thomas/Desktop
0016: Y   excluded /home/thomas/Downloads/teamviewer8/profile/drive_c/users/thomas/My Pictures
0017: Y   excluded /home/thomas/Downloads/teamviewer8/profile/drive_c/users/thomas/My Videos
0018: Y   excluded /home/thomas/Downloads/teamviewer8/profile/dosdevices/c:
0019: Y   excluded /home/thomas/Downloads/teamviewer8/profile/dosdevices/z:
0020: N   excluded /home/thomas/Documents/Aptana Studio 3 Workspace/.metadata
```
