# <img src="http://i.imgur.com/h51t4bA.png" alt="AWESOME - Aberystwyth Web Evaluation Surveys Of Module Experiences" height="88px" />

Dev branch: [![Dev branch Build Status](https://magnum.travis-ci.com/bbrks/AWESOME.svg?token=xqotcpsHvJmZdKpQBoyp&branch=dev)](https://magnum.travis-ci.com/bbrks/AWESOME)

---

AWESOME is a web-based module evaluation questionnaire generator for the monitoring and evaluation of teaching.

## Installation

Clone the repo, copy config.sample.php to config.php and edit

### Lighttpd

Use the following rewrite rule in place of the .htaccess rules

```
  url.rewrite-if-not-file = (
    "^\/(admin)(.*)$" => "$0",
    "^/(.*)" => "/index.php?url=$1"
  )
```

## Devblog
http://diss.bbrks.me

[<img src="http://dev.bbrks.me/feedimg/image.php?url=diss.bbrks.me/feed&scale=2" height="65px" />](http://diss.bbrks.me)

## Authors

- Hannah Dee
- Keiron O'Shea
- Ben Brooks

## Copyright and License

Code and documentation copyright 2014-2015.

Code released under [the MIT license](https://github.com/bbrks/AWESOME/blob/master/LICENSE).
