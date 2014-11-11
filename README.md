PHP-File-Crawler
================

PHP based file crawler initially for archiving data off old hard drives for a client


Goals
-----

- aggregator to give stats and breakdowns (# files, folders, per extension, etc)
- reporting for dumping this to different formats (csv, email, etc)
- archival observer to build an archive, in a new location, of the matched files
- matched observers to output to html and csv for pairing with an archive 

To Do
-----

- no reporting yet
- Unit tests

Progress Notes
--------------

<p>
New method crawls through entire drives like a beast but memory is going to be
an issue if the Observers store too much data (entire SplFileInfo for example).
This isn't a problem as long as they do their work on the fly instead of 
waiting until the scan is finished. Also, nesting can be an issue (had to bump 
xdebug up to 200) since there are four function levels per directory level.
</p>


Example
-------

There are two filters to create. Just creating filters and using them will
get you everything. In most cases you'll want to at least exclude linked
directories and add some kind of filtering to your files (extension for example).

Below is a simple run to get an idea. It will find all .htm(l) and .css files in
my Downloads and Documents directories. It will skip any hidden directories,
linked directories, any directories named "extract" and will ignore anything
deeper than 7 directories deep. Since the basic observers just log things
without actually taking action I will dump them to console to show what they
observed.

```php
	// file filter is a match all filter...
	// Everything must match, including 1 regex if any are set, to match
	$file_filter = new php_file_crawler\includes\FileInfoFilterBase();
	$file_filter->addRegEx( '/\.htm[l]*$/i' );	// find html files
	$file_filter->addRegEx( '/\.css$/i' );	// and css files
	// dir filter is a match any filter...
	// If anything matches the directory is excluded from the search
	$dir_filter = new php_file_crawler\includes\FileInfoFilterBase();
	$dir_filter->setIsLink( TRUE );			// no linked directories
	$dir_filter->addRegEx( '/^\./' );		// no hidden directories
	$dir_filter->addRegEx( '/^extract$/' );	// my Download's extract directory
	// create our search, last param is depth and is optional
	$search = new php_file_crawler\DirectorySearch( $file_filter, $dir_filter, 7 );
	// subscribe observers
	$matched = new php_file_crawler\MatchedObserver( $search );
	$skipped = new php_file_crawler\SkippedDirObserver( $search );
	// do some searches
	$search->searchDirectory( '/home/thomas/Downloads' );
	$search->searchDirectory( '/home/thomas/Documents' );

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
0001: N    matched /home/thomas/Downloads/fpdf/changelog.htm
0002: N    matched /home/thomas/Downloads/fpdf/FAQ.htm
0003: N    matched /home/thomas/Downloads/fpdf/fpdf.css
0004: N    matched /home/thomas/Downloads/fpdf/tutorial/tuto7.htm
0005: N    matched /home/thomas/Downloads/fpdf/tutorial/tuto2.htm
0006: N    matched /home/thomas/Downloads/fpdf/tutorial/tuto1.htm
0007: N    matched /home/thomas/Downloads/fpdf/tutorial/tuto3.htm
0008: N    matched /home/thomas/Downloads/fpdf/tutorial/index.htm
0009: N    matched /home/thomas/Downloads/fpdf/tutorial/tuto4.htm
0010: N    matched /home/thomas/Downloads/fpdf/tutorial/tuto6.htm
0011: N    matched /home/thomas/Downloads/fpdf/tutorial/tuto5.htm
0012: N    matched /home/thomas/Downloads/fpdf/doc/setfont.htm
0013: N    matched /home/thomas/Downloads/fpdf/doc/setdrawcolor.htm
0014: N    matched /home/thomas/Downloads/fpdf/doc/text.htm
0015: N    matched /home/thomas/Downloads/fpdf/doc/settextcolor.htm
0016: N    matched /home/thomas/Downloads/fpdf/doc/cell.htm
0017: N    matched /home/thomas/Downloads/fpdf/doc/getstringwidth.htm
0018: N    matched /home/thomas/Downloads/fpdf/doc/setkeywords.htm
0019: N    matched /home/thomas/Downloads/fpdf/doc/setsubject.htm
0020: N    matched /home/thomas/Downloads/fpdf/doc/index.htm
0021: N    matched /home/thomas/Downloads/fpdf/doc/setfillcolor.htm
0022: N    matched /home/thomas/Downloads/fpdf/doc/multicell.htm
0023: N    matched /home/thomas/Downloads/fpdf/doc/pageno.htm
0024: N    matched /home/thomas/Downloads/fpdf/doc/setcompression.htm
0025: N    matched /home/thomas/Downloads/fpdf/doc/rect.htm
0026: N    matched /home/thomas/Downloads/fpdf/doc/aliasnbpages.htm
0027: N    matched /home/thomas/Downloads/fpdf/doc/footer.htm
0028: N    matched /home/thomas/Downloads/fpdf/doc/settitle.htm
0029: N    matched /home/thomas/Downloads/fpdf/doc/fpdf.htm
0030: N    matched /home/thomas/Downloads/fpdf/doc/setleftmargin.htm
0031: N    matched /home/thomas/Downloads/fpdf/doc/setauthor.htm
0032: N    matched /home/thomas/Downloads/fpdf/doc/getx.htm
0033: N    matched /home/thomas/Downloads/fpdf/doc/setlink.htm
0034: N    matched /home/thomas/Downloads/fpdf/doc/link.htm
0035: N    matched /home/thomas/Downloads/fpdf/doc/sety.htm
0036: N    matched /home/thomas/Downloads/fpdf/doc/error.htm
0037: N    matched /home/thomas/Downloads/fpdf/doc/setautopagebreak.htm
0038: N    matched /home/thomas/Downloads/fpdf/doc/output.htm
0039: N    matched /home/thomas/Downloads/fpdf/doc/header.htm
0040: N    matched /home/thomas/Downloads/fpdf/doc/setmargins.htm
0041: N    matched /home/thomas/Downloads/fpdf/doc/settopmargin.htm
0042: N    matched /home/thomas/Downloads/fpdf/doc/line.htm
0043: N    matched /home/thomas/Downloads/fpdf/doc/setdisplaymode.htm
0044: N    matched /home/thomas/Downloads/fpdf/doc/ln.htm
0045: N    matched /home/thomas/Downloads/fpdf/doc/setrightmargin.htm
0046: N    matched /home/thomas/Downloads/fpdf/doc/setcreator.htm
0047: N    matched /home/thomas/Downloads/fpdf/doc/setx.htm
0048: N    matched /home/thomas/Downloads/fpdf/doc/acceptpagebreak.htm
0049: N    matched /home/thomas/Downloads/fpdf/doc/addfont.htm
0050: N    matched /home/thomas/Downloads/fpdf/doc/write.htm
0051: N    matched /home/thomas/Downloads/fpdf/doc/setlinewidth.htm
0052: N    matched /home/thomas/Downloads/fpdf/doc/addpage.htm
0053: N    matched /home/thomas/Downloads/fpdf/doc/addlink.htm
0054: N    matched /home/thomas/Downloads/fpdf/doc/image.htm
0055: N    matched /home/thomas/Downloads/fpdf/doc/setfontsize.htm
0056: N    matched /home/thomas/Downloads/fpdf/doc/gety.htm
0057: N    matched /home/thomas/Downloads/fpdf/doc/setxy.htm
0058: N    matched /home/thomas/Downloads/fpdf/doc/close.htm
0059: N    matched /home/thomas/Downloads/teamviewer8/profile/drive_c/teamviewer.html
0060: N    matched /home/thomas/Documents/testfind.HTML
0061: N    matched /home/thomas/Documents/crawl1/crawl2/thisfile.css
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
0020: N    toodeep /home/thomas/Documents/UPS/Shipping/ShippingPACKAGE/PACKAGEWebServices/CodeSamples/Ship/DotNet
0021: N    toodeep /home/thomas/Documents/UPS/Shipping/ShippingPACKAGE/PACKAGEWebServices/CodeSamples/Ship/Axis2.4
0022: N    toodeep /home/thomas/Documents/UPS/Shipping/ShippingPACKAGE/PACKAGEWebServices/CodeSamples/Ship/JAXWS2.1
0023: N    toodeep /home/thomas/Documents/UPS/Shipping/ShippingPACKAGE/PACKAGEWebServices/CodeSamples/Ship/PHP
0024: N    toodeep /home/thomas/Documents/UPS/Shipping/ShippingPACKAGE/PACKAGEWebServices/CodeSamples/Ship/PERL
0025: N    toodeep /home/thomas/Documents/UPS/Shipping/ShippingPACKAGE/PACKAGEWebServices/CodeSamples/Void/DotNet
0026: N    toodeep /home/thomas/Documents/UPS/Shipping/ShippingPACKAGE/PACKAGEWebServices/CodeSamples/Void/Axis2.4
0027: N    toodeep /home/thomas/Documents/UPS/Shipping/ShippingPACKAGE/PACKAGEWebServices/CodeSamples/Void/JAXWS2.1
0028: N    toodeep /home/thomas/Documents/UPS/Shipping/ShippingPACKAGE/PACKAGEWebServices/CodeSamples/Void/PHP
0029: N    toodeep /home/thomas/Documents/UPS/Shipping/ShippingPACKAGE/PACKAGEWebServices/CodeSamples/Void/PERL
0030: N    toodeep /home/thomas/Documents/UPS/Shipping/ShippingPACKAGE/PACKAGEWebServices/CodeSamples/LabelRecovery/DotNet
0031: N    toodeep /home/thomas/Documents/UPS/Shipping/ShippingPACKAGE/PACKAGEWebServices/CodeSamples/LabelRecovery/Axis2.4
0032: N    toodeep /home/thomas/Documents/UPS/Shipping/ShippingPACKAGE/PACKAGEWebServices/CodeSamples/LabelRecovery/JAXWS2.1
0033: N    toodeep /home/thomas/Documents/UPS/Shipping/ShippingPACKAGE/PACKAGEWebServices/CodeSamples/LabelRecovery/PHP
0034: N    toodeep /home/thomas/Documents/UPS/Shipping/ShippingPACKAGE/PACKAGEWebServices/CodeSamples/LabelRecovery/PERL
0035: N    toodeep /home/thomas/Documents/UPS/Shipping/ShippingPACKAGE/PACKAGEXMLTools/CodeSamples/ShipConfirm/JAXB
0036: N    toodeep /home/thomas/Documents/UPS/Shipping/ShippingPACKAGE/PACKAGEXMLTools/CodeSamples/ShipConfirm/PHP
0037: N    toodeep /home/thomas/Documents/UPS/Shipping/ShippingPACKAGE/PACKAGEXMLTools/CodeSamples/ShipConfirm/PERL
0038: N    toodeep /home/thomas/Documents/UPS/Shipping/ShippingPACKAGE/PACKAGEXMLTools/CodeSamples/Void/JAXB
0039: N    toodeep /home/thomas/Documents/UPS/Shipping/ShippingPACKAGE/PACKAGEXMLTools/CodeSamples/Void/PHP
0040: N    toodeep /home/thomas/Documents/UPS/Shipping/ShippingPACKAGE/PACKAGEXMLTools/CodeSamples/Void/PERL
0041: N    toodeep /home/thomas/Documents/UPS/Shipping/ShippingPACKAGE/PACKAGEXMLTools/CodeSamples/ShipAccept/JAXB
0042: N    toodeep /home/thomas/Documents/UPS/Shipping/ShippingPACKAGE/PACKAGEXMLTools/CodeSamples/ShipAccept/PHP
0043: N    toodeep /home/thomas/Documents/UPS/Shipping/ShippingPACKAGE/PACKAGEXMLTools/CodeSamples/ShipAccept/PERL
0044: N    toodeep /home/thomas/Documents/UPS/Shipping/ShippingPACKAGE/PACKAGEXMLTools/CodeSamples/LabelRecovery/JAXB
0045: N    toodeep /home/thomas/Documents/UPS/Shipping/ShippingPACKAGE/PACKAGEXMLTools/CodeSamples/LabelRecovery/PHP
0046: N    toodeep /home/thomas/Documents/UPS/Shipping/ShippingPACKAGE/PACKAGEXMLTools/CodeSamples/LabelRecovery/PERL
0047: N    toodeep /home/thomas/Documents/UPS/Shipping/ShippingGROUNDFREIGHTWebService/CodeSamples/Freight/DotNet/FreightShipWSSample
0048: N    toodeep /home/thomas/Documents/UPS/Shipping/ShippingGROUNDFREIGHTWebService/CodeSamples/Freight/Axis2.4/src
0049: N    toodeep /home/thomas/Documents/UPS/Shipping/ShippingGROUNDFREIGHTWebService/CodeSamples/Freight/JAXWS2.1/src
0050: N    toodeep /home/thomas/Documents/UPS/AddressValidation/Regional Address Validation for SHIPPING/AVXML/CodeSamples/AV/JAXB
0051: N    toodeep /home/thomas/Documents/UPS/AddressValidation/Regional Address Validation for SHIPPING/AVXML/CodeSamples/AV/PHP
0052: N    toodeep /home/thomas/Documents/UPS/AddressValidation/Regional Address Validation for SHIPPING/AVXML/CodeSamples/AV/PERL
```
