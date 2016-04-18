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
  * ~~`.center-block` replaced by `.m-x-auto`,~~ `.pull-left` by `.pull-xs-left` & `.pull-right` by `.pull-xs-right`
  * `.btn-*-outline` replaced by `.btn-outline-*`
  * `.col-*-offset-*` replaced by `.offset-*-*`, `.col-*-push-*` by `.push-*-*` & `.col-*-pull-*` by `.pull-*-*`
  * _`label`_ shortcode replaced by _`tag`_ shortcode
  * added `.m-b-0` to `.navbar-brand` as per docs
  * EqualHeights now based on [@Sam152](https://github.com/Sam152/Javascript-Equal-Height-Responsive-Rows)'s work
  * moved print support to theme css file and added `bootstrap-pr.css` including `.col-pr-*` classes


*  0.1.04
  * added a few new shortcodes; _`icon`_, _`lead`_ and _`blockquote`_
  * dropped `.pager` class, needed to re-write page navigation to use button classes
  * changed `.pagination` class functionality required rewrite of pagination
  * changed `.input-group`&gt;`.input-group-addon` functionality required small changes to search and comment forms.
  * renamed `.label` class to `.tag`, this causes all of WP's *"tag taxonomy"* to break - needed to write filters to remove WP behavior.
    * e.g. if you have a tag called __success__ then WP inserts a class called `tag-success`, this causes the whole article to show as a BS4 tag.
  * needed to revert to using &lt;center&gt; html - just can't seem to figure out CSS centering
  * small changes to footer area

★
