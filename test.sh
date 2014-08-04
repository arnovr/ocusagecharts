git filter-branch -f --env-filter '
 
an="arno"
am="arno@mijndomein.nl"
cn="arno"
cm="arno@mijndomein.nl"
 
if [ "$GIT_COMMITTER_EMAIL" = "arno@mijndomein.nl" ]
then
    cn="arno"
    cm="arno@van-rossum.com"
fi
if [ "$GIT_AUTHOR_EMAIL" = "arno@mijndomein.nl" ]
then
    an="arnovr"
    am="arno@van-rossum.com"
fi
 
export GIT_AUTHOR_NAME="$an"
export GIT_AUTHOR_EMAIL="$am"
export GIT_COMMITTER_NAME="$cn"
export GIT_COMMITTER_EMAIL="$cm"
'
