<?php
/** 
 * pull out 5 more followers in the list for each AJAX request
 * there are only 8 more to load, so just 2 pages of results.
 *
 * typically with mysql_query or mysqli_query you can check the very last entry.
 * we could determine if this next page is the last one by checking against the very last follower ID
 * here is a very basic example:
 * mysql_query('SELECT follows.datetime, follows.followerid FROM TABLE follows WHERE follows.profileid = $_POST['uid'] ORDER BY follows.datetime DESC LIMIT 1')
 *
 * we get the followerid of each user who followed and the time they followed. we only want the people who follow a certain profileid, which would correspond to the same user they all followed(passed in via Ajax). We then order results by the oldest datetime and check to make sure each follower doesn't match this last ID. If somebody does then we hit the end of the list.
 *
 * For this example let's skip all the mysql stuff and pretend like we already converted the results to arrays.
**/

header("content-type:application/json");

$spreadid = $_POST['spreadid'];
$datetime = date("Y-m-d H:i:s");

$readingdata = '0785f0866571844290985b34b342e1be3a293031270263282144260814081918';

$pg1 = array(
       'cardids' => array
       (
            1,5,7
       ),
       'spreadid' => $spreadid,
       'readingdata' => $readingdata
);

echo json_encode($pg1);

exit();

?>