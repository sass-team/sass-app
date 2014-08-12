[TOC]

# README 
A system to managing hours for peer tutoring for a university.

# [GIT (CVS)](http://git-scm.com/book/en/Getting-Started-About-Version-Control)
What is version control, and why should you care? Version control is a system that records changes to a file or set of files over time so that you can recall specific versions later. 

## Set up Git
[Installi git for Windows](https://confluence.atlassian.com/display/BITBUCKET/Set+up+Git+and+Mercurial#SetupGitandMercurial-Step1.InstallGitforWindows)

## Initilizing & retrieving repo

At an empty folder using cmd:
```
// initalizes an empty git repository
git init
// add remote current remoterepo
git remote add origin master git@bitbucket.org:geoif_rdok/sass-ms.git
// retrieve files from bitbucket repo
git pull origin master
```
[Official Guide](https://confluence.atlassian.com/display/BITBUCKET/Import+code+from+an+existing+project)

## Changing Branches
```
// retrieve what data are on server
git pull
// show remote(server) branches
git branch -r
// show local branch
git branch
// show both local and server branches
git branch -a
// change to branch develop
git checkout develop
// change to branch master
git checkout master
```

## Start work on new feature
```
// you are in develop  branch, and want to start working on a NEW feature: UI-courses-create
git checkout -b UI-courses-create
// you do some work on it. bla bla. finish work.
git add --all
git commit -m "Create UI for creating course."
// since you finished working on this feature/major work/no plans to work in near future/your partner to check it out: you want to merge those changes back to develop
git checkout develop
git merge UI-courses-create
// now the branch develop contains this new feature, and can continue to work.
// if you didn't finish this feature, and still working on it, and want to be tested from partners:
// from branch UI-courses-create, you push it to server.
git push origin UI-courses-create
```

# Wamp - Virtual Host
- http://forum.wampserver.com/read.php?2,124482

# Gitflow  
[Training](https://github.com/nvie/gitflow)  

![Gitflow Diagram](http://nvie.com/img/2009/12/Screen-shot-2009-12-24-at-11.32.03.png "Visualize")

# localhost - emails
http://www.toolheap.com/test-mail-server-tool/
>Test Mail Server Tool is a full featured mail (SMTP) server emulation to test mail sending capabilities of a web or desktop application or to preview messages before they are actually sent.  

Just install the above app. Run "Test Mail Server Tool". Allow to connect to internet if prompted.  

# [ssh](https://help.github.com/articles/generating-ssh-keys)