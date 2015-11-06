#!/bin/bash

GIT_REPO="clone git@github.com:matgargano/testpantheon.git"
PANTHEON_REPO="ssh://codeserver.dev.f01647d8-b334-42b6-a823-ba7d1c9ae609@codeserver.dev.f01647d8-b334-42b6-a823-ba7d1c9ae609.drush.in:2222/~/repository.git"
DATE=`date +%Y%m%d%H%M%S`
HOLDING_CELL="/Users/morgandeveloper/Sites/holding-cell"
PANTHEON_HOLDING_CELL="/Users/morgandeveloper/Sites/pantheon-holding-cell"

rm -rf $HOLDING_CELL
rm -rf $PANTHEON_HOLDING_CELL
mkdir -p $HOLDING_CELL
mkdir -p $PANTHEON_HOLDING_CELL

git $GIT_REPO $HOLDING_CELL

cd $HOLDING_CELL
rm -rf wp-content/plugins
rm -rf wp-content/themes
rm -rf wp-content/mu-plugins
if [ -d /Users/morgandeveloper/Sites/pantheon-test/web/app/themes ]; then
  cp -R /Users/morgandeveloper/Sites/pantheon-test/web/app/themes $HOLDING_CELL/wp-content/themes
fi

if [ -d /Users/morgandeveloper/Sites/pantheon-test/web/app/plugins ]; then
  cp -R /Users/morgandeveloper/Sites/pantheon-test/web/app/plugins $HOLDING_CELL/wp-content/plugins
fi

if [ -d /Users/morgandeveloper/Sites/pantheon-test/web/app/mu-plugins ]; then
  cp -R /Users/morgandeveloper/Sites/pantheon-test/web/app/mu-plugins $HOLDING_CELL/wp-content/mu-plugins
fi
cd $HOLDING_CELL
git pull --no-rebase --squash -Xtheirs $PANTHEON_REPO master
git add -A
git commit -m "Syncing with Pantheon core files"

git tag -a $DATE -m "tagging release $DATE"

git push origin head
git push --tags


git clone $PANTHEON_REPO $PANTHEON_HOLDING_CELL

rm -rf $PANTHEON_HOLDING_CELL/wp-content/plugins
rm -rf $PANTHEON_HOLDING_CELL/wp-content/themes
rm -rf $PANTHEON_HOLDING_CELL/wp-content/mu-plugins


if [ -d $HOLDING_CELL/wp-content/themes ]; then
  cp -R $HOLDING_CELL/wp-content/themes $PANTHEON_HOLDING_CELL/wp-content/themes
fi

if [ -d $HOLDING_CELL/wp-content/plugins ]; then
  cp -R $HOLDING_CELL/wp-content/plugins $PANTHEON_HOLDING_CELL/wp-content/plugins
fi

if [ -d $HOLDING_CELL/wp-content/mu-plugins ]; then
  cp -R $HOLDING_CELL/wp-content/mu-plugins $PANTHEON_HOLDING_CELL/wp-content/mu-plugins
fi

cd $PANTHEON_HOLDING_CELL

git add -A
git commit -am "Pushing up $DATE"
git merge --strategy-option ours
git push origin head

