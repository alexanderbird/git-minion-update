# git-minion-update
For pull-only git repos that should be regularly updated from the master git repo: a PHP script that will fetch-reset-clean the local repo when requested. 

## Quick Setup
### 1. Add to your project
```bash
git submodule add git@github.com:alexanderbird/git-minion-update.git
git add -A
git commit -m "Added git-minion-update script"
```

### 2. Checkout in production
```bash
git submodule update --init --recursive
```

### 3. Configure
1. Add a `.git-minion-update.ini` file to the project root (or more precisely, one directory above the git-minion-update directory)
2. The following values can be configured
```ini
branch = 'name-of-branch-to-checkout' ; default 'master'
secret = 'something-private' ; required to run the script
redirect_to = '../will-redirect-here-if-secret-not-provided.html' ; default '..' which is the site root, relative to git-minion-update.ini 
```

### 4. Add git post-commit hook to your repo
`wget http://your-domain.com/git-minion-update/?secret=your-secret`

## Overview
### Simple Continuous Deployment
You want your production site updated whenever you commit to master, but your git repo doesn't have shell access to your production code (either the repo is on another machine, or your repo user account doesn't have access to the production directory). See [this Stack Overflow question](http://stackoverflow.com/questions/9589814/git-force-a-pull-to-overwrite-everything-on-every-pull). 

### The Main Idea
* Your site is hosted in git
* When a GET request is made to some secret url in production, your production checkout is updated. (That's where this script comes in.)
* In your git repo, a post-commit hook is configured to make a call to that secret url

## "minion"...?
It sounds nicer than git-slave-update. http://programmers.stackexchange.com/questions/108035/master-slave-politically-correct-version
