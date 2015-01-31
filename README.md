[TOC]

## About 
A system to managing hours for peer tutoring for a university.

# [GIT (CVS)](http://git-scm.com/book/en/Getting-Started-About-Version-Control)
What is version control, and why should you care? Version control is a system that records changes to a file or set of files over time so that you can recall specific versions later. 

## Set up Git
[Installi git for Windows](https://confluence.atlassian.com/display/BITBUCKET/Set+up+Git+and+Mercurial#SetupGitandMercurial-Step1.InstallGitforWindows)

## Initilizing repo

At an empty folder using cmd:
```
// initalizes an empty git repository
git init
// add remote current remoterepo
git remote add origin master git@bitbucket.org:geoif_rdok/sass-ms.git
// retrieve files from bitbucket repo
git pull origin master
git pull origin develop
```
[Official Guide](https://confluence.atlassian.com/display/BITBUCKET/Import+code+from+an+existing+project)
## Synchronize local with origin/server 
```
// retrieve files from bitbucket repo
git pull origin master
git pull origin develop
// if you want to push work on server/origin
git push origin develop
git push origin your-feature-name
// CAREFUL: production server -- changes are automated to server. Make sure everything/feature works as intended, and database credentials are correct.
git push origin master
```

## Changing Branches
```
// retrieve info about data origin/server
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

## Delete Branches
```
// after finishing a feature, you might want to delete the branch for less/clear/easily/ branch management
// delete local branch
git branch -D feature/UI-courses-create
// delete CAREFUL!!!!! servers/origin branch UI-courses-create. 
git push origin --delete feature/UI-courses-create
// verifiy branch is deleted on local pc.
git branch
// verify branch is delted on server/origin
git branch -r
``` 

## Merge  Branches  
// We want to merge feature/branch_one with feature/branche_two. We do work on feature/branch_one.  After commiting all work on branch_one:
```
git checkout feature/branche_two // usually " feature/branche_two" is "develop" branch.
git merge feature/branch_one
```
// now the branch develop contains this new feature, and can continue to work.


## Start work on new feature/bug
#### Start new feature/bug and finish it.
```
// you are in develop  branch, and want to start working on a NEW feature: UI-courses-create. for bug replace feature with bug.
git checkout -b feature/UI-courses-create
// you do some work on it. bla bla. finish work.
git add --all
git commit -m "Create UI for creating course."
// since you finished working on this feature/major work/no plans to work in near future/your partner to check it out: you want to merge those changes back to develop
git checkout develop
git merge feature/UI-courses-create
// now the branch develop contains this new feature, and can continue to work.
``` 

#### Start new feature/bug but don't finish.
// follow all previous steps then:
// from branch UI-courses-create, you push it to server.
// replace feature with bug as needed.
```
git push origin feature/UI-courses-create
```

#### Delete branch of this new feature/bug
```
// after finishing a feature, you might want to delete the branch for less/clear/easily/ branch management
// delete local branch
git branch -D feature/UI-courses-create
// delete servers/origin branch UI-courses-create
git push origin --delete feature/UI-courses-create
// verifiy branch is deleted on local pc.
git branch
// verify branch is delted on server/origin
git branch -r
```

# Wamp - Virtual Host
- http://forum.wampserver.com/read.php?2,124482

# Gitflow  
[Training](https://github.com/nvie/gitflow)  

![Gitflow Diagram](http://nvie.com/img/git-model@2x.png "Visualize")

# [Markdown-Cheatsheet](https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet)  

# localhost - emails
http://www.toolheap.com/test-mail-server-tool/
>Test Mail Server Tool is a full featured mail (SMTP) server emulation to test mail sending capabilities of a web or desktop application or to preview messages before they are actually sent.  

Just install the above app. Run "Test Mail Server Tool". Allow to connect to internet if prompted.  

# [ssh](https://help.github.com/articles/generating-ssh-keys)


### Active/Inactive Members
- This is used only for blocking appointnets/add  - schedule appoinments
