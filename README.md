# git-minion-update
For pull-only git repos that should be regularly updated from the master git repo: a PHP script that will fetch-reset-clean the local repo when requested. 

## Quick Setup
### 1. Add to your project
```
git submodule add git@github.com:alexanderbird/git-minion-update.git
git add -A
git commit -m "Added git-minion-update script"
```

### 2. Checkout in production
```
git submodule update --init --recursive
```

### 3. Configure - see below

## Overview
### Simple Continuous Deployment
You want your production site updated whenever you commit to master, but your git repo doesn't have shell access to your production code (either the repo is on another machine, or your repo user account doesn't have access to the production directory). See [this Stack Overflow question](http://stackoverflow.com/questions/9589814/git-force-a-pull-to-overwrite-everything-on-every-pull). 

### The Main Idea
* Your site is hosted in git
* When a GET request is made to some secret url in production, your production checkout is updated. (That's where this script comes in.)
* In your git repo, a post-commit hook is configured to make a call to that secret url

### Configuration
1. Configure index.php by 
  1. Changing the branch name to follow (if you want to follow something other than master)
  2. Set a secret string so only those who know it can run the script. Make it long and unguessable without spaces or special characters. 
2. Add a post-commit hook to your main repo that makes a request to http://your-domain.com/git-minion-update?secret=yourSecretString

## "minion"...?
It sounds nicer than git-slave-update. http://programmers.stackexchange.com/questions/108035/master-slave-politically-correct-version

# Work In Progress
- [ ] Update submodules
- [ ] Redirect if the secret isn't provided
