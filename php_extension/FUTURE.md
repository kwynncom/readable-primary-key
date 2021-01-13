2021/01/13 - ideas for future work

BUILD HELP

Create logic for when I need "./configure" because if I am compiling over and over, I tend to comment it out, then get confused days or weeks later.

Create a tmp .so file for testing in the /tmp/npk folder.  Otherwise, test.php uses the installed version.

Note the core phpversion() function that also gives versions of extensions:

$name = 'nanopk';
echo($name . ' version: ' . phpversion($name) . "\n");
