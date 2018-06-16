# Contributing to Laravel-Modules

👍🎉 First off, thanks for taking the time to contribute! 🎉👍

## Code of Conduct

This project and everyone participating in it is governed by the [Laravel-Modules Code of Conduct](CODE_OF_CONDUCT.md). By participating
you are expected to uphold this code. Please report unacceptable behavior to [wilk.randall@gmail.com](mailto:wilk.randall@gmail.com).

## How Can I Contribute?

### Reporting Bugs

- **Ensure the bug was not already reported** by searching Github under [Issues](https://github.com/rawilk/laravel-modules/issues).
- If you're unable to find an open issue addressing the problem, [open a new one](https://github.com/rawilk/laravel-modules/issues/new). Be
sure to include a **title and clear description**, as much relevant information as possible, and a **code sample** or an **executable test case**
demonstrating the expected behavior that is not occuring.
- If you're providing snippets in the issue, use [Markdown code blocks](https://help.github.com/articles/markdown-basics/#multiple-lines).

Provide more context by answering these questions:

- **Did the problem start happening recently** (e.g. after updating to a new version of Laravel-Modules) or was this always a problem?
- If the problem started happening recently, **can you reproduce the problem in an older version of Laravel-Modules?** What's the most recent
version in which the problem doesn't happen? You can download older versions of Laravel-Modules from the [releases page](https://github.com/rawilk/laravel-modules/releases).
- **Can you reliably reproduce the issue?** If not, provide details about how often  the problem happens and under which conditions
it normally happens.
- **Describe the environment:** What operating system and version are you using? What version of Laravel are you using?

### Suggesting Enhancements

This section guides you through submitting an enhancement suggestion for Laravel-Modules, including completely new features and minor improvements
to existing functionality. Following these guidelines helps maintainers and the community understand your suggestion :pencil: and find related
suggestions :mag_right:.

Before creating enhancement suggestions, please check [this list](#before-submitting-an-enhancement-suggestion) as you might find
out that you don't need to create one. When creating an enhancement suggestion, please [include as many details as possible](#how-do-i-submit-a-good-enhancement-suggestion).
Fill in [the template](https://github.com/rawilk/laravel-modules/blob/master/.github/ISSUE_TEMPLATE.md), including the steps that you might imagine
you would take if the feature you're requesting existed.

### Before Submitting An Enhancement Suggestion
- Check if you are using the latest version of Laravel-Modules and if you can get the desired behavior by upgrading to that version.
- **Perform a [cursory search](https://github.com/rawilk/laravel-modules/issues?utf8=%E2%9C%93&q=+is%3Aissue+label%3Aenhancement+)**
to see if the enhancement has already been suggested. If it has, add a comment to the existing issue instead of opening a new one.

### How Do I Submit A (Good) Enhancement Suggestion?

Enhancement suggestions are tracked as [Github issues](https://guides.github.com/features/issues). Create an issue and provide
the following information:

- **Use a clear and descriptive title** for the issue to identify the suggestion.
- **Provide a step-by-step description of the suggested enhancement** in as many details as possible.
- **Provide specific examples to demonstrate the steps**. Include copy/pasteable snippets which you use in those examples, as 
[Markdown code blocks](https://help.github.com/articles/markdown-basics/#multiple-lines).
- **Describe the current behavior** and **explain which behavior you expected to see instead** and why.
- **Include screenshots and animated GIFs** which help you demonstrate the steps or point out the part of Laravel-Modules
which the suggestion is related to. You can use [this tool](https://www.cockos.com/licecap/) to record GIFs on macOS and Windows, and 
[this tool](https://github.com/colinkeenan/silentcast) or [this tool](https://github.com/GNONE/byzanz) on Linux.
- **Specify which version of Laravel-Modules you're using.** You can get the exact version by referencing your `composer.json` file in your project.
- **Specify the the version of Laravel you're running.**

### Setup

- Clone this repo (`https://github.com/rawilk/laravel-modules.git`)
- Make sure you have composer installed locally
- `cd laravel-modules`
- Run `composer install` to get all dependencies installed.
- Be sure to include [tests](https://phpunit.readthedocs.io/en/7.1/) when necessary for new code or modified code.

### Pull Requests

- Fill in [the required template](https://github.com/rawilk/laravel-modules/blob/master/.github/PULL_REQUEST_TEMPLATE.md).
- Do not include issue numbers in the PR title.
- Include screenshots and animated GIFs in your pull request when possible.

### Coding Conventions

This project follows the [psr-2](https://www.php-fig.org/psr/psr-2/) code style guidelines. In addition to
psr-2, please follow these coding conventions when contributing to the project.

- Imports should always be ordered.
- One blank line should precede a return statement in a function.
- One blank line should always come after the opening php (`<?php`) tag.
- Class properties and variables should be declared using camel case (`$variableName` not `$variable_name`).