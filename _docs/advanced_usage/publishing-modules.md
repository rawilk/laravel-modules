---
title: Publishing Modules
permalink: /docs/advanced-usage/publishing-modules/
doc_section_id: advanced-usage
---

After creating a module, you may want to make the module available to other developers. You can push
your module to [github](https://github.com) or [bitbucket](https://bitbucket.org) and after that you
can submit your module to the packagist website.

Follow these steps to publish your module:

1. Create the module
2. Push the module to github, bitbucket or gitlab.
3. Submit your module to the packagist website. Submitting to packagist is very easy; just point
packagist to your repository and then publish it.

### Have Modules Be Installed In Modules Folder

There is also a way to have your modules be installed in the `Modules/` directory automatically.
This is made possible by [joshbrw/laravel-module-installer](https://github.com/joshbrw/laravel-module-installer).
Simply require this package on your module, and set the `type` key in the `composer.json` file to
`laravel-module`.