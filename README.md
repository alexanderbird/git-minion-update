# git-minion-update
For pull-only git repos that should be regularly updated from the master git repo: a PHP script that will fetch-reset-clean the local repo when requested. 

## Simple Continuous Deployment
You want your production site updated whenever you commit to master, but your git repo doesn't have shell access to your production code (either the repo is on another machine, or your repo user account doesn't have access to the production directory). See [this Stack Overflow question](http://stackoverflow.com/questions/9589814/git-force-a-pull-to-overwrite-everything-on-every-pull). 

## The Main Idea
* Your site is hosted in git
* When a GET request is made to some secret url in production, your production checkout is updated. (That's where this script comes in.)
* In your git repo, a post-commit hook is configured to make a call to that secret url

## Setup & Configuration
1. Clone git-minion-update under the root directory of your site
  1. For a quick and dirty approach, only include it on the production site
  2. For a better approach, [setup this repo as a submodule of your project](https://git-scm.com/book/en/v2/Git-Tools-Submodules). In some future commit, I will have git-minion-update update submodules also, including itself
2. Configure index.php by 
  1. Changing the branch name to follow (if you want to follow something other than master)
  2. Set a secret string so only those who know it can run the script. Make it long and unguessable without spaces or special characters. 
3. Add a post-commit hook to your main repo that makes a request to http://your-domain.com/git-minion-update?secret=yourSecretString

## "minion"...?
It sounds nicer than git-slave-update. http://programmers.stackexchange.com/questions/108035/master-slave-politically-correct-version

# Work In Progress
[ ] Update submodules
[ ] Redirect if the secret isn't provided
