 <img src="http://i.imgur.com/IOfwVzr.png" alt="AWESOME - Aberystwyth Web Evaluation Surveys Of Module Experiences" height="88px" /> [![Build Status](https://magnum.travis-ci.com/bbrks/AWESOME.svg?token=xqotcpsHvJmZdKpQBoyp&branch=oopmvc)](https://magnum.travis-ci.com/bbrks/AWESOME)
=======

AWESOME is a web-based module evaluation questionnaire generator for the monitoring and evaluation of teaching.

        Copyright 2014
        Keiron O'Shea,
        Hannah Dee,
        Ben Brooks
 
## Contents


- [Getting started](#getting-started)
- [Installation](#installation)
- [Reporting bugs and feature requests](#reporting-bugs-and-feature-requests)
- [Copyright and License](#copyright-and-license)

## Getting started

There are two ways you can get your hands on AWESOME:

- [Download it directly, here](https://github.com/bbrks/AWESOME/archive/master.zip).
- Clone the repository using Git: ```git clone https://github.com/bbrks/AWESOME```.

## Installation

- If you have downloaded directly, unzip the contents of the repository using any software you like, or using the following command.

```uzip AWESOME.zip -d path/to/whatever/directory/you/want```

- Navigate to the directory and copy the contents of the src folder to wherever you require AWESOME to be located.

- Log into MySQL and ```CREATE DATABASE awesome```

- Import the ```sqldump.sql``` file using the following command.

```mysql -u username -p awesome < sqldump.sql```

- Rename ```db.php.sample``` to ```db.php```.

- Edit ```db.php``` to correctly match both your ```$url``` **(important)** and the new ```$db``` details.

- Done!

## Reporting bugs and feature requests

If you have a bug or feature request [please feel free to open a new issue](https://github.com/bbrks/AWESOME/issues/new).

## Copyright and License

Code released under [the MIT license](https://github.com/bbrks/AWESOME/blob/master/LICENSE).
