<?php
include_once('include.php');
 
//dump( JLog::test() );
$a = Condition::get_condition("a=23 and b>23 and c>23 and d>=123 and e<= 2334 and f =agsdfsdf" );
var_dump( $a ); 
 //var_dump( TestAuto::get_files( $model_list ));

/*
考虑这个情况， 一个raft节点暂时网络故障，导致进入candidate状态。term增加。然后网络故障消除。收到leader的rpc。发现对方的term比自家的小，然后拒绝。重新开始选举。那就会导致这个节点无限不可用。




*/









 /*
 cab 如果一个分布式网络，举例子一个分片数据库 。 即使每一个节点都设计成高可用的。并且一般情况下节点完全不可用不发生。那么到那个节点的网络故障或者丢包仍然可以让整个系统的ac两个都成立变得不可能。

 在需要强一致性的分布式系统中，只能牺牲可用性。没有别的办法。要不然就不要分布式，要小型机。无可扩展性。
 如果是缓存系统，可以牺牲一致性，因为缓存原本也对一致性没有要求。

 raft是一个一致性算法。保证的是一致性和一定程度的高可用。只是一个复制状态机的算法。它的系统是没有可扩展性的。
 有可扩展性的系统是分片的那种。



在强一致性的分布式系统中，牺牲了可用性。那么如何随时替换节点机器呢。每个节点都是一个高可用节点就可以了。这样机器宕机的风险在一定程度上被规避了。网络之间的不通畅和长时间丢包是主要的风险。现在的几种nosql数据库也是这样设计的,当然区别是强一致性这方面,nosql很可能是最终一致性，提高了可用性。例如在搜索引擎中，极少量的用户在极少量的时候搜索结果缺失了一部分，是可以接受的。但是搜索对所有人无法使用是不可接受的。社交网络中对于评论列表也出现非强一致性的情况是可以容忍的。如果是涉及到用户财产，重要数据，那么必须要重视强一致性，而降低可用性。

例子，game的分布式系统。可用性是首要的。一致性也很重要。水平扩展是一个不错的方案。出现问题的时候，只会影响一部分用户。
然而一般game不需要分布式系统。


*/

