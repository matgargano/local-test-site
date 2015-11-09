#!/bin/bash



DATE=`date +%Y%m%d%H%M%S`
CANONICAL_REPO="git@github.com:matgargano/local-test-site.git"
CANONICAL_HOLDING_CELL="/Users/morgandeveloper/temp/canonical-holding-cell"
CANONICAL_PLUGINS_DIR="$CANONICAL_HOLDING_CELL/web/app/plugins"
CANONICAL_THEMES_DIR="$CANONICAL_HOLDING_CELL/web/app/themes"

INTERIM_REPO="git@github.com:matgargano/interim-pantheon-wp.git"
INTERIM_HOLDING_CELL="/Users/morgandeveloper/temp/interim-holding-cell"
INTERIM_PLUGINS_DIR="$INTERIM_HOLDING_CELL/wp-content/plugins"
INTERIM_THEMES_DIR="$INTERIM_HOLDING_CELL/wp-content/themes"

PANTHEON_REPO="ssh://codeserver.dev.d68a4e73-f922-4258-94ff-84749fee08f1@codeserver.dev.d68a4e73-f922-4258-94ff-84749fee08f1.drush.in:2222/~/repository.git"
PANTHEON_HOLDING_CELL="/Users/morgandeveloper/temp/pantheon-holding-cell"
PANTHEON_PLUGINS_DIR="$PANTHEON_HOLDING_CELL/wp-content/plugins"
PANTHEON_THEMES_DIR="$PANTHEON_HOLDING_CELL/wp-content/themes"



rm -rf $CANONICAL_HOLDING_CELL $PANTHEON_HOLDING_CELL $INTERIM_HOLDING_CELL
mkdir -p $CANONICAL_HOLDING_CELL
mkdir -p $PANTHEON_HOLDING_CELL
mkdir -p $INTERIM_HOLDING_CELL

git clone $CANONICAL_REPO $CANONICAL_HOLDING_CELL
cd $CANONICAL_HOLDING_CELL
composer install

git clone $INTERIM_REPO $INTERIM_HOLDING_CELL
git clone $PANTHEON_REPO $PANTHEON_HOLDING_CELL


rm -rf $INTERIM_PLUGINS_DIR $INTERIM_THEMES_DIR
cp -R $CANONICAL_PLUGINS_DIR $INTERIM_PLUGINS_DIR
cp -R $CANONICAL_THEMES_DIR $INTERIM_THEMES_DIR

cd $INTERIM_HOLDING_CELL
git add .
git commit -am "COMMIT $DATE"
git push origin head
git tag -a "$DATE" -m "Tagging for $DATE deploy"
git push origin head
git push origin --tags
