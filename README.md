Projex
======

Simplify management of your local project directories

## Why use Projex?

Our company [LinkORB](http://engineering.linkorb.com) switched to a micro-service architecture a while ago.
This, combined with the large amount of libraries we're maintaining, resulted in a large amount (nearly 200)
of git repositories on local computers of developers.

Projex helps you to keep track of these projects, and it allows you to perform some mass commands on them.

## Project scanner

This tool will automatically scan your computer for project directories.
It currently requires a certain directory structure (configurability for personal preferences may be added later).

1. All git repositories go in the  `~/git` directory.
2. In there, create a directory per group/organization (just like on github).
3. In those directories you create the actual git repositories.

## Getting started

### Install dependencies:

Get PHP dependencies using Composer
```
composer install
```

## Commands

You can view all available commands by running `bin/projex`

### projects:scan

Scans your computer for projects (according to structure described above), and lists the found project directories.

### atom:update

This command will scan your machine for projects, and generate a new '~/.atom/projects.cson' file.
This can be used in combination with this awesome atom plugin:

    https://atom.io/packages/project-manager

## License

Please refer to the included LICENSE.md file

## Brought to you by the LinkORB Engineering team

<img src="http://www.linkorb.com/d/meta/tier1/images/linkorbengineering-logo.png" width="200px" /><br />
Check out our other projects at [engineering.linkorb.com](http://engineering.linkorb.com).

Btw, we're hiring!
