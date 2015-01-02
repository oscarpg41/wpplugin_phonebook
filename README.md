wpplugin_phonebook
==================
Date: 23 November 2014

=== Phone Book ===

Developer: Oskar  (www.oscarperez.es)

Tested up to: 4.0

Stable tag: 1.0

License: GPLv2 or later

== Description ==

This PhoneBook plugin helps to manage the phone book easily over the WordPress blog.

Language: Spanish

Plugin phoneBook to manage a phone book.

The phone book have three fields: idPhone, name and phone

The opg_plugin_phonebook database have three columns:

- `idPhone` INT( 11 ) , 

- `name` VARCHAR( 255 ) NOT NULL , 

- `phone` VARCHAR( 40 ) NOT NULL )';


The plugin contains four files:
- opg_phonebook.php
- opg_phonebook.js
- img/modificar.png
- img/papelera.png


== Installation ==

Unzip the PhoneBook plugin into your blog, into the path wp-content\plugins.

After the installation, you will must have a new directory: wp-content\plugins\opg_phonebook

Activate it, 
You're done!

== Changelog ==

= 1.0.0 = *Release Date - 23rd November, 2014

= 1.1.0 = *Release Date - 2rd January, 2015

    In the list of phones changed the literal 'Modify' and 'Delete' by two images.

    Before deleting the record, a confirmation is requested by a JavaScript confirm.