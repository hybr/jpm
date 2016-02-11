This document describes how to configure the github development directory
_______________________________________________________________________________

Create folder to host the repository

[ysharma@localhost jpm]$ mkdir jpm
_______________________________________________________________________________

Go inside the folder

[ysharma@localhost jpm]$ cd jpm
_______________________________________________________________________________

Initialise the git configuration

[ysharma@localhost jpm]$ git init
_______________________________________________________________________________

Check the initial configuration status

[ysharma@localhost jpm]$ git status
On branch master

Initial commit

nothing to commit (create/copy files and use "git add" to track)
_______________________________________________________________________________

Update the configuration to link the remote repository with a name
Remote repository is https://github.com/hybr/jpm.git
Name we used is jpm_repo

[ysharma@localhost jpm]$ git remote add jpm_repo https://github.com/hybr/jpm.git

[ysharma@localhost jpm]$ cat .git/config 
[core]
	repositoryformatversion = 0
	filemode = true
	bare = false
	logallrefupdates = true
[remote "jpm_repo"]
	url = https://github.com/hybr/jpm.git
	fetch = +refs/heads/*:refs/remotes/jpm_repo/*
_______________________________________________________________________________

Check the remote repository linked

[ysharma@localhost jpm]$ git remote
jpm_repo
_______________________________________________________________________________

Read the remote repository configuration only and implement in local

[ysharma@localhost jpm]$ git fetch jpm_repo

remote: Counting objects: 1851, done.
remote: Compressing objects: 100% (1192/1192), done.
remote: Total 1851 (delta 629), reused 1840 (delta 625), pack-reused 0
Receiving objects: 100% (1851/1851), 11.93 MiB | 256.00 KiB/s, done.
Resolving deltas: 100% (629/629), done.
From https://github.com/hybr/jpm
 * [new branch]      development -> jpm_repo/development
 * [new branch]      master     -> jpm_repo/master
 * [new branch]      production -> jpm_repo/production
 * [new branch]      quality_assurance -> jpm_repo/quality_assurance

[ysharma@localhost jpm]$ ls

[ysharma@localhost jpm]$ cat .git/config 
[core]
	repositoryformatversion = 0
	filemode = true
	bare = false
	logallrefupdates = true
[remote "jpm_repo"]
	url = https://github.com/hybr/jpm.git
	fetch = +refs/heads/*:refs/remotes/jpm_repo/*

[ysharma@localhost jpm]$ git status
On branch master

Initial commit

nothing to commit (create/copy files and use "git add" to track)
_______________________________________________________________________________

Now we configured with same configuration as remote

Get the files in development branch of jpm_repo

[ysharma@localhost jpm]$ git checkout --track -b development
Switched to a new branch 'development'
_______________________________________________________________________________

Get actual files

[ysharma@localhost jpm]$ git pull jpm_repo development
From https://github.com/hybr/jpm
 * branch            development -> FETCH_HEAD
_______________________________________________________________________________

[ysharma@localhost jpm]$ ls
autoload.php  composer.json  composer.lock  objects  public  
README.md  README.txt  vendor  x.php

_______________________________________________________________________________
_______________________________________________________________________________

Do updates to modify the dev branch. I have updated public/index.php as 
an example

[ysharma@localhost jpm]$ git status
On branch development
Changes not staged for commit:
  (use "git add <file>..." to update what will be committed)
  (use "git checkout -- <file>..." to discard changes in working directory)

	modified:   index.php

no changes added to commit (use "git add" and/or "git commit -a")

_______________________________________________________________________________

Now add the changes in configuration

[ysharma@localhost jpm]$ git add --all *
_______________________________________________________________________________

[ysharma@localhost jpm]$ git status
On branch development
Changes to be committed:
  (use "git reset HEAD <file>..." to unstage)

	modified:   index.php
_______________________________________________________________________________

Commit the changes locally

[ysharma@localhost jpm]$ git commit -a
[jpm_repo/development a2f8498] framework documentation
 1 file changed, 55 insertions(+), 5 deletions(-)
_______________________________________________________________________________

[ysharma@localhost jpm]$ git status
On branch development
nothing to commit, working directory clean
_______________________________________________________________________________

Push the changes made locally to remote repository

[ysharma@localhost jpm]$ git push --set-upstream jpm_repo development

Username for 'https://github.com': sharma.yogesh.1234@gmail.com
Password for 'https://sharma.yogesh.1234@gmail.com@github.com': 
Counting objects: 1, done.
Writing objects: 100% (1/1), 202 bytes | 0 bytes/s, done.
Total 1 (delta 0), reused 0 (delta 0)
To https://github.com/hybr/jpm.git
 * [new branch]      development -> development
Branch development set up to track remote branch development from jpm_repo.
