#!/bin/bash

COMMIT_MESSAGE_SDK="";
COMMIT_MESSAGE="pull";

for i in "$@"; do
	if [ "${i:0:9}" = "--cm-sdk=" ]; then COMMIT_MESSAGE_SDK=${i:9}
	elif [ "${i:0:5}" = "--cm=" ]; then COMMIT_MESSAGE=${i:5}
	else echo "$i: Unrecognised parameter."; exit 1
	fi
done

if [ $COMMIT_MESSAGE_SDK == "" ]; then COMMIT_MESSAGE_SDK=$COMMIT_MESSAGE
fi

echo "";
echo "########## Going to SDK ##########";
echo "";

cd includes/sdk;

git status
git add .
git commit -m "$COMMIT_MESSAGE_SDK";
git pull
git push origin master

echo "";
echo "########## Going back to Root ##########";
echo "";

cd ../..

git status
git add .
git commit -m "$COMMIT_MESSAGE";
git pull
git push


exit
/bin/bash