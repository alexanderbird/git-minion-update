# git-minion-update
Continuous deployment on a standard LAMP stack without dependancies - just add the script to your project and a post-update hook on your git server.

## Setup
### 1. Local setup
#### 1.1 Add to your project
```bash
git submodule add https://github.com/alexanderbird/git-minion-update.git 
```

#### 1.2 Configure
Add a `.git-minion-update.ini` file to the project root (or more precisely, one directory above the git-minion-update directory)

**The following values can be configured**
```ini
branch = 'name-of-branch-to-checkout' ; default 'master'
secret = 'something-private' ; required to run the script
redirect_to = '../will-redirect-here-if-secret-not-provided.html' ; default '..' which is the site root, relative to git-minion-update/index.php
get_latest_submodules = false ; runs `git submodule update`
  ; when true, runs `git submodule foreach git pull origin master`
  ; default to true
path = '/relative/to/project/root' ; default to '/'
  ; this is from where the `git pull` will be executed
```

#### 1.3 Commit and Push
```bash
git add -A
git commit -m "Added git-minion-update script"
git push
```

### 2. Production Setup
#### 2.1 Checkout in production
```bash
git pull
git submodule update --init --recursive
```

### 3. Git hook setup
#### 3.1 - First option: using GitHub
* Project > Settings > Webhooks > Add Webhook
	* *Payload URL:* http://your-domain.com/git-minion-update/?secret=your-secret
	* Save your changes
	
Note: I haven't tested out this configuration, I use a private git server for the projects that I use `git-minion-update` for. Please let me know if these GitHub instructions don't work for you. 

#### 3.2 - Second option: using a different git server
Add a post-update hook that executes: `curl http://your-domain.com/git-minion-update/?secret=your-secret`

#### 4. Test commit
    
    git push

Which produces: 

    Counting objects: 5, done.
    Delta compression using up to 4 threads.
    Compressing objects: 100% (5/5), done.
    Writing objects: 100% (5/5), 782 bytes | 0 bytes/s, done.
    Total 5 (delta 3), reused 0 (delta 0)
    remote:   % Total    % Received % Xferd  Average Speed   Time    Time     Time  Current
    remote:                                  Dload  Upload   Total   Spent    Left  Speed
    remote: 100   110    0   110    0     0     51      0 --:--:--  0:00:02 --:--:--    51
    remote: Updating from prod branch
    remote: From example.com:your_project.git
    remote:  * branch            prod       -> FETCH_HEAD
    remote: 
    remote: 
    remote: 
    remote: 
    To git@example.com:your_project.git 
       06adc99..6413fda  prod -> prod

## Overview
### Simple Continuous Deployment
You want your production site updated whenever you commit to master, but your git repo doesn't have shell access to your production code (either the repo is on another machine, or your repo user account doesn't have access to the production directory). See [this Stack Overflow question](http://stackoverflow.com/questions/9589814/git-force-a-pull-to-overwrite-everything-on-every-pull). 

### The Main Idea
* Your site is hosted in git
* When a GET request is made to some secret url in production, your production checkout is updated. (That's where this script comes in.)
* In your git repo, a post-commit hook is configured to make a call to that secret url

## "minion"...?
This script is used to update a git repo that is a slave to the main repo. But minion sounds nicer than slave, so I'm going with that. So the minion repo is one that should be pull-only, and should replicate the master after each commit. 
