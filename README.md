WP-Bootstrap4
=============


---

**( _At the time of release Bootsrap 4 is in version 4.0-Alpha-2._ )**

This theme is based on the [v4-dev](https://github.com/twbs/bootstrap/tree/v4-dev) branch, last pull dated 8-Apr-2016.

---


Introduction
------------

A responsive Wordpress blogging theme based on [Bootstrap 4](http://v4-alpha.getbootstrap.com).

Unlike [WP-Bootstrap2](https://github.com/vinorodrigues/wp-bootstrap2) that started with *_S*, this project written from the ground up to cut out a lot of clutter.

This implies certain considerations:
* The theme is not multi-language, no l10n (localization) exists.  It's only in English.
* RTL (right-to-left language) is also excluded
* Common class names used in WP Core have been removed. As few non-Bootstrap class names as possible is the approach taken.
* Assistive technologies are excluded in this initial release.  This will be updated later.


Releases
--------

*  0.1.01
  *  Initial functional upload to Github


*  0.1.02
  *  added custom header image
  *  added post featured image support, that replaces the custom header image
  *  added custom background image support
  *  ~~added `print.css` print content css~~


*  0.1.03
  * based on _twbs/bootstrap @26adc1_ (_v4-dev_ branch)
  * includes _FortAwesome/Font-Awesome @d820f5c_ (_4.6.0-wip_ branch)
  * `center-block` replaced by `m-x-auto`, `pull-left` by `pull-xs-left` & `pull-right` by `pull-xs-right`
  * `btn-*-outline` replaced by `btn-outline-*`
  * `col-*-offset-*` replaced by `offset-*-*`, `col-*-push-*` by `push-*-*` & `col-*-pull-*` by `pull-*-*`
  * _`label`_ shortcode replaced by _`tag`_ shortcode
  * added `m-b-0` to `navbar-brand` as per docs
  * EqualHeights now based on [@Sam152](https://github.com/Sam152/Javascript-Equal-Height-Responsive-Rows)'s work
  * moved print support to theme css file and added `bootstrap-print.css` including `col-pr-*` classes

Licences
--------

- Theme _(this work)_ under [MIT License](http://www.gnu.org/licenses/gpl.html)
- [Bootstrap 4](http://v4-alpha.getbootstrap.com) under [MIT License](http://www.apache.org/licenses/LICENSE-2.0)
- [Font Awesome](http://fortawesome.github.io/Font-Awesome/) under [SIL OFL 1.1](http://scripts.sil.org/OFL)
- [Wordpress](http://wordpress.org) under [GPLv2](http://www.gnu.org/licenses/gpl-2.0.html)
