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

- trap and report permissions errors (part of state/notify handling)
- better state handling/passing
- better filtering options (class it out through an interface)
- get the framework finished and in place

Progress Notes
--------------

Simple observer pattern in place with basic data handler (simple array for now)
File includes and directory excludes in place (using regex)
detecting and ignoring symlinks when scanning (via param, ignored by default)

