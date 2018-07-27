<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: gameserice.proto

namespace Xxgame;

use Google\Protobuf\Internal\DescriptorPool;
use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

class TeaList extends \Google\Protobuf\Internal\Message
{
    private $tea_list;

    public function getTeaList()
    {
        return $this->tea_list;
    }

    public function setTeaList(&$var)
    {
        GPBUtil::checkRepeatedField($var, GPBType::MESSAGE, \Xxgame\TeaInfo::class);
        $this->tea_list = $var;
    }

}

class TeaInfo extends \Google\Protobuf\Internal\Message
{
    private $tea_name = '';
    private $uid = 0;
    private $forbid = 0;
    private $msg = '';
    private $method1 = 0;
    private $type1 = 0;
    private $hall1 = '';
    private $method2 = 0;
    private $type2 = 0;
    private $hall2 = '';
    private $method3 = 0;
    private $type3 = 0;
    private $hall3 = '';
    private $tea_id = 0;
    private $head = '';
    private $nickname = '';

    public function getTeaName()
    {
        return $this->tea_name;
    }

    public function setTeaName($var)
    {
        GPBUtil::checkString($var, True);
        $this->tea_name = $var;
    }

    public function getUid()
    {
        return $this->uid;
    }

    public function setUid($var)
    {
        GPBUtil::checkUint32($var);
        $this->uid = $var;
    }

    public function getForbid()
    {
        return $this->forbid;
    }

    public function setForbid($var)
    {
        GPBUtil::checkUint32($var);
        $this->forbid = $var;
    }

    public function getMsg()
    {
        return $this->msg;
    }

    public function setMsg($var)
    {
        GPBUtil::checkString($var, True);
        $this->msg = $var;
    }

    public function getMethod1()
    {
        return $this->method1;
    }

    public function setMethod1($var)
    {
        GPBUtil::checkUint32($var);
        $this->method1 = $var;
    }

    public function getType1()
    {
        return $this->type1;
    }

    public function setType1($var)
    {
        GPBUtil::checkUint32($var);
        $this->type1 = $var;
    }

    public function getHall1()
    {
        return $this->hall1;
    }

    public function setHall1($var)
    {
        GPBUtil::checkString($var, True);
        $this->hall1 = $var;
    }

    public function getMethod2()
    {
        return $this->method2;
    }

    public function setMethod2($var)
    {
        GPBUtil::checkUint32($var);
        $this->method2 = $var;
    }

    public function getType2()
    {
        return $this->type2;
    }

    public function setType2($var)
    {
        GPBUtil::checkUint32($var);
        $this->type2 = $var;
    }

    public function getHall2()
    {
        return $this->hall2;
    }

    public function setHall2($var)
    {
        GPBUtil::checkString($var, True);
        $this->hall2 = $var;
    }

    public function getMethod3()
    {
        return $this->method3;
    }

    public function setMethod3($var)
    {
        GPBUtil::checkUint32($var);
        $this->method3 = $var;
    }

    public function getType3()
    {
        return $this->type3;
    }

    public function setType3($var)
    {
        GPBUtil::checkUint32($var);
        $this->type3 = $var;
    }

    public function getHall3()
    {
        return $this->hall3;
    }

    public function setHall3($var)
    {
        GPBUtil::checkString($var, True);
        $this->hall3 = $var;
    }

    public function getTeaId()
    {
        return $this->tea_id;
    }

    public function setTeaId($var)
    {
        GPBUtil::checkUint32($var);
        $this->tea_id = $var;
    }

    public function getHead()
    {
        return $this->head;
    }

    public function setHead($var)
    {
        GPBUtil::checkString($var, True);
        $this->head = $var;
    }

    public function getNickname()
    {
        return $this->nickname;
    }

    public function setNickname($var)
    {
        GPBUtil::checkString($var, True);
        $this->nickname = $var;
    }

}

class TeaPlayerList extends \Google\Protobuf\Internal\Message
{
    private $player_list;

    public function getPlayerList()
    {
        return $this->player_list;
    }

    public function setPlayerList(&$var)
    {
        GPBUtil::checkRepeatedField($var, GPBType::MESSAGE, \Xxgame\TeaPlayer::class);
        $this->player_list = $var;
    }

}

class TeaPlayer extends \Google\Protobuf\Internal\Message
{
    private $nickname = '';
    private $tea_id = 0;
    private $state = 0;
    private $manager = 0;
    private $hall_id = 0;
    private $uid = 0;
    private $remarks = '';
    private $date = '';
    private $online = 0;
    private $tp_score = 0.0;
    private $head_url = '';
    private $recid = 0;
    private $wan_list;

    public function getNickname()
    {
        return $this->nickname;
    }

    public function setNickname($var)
    {
        GPBUtil::checkString($var, True);
        $this->nickname = $var;
    }

    public function getTeaId()
    {
        return $this->tea_id;
    }

    public function setTeaId($var)
    {
        GPBUtil::checkUint32($var);
        $this->tea_id = $var;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setState($var)
    {
        GPBUtil::checkUint32($var);
        $this->state = $var;
    }

    public function getManager()
    {
        return $this->manager;
    }

    public function setManager($var)
    {
        GPBUtil::checkUint32($var);
        $this->manager = $var;
    }

    public function getHallId()
    {
        return $this->hall_id;
    }

    public function setHallId($var)
    {
        GPBUtil::checkUint32($var);
        $this->hall_id = $var;
    }

    public function getUid()
    {
        return $this->uid;
    }

    public function setUid($var)
    {
        GPBUtil::checkUint32($var);
        $this->uid = $var;
    }

    public function getRemarks()
    {
        return $this->remarks;
    }

    public function setRemarks($var)
    {
        GPBUtil::checkString($var, True);
        $this->remarks = $var;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($var)
    {
        GPBUtil::checkString($var, True);
        $this->date = $var;
    }

    public function getOnline()
    {
        return $this->online;
    }

    public function setOnline($var)
    {
        GPBUtil::checkUint32($var);
        $this->online = $var;
    }

    public function getTpScore()
    {
        return $this->tp_score;
    }

    public function setTpScore($var)
    {
        GPBUtil::checkFloat($var);
        $this->tp_score = $var;
    }

    public function getHeadUrl()
    {
        return $this->head_url;
    }

    public function setHeadUrl($var)
    {
        GPBUtil::checkString($var, True);
        $this->head_url = $var;
    }

    public function getRecid()
    {
        return $this->recid;
    }

    public function setRecid($var)
    {
        GPBUtil::checkUint32($var);
        $this->recid = $var;
    }

    public function getWanList()
    {
        return $this->wan_list;
    }

    public function setWanList(&$var)
    {
        GPBUtil::checkRepeatedField($var, GPBType::MESSAGE, \Xxgame\WinAndNum::class);
        $this->wan_list = $var;
    }

}

class WinAndNum extends \Google\Protobuf\Internal\Message
{
    private $one_hall_win = 0;
    private $two_hall_win = 0;
    private $three_hall_win = 0;
    private $one_hall_num = 0;
    private $two_hall_num = 0;
    private $three_hall_num = 0;
    private $days = 0;

    public function getOneHallWin()
    {
        return $this->one_hall_win;
    }

    public function setOneHallWin($var)
    {
        GPBUtil::checkUint32($var);
        $this->one_hall_win = $var;
    }

    public function getTwoHallWin()
    {
        return $this->two_hall_win;
    }

    public function setTwoHallWin($var)
    {
        GPBUtil::checkUint32($var);
        $this->two_hall_win = $var;
    }

    public function getThreeHallWin()
    {
        return $this->three_hall_win;
    }

    public function setThreeHallWin($var)
    {
        GPBUtil::checkUint32($var);
        $this->three_hall_win = $var;
    }

    public function getOneHallNum()
    {
        return $this->one_hall_num;
    }

    public function setOneHallNum($var)
    {
        GPBUtil::checkUint32($var);
        $this->one_hall_num = $var;
    }

    public function getTwoHallNum()
    {
        return $this->two_hall_num;
    }

    public function setTwoHallNum($var)
    {
        GPBUtil::checkUint32($var);
        $this->two_hall_num = $var;
    }

    public function getThreeHallNum()
    {
        return $this->three_hall_num;
    }

    public function setThreeHallNum($var)
    {
        GPBUtil::checkUint32($var);
        $this->three_hall_num = $var;
    }

    public function getDays()
    {
        return $this->days;
    }

    public function setDays($var)
    {
        GPBUtil::checkUint32($var);
        $this->days = $var;
    }

}

class RecordList extends \Google\Protobuf\Internal\Message
{
    private $Record_list;
    private $total = 0;

    public function getRecordList()
    {
        return $this->Record_list;
    }

    public function setRecordList(&$var)
    {
        GPBUtil::checkRepeatedField($var, GPBType::MESSAGE, \Xxgame\Record::class);
        $this->Record_list = $var;
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function setTotal($var)
    {
        GPBUtil::checkUint32($var);
        $this->total = $var;
    }

}

class Record extends \Google\Protobuf\Internal\Message
{
    private $roomid = 0;
    private $number = 0;
    private $createtime = '';
    private $player;
    private $gametype = 0;
    private $gameno = 0;
    private $hall_num = 0;

    public function getRoomid()
    {
        return $this->roomid;
    }

    public function setRoomid($var)
    {
        GPBUtil::checkUint32($var);
        $this->roomid = $var;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function setNumber($var)
    {
        GPBUtil::checkUint32($var);
        $this->number = $var;
    }

    public function getCreatetime()
    {
        return $this->createtime;
    }

    public function setCreatetime($var)
    {
        GPBUtil::checkString($var, True);
        $this->createtime = $var;
    }

    public function getPlayer()
    {
        return $this->player;
    }

    public function setPlayer(&$var)
    {
        GPBUtil::checkRepeatedField($var, GPBType::MESSAGE, \Xxgame\Playerinfo::class);
        $this->player = $var;
    }

    public function getGametype()
    {
        return $this->gametype;
    }

    public function setGametype($var)
    {
        GPBUtil::checkUint32($var);
        $this->gametype = $var;
    }

    public function getGameno()
    {
        return $this->gameno;
    }

    public function setGameno($var)
    {
        GPBUtil::checkUint32($var);
        $this->gameno = $var;
    }

    public function getHallNum()
    {
        return $this->hall_num;
    }

    public function setHallNum($var)
    {
        GPBUtil::checkUint32($var);
        $this->hall_num = $var;
    }

}

class SingleList extends \Google\Protobuf\Internal\Message
{
    private $Single_list;
    private $total = 0;

    public function getSingleList()
    {
        return $this->Single_list;
    }

    public function setSingleList(&$var)
    {
        GPBUtil::checkRepeatedField($var, GPBType::MESSAGE, \Xxgame\Single::class);
        $this->Single_list = $var;
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function setTotal($var)
    {
        GPBUtil::checkUint32($var);
        $this->total = $var;
    }

}

class Single extends \Google\Protobuf\Internal\Message
{
    private $record_id = 0;
    private $createtime = '';
    private $player;
    private $index = 0;

    public function getRecordId()
    {
        return $this->record_id;
    }

    public function setRecordId($var)
    {
        GPBUtil::checkUint32($var);
        $this->record_id = $var;
    }

    public function getCreatetime()
    {
        return $this->createtime;
    }

    public function setCreatetime($var)
    {
        GPBUtil::checkString($var, True);
        $this->createtime = $var;
    }

    public function getPlayer()
    {
        return $this->player;
    }

    public function setPlayer(&$var)
    {
        GPBUtil::checkRepeatedField($var, GPBType::MESSAGE, \Xxgame\Playerinfo::class);
        $this->player = $var;
    }

    public function getIndex()
    {
        return $this->index;
    }

    public function setIndex($var)
    {
        GPBUtil::checkUint32($var);
        $this->index = $var;
    }

}

class Playerinfo extends \Google\Protobuf\Internal\Message
{
    private $nickname = '';
    private $head = '';
    private $roomcard = 0;
    private $role = 0;
    private $marquee = '';
    private $urgent = '';
    private $score = 0;
    private $uid = 0;
    private $tili = 0;
    private $iswin = 0;

    public function getNickname()
    {
        return $this->nickname;
    }

    public function setNickname($var)
    {
        GPBUtil::checkString($var, True);
        $this->nickname = $var;
    }

    public function getHead()
    {
        return $this->head;
    }

    public function setHead($var)
    {
        GPBUtil::checkString($var, True);
        $this->head = $var;
    }

    public function getRoomcard()
    {
        return $this->roomcard;
    }

    public function setRoomcard($var)
    {
        GPBUtil::checkUint32($var);
        $this->roomcard = $var;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($var)
    {
        GPBUtil::checkUint32($var);
        $this->role = $var;
    }

    public function getMarquee()
    {
        return $this->marquee;
    }

    public function setMarquee($var)
    {
        GPBUtil::checkString($var, True);
        $this->marquee = $var;
    }

    public function getUrgent()
    {
        return $this->urgent;
    }

    public function setUrgent($var)
    {
        GPBUtil::checkString($var, True);
        $this->urgent = $var;
    }

    public function getScore()
    {
        return $this->score;
    }

    public function setScore($var)
    {
        GPBUtil::checkInt32($var);
        $this->score = $var;
    }

    public function getUid()
    {
        return $this->uid;
    }

    public function setUid($var)
    {
        GPBUtil::checkUint32($var);
        $this->uid = $var;
    }

    public function getTili()
    {
        return $this->tili;
    }

    public function setTili($var)
    {
        GPBUtil::checkInt32($var);
        $this->tili = $var;
    }

    public function getIswin()
    {
        return $this->iswin;
    }

    public function setIswin($var)
    {
        GPBUtil::checkUint32($var);
        $this->iswin = $var;
    }

}

class Business extends \Google\Protobuf\Internal\Message
{
    private $tea_num = 0;
    private $hall_num_1 = 0;
    private $hall_num_2 = 0;
    private $hall_num_3 = 0;
    private $date_day = '';

    public function getTeaNum()
    {
        return $this->tea_num;
    }

    public function setTeaNum($var)
    {
        GPBUtil::checkUint32($var);
        $this->tea_num = $var;
    }

    public function getHallNum1()
    {
        return $this->hall_num_1;
    }

    public function setHallNum1($var)
    {
        GPBUtil::checkUint32($var);
        $this->hall_num_1 = $var;
    }

    public function getHallNum2()
    {
        return $this->hall_num_2;
    }

    public function setHallNum2($var)
    {
        GPBUtil::checkUint32($var);
        $this->hall_num_2 = $var;
    }

    public function getHallNum3()
    {
        return $this->hall_num_3;
    }

    public function setHallNum3($var)
    {
        GPBUtil::checkUint32($var);
        $this->hall_num_3 = $var;
    }

    public function getDateDay()
    {
        return $this->date_day;
    }

    public function setDateDay($var)
    {
        GPBUtil::checkString($var, True);
        $this->date_day = $var;
    }

}

class BusinessList extends \Google\Protobuf\Internal\Message
{
    private $bus_list;

    public function getBusList()
    {
        return $this->bus_list;
    }

    public function setBusList(&$var)
    {
        GPBUtil::checkRepeatedField($var, GPBType::MESSAGE, \Xxgame\Business::class);
        $this->bus_list = $var;
    }

}

class PlayWinList extends \Google\Protobuf\Internal\Message
{
    private $playwin_list;

    public function getPlaywinList()
    {
        return $this->playwin_list;
    }

    public function setPlaywinList(&$var)
    {
        GPBUtil::checkRepeatedField($var, GPBType::MESSAGE, \Xxgame\PlayWin::class);
        $this->playwin_list = $var;
    }

}

class PlayWin extends \Google\Protobuf\Internal\Message
{
    private $nickname = '';
    private $head = '';
    private $uid = 0;
    private $hall_win_one = 0;
    private $hall_win_two = 0;
    private $hall_win_three = 0;
    private $total_win = 0;

    public function getNickname()
    {
        return $this->nickname;
    }

    public function setNickname($var)
    {
        GPBUtil::checkString($var, True);
        $this->nickname = $var;
    }

    public function getHead()
    {
        return $this->head;
    }

    public function setHead($var)
    {
        GPBUtil::checkString($var, True);
        $this->head = $var;
    }

    public function getUid()
    {
        return $this->uid;
    }

    public function setUid($var)
    {
        GPBUtil::checkUint32($var);
        $this->uid = $var;
    }

    public function getHallWinOne()
    {
        return $this->hall_win_one;
    }

    public function setHallWinOne($var)
    {
        GPBUtil::checkUint32($var);
        $this->hall_win_one = $var;
    }

    public function getHallWinTwo()
    {
        return $this->hall_win_two;
    }

    public function setHallWinTwo($var)
    {
        GPBUtil::checkUint32($var);
        $this->hall_win_two = $var;
    }

    public function getHallWinThree()
    {
        return $this->hall_win_three;
    }

    public function setHallWinThree($var)
    {
        GPBUtil::checkUint32($var);
        $this->hall_win_three = $var;
    }

    public function getTotalWin()
    {
        return $this->total_win;
    }

    public function setTotalWin($var)
    {
        GPBUtil::checkUint32($var);
        $this->total_win = $var;
    }

}

$pool = DescriptorPool::getGeneratedPool();

$pool->internalAddGeneratedFile(hex2bin(
    "0ab40d0a1067616d657365726963652e70726f746f1206787867616d6522" .
    "2c0a075465614c69737412210a087465615f6c69737418012003280b320f" .
    "2e787867616d652e546561496e666f2282020a07546561496e666f12100a" .
    "087465615f6e616d65180120012809120b0a0375696418022001280d120e" .
    "0a06666f7262696418032001280d120b0a036d7367180420012809120f0a" .
    "076d6574686f643118052001280d120d0a05747970653118062001280d12" .
    "0d0a0568616c6c31180720012809120f0a076d6574686f64321808200128" .
    "0d120d0a05747970653218092001280d120d0a0568616c6c32180a200128" .
    "09120f0a076d6574686f6433180b2001280d120d0a057479706533180c20" .
    "01280d120d0a0568616c6c33180d20012809120e0a067465615f6964180e" .
    "2001280d120c0a0468656164180f2001280912100a086e69636b6e616d65" .
    "18102001280922370a0d546561506c617965724c69737412260a0b706c61" .
    "7965725f6c69737418012003280b32112e787867616d652e546561506c61" .
    "79657222f2010a09546561506c6179657212100a086e69636b6e616d6518" .
    "0120012809120e0a067465615f696418022001280d120d0a057374617465" .
    "18032001280d120f0a076d616e6167657218042001280d120f0a0768616c" .
    "6c5f696418052001280d120b0a0375696418062001280d120f0a0772656d" .
    "61726b73180720012809120c0a0464617465180820012809120e0a066f6e" .
    "6c696e6518092001280d12100a0874705f73636f7265180a200128021210" .
    "0a08686561645f75726c180b20012809120d0a057265636964180c200128" .
    "0d12230a0877616e5f6c697374180d2003280b32112e787867616d652e57" .
    "696e416e644e756d22a1010a0957696e416e644e756d12140a0c6f6e655f" .
    "68616c6c5f77696e18012001280d12140a0c74776f5f68616c6c5f77696e" .
    "18022001280d12160a0e74687265655f68616c6c5f77696e18032001280d" .
    "12140a0c6f6e655f68616c6c5f6e756d18042001280d12140a0c74776f5f" .
    "68616c6c5f6e756d18052001280d12160a0e74687265655f68616c6c5f6e" .
    "756d18062001280d120c0a046461797318072001280d22400a0a5265636f" .
    "72644c69737412230a0b5265636f72645f6c69737418012003280b320e2e" .
    "787867616d652e5265636f7264120d0a05746f74616c18022001280d2294" .
    "010a065265636f7264120e0a06726f6f6d696418012001280d120e0a066e" .
    "756d62657218022001280d12120a0a63726561746574696d651803200128" .
    "0912220a06706c6179657218042003280b32122e787867616d652e506c61" .
    "796572696e666f12100a0867616d657479706518052001280d120e0a0667" .
    "616d656e6f18062001280d12100a0868616c6c5f6e756d18072001280d22" .
    "400a0a53696e676c654c69737412230a0b53696e676c655f6c6973741801" .
    "2003280b320e2e787867616d652e53696e676c65120d0a05746f74616c18" .
    "022001280d22620a0653696e676c6512110a097265636f72645f69641801" .
    "2001280d12120a0a63726561746574696d6518022001280912220a06706c" .
    "6179657218032003280b32122e787867616d652e506c61796572696e666f" .
    "120d0a05696e64657818042001280d22a6010a0a506c61796572696e666f" .
    "12100a086e69636b6e616d65180120012809120c0a046865616418022001" .
    "280912100a08726f6f6d6361726418032001280d120c0a04726f6c651804" .
    "2001280d120f0a076d617271756565180520012809120e0a06757267656e" .
    "74180620012809120d0a0573636f7265180720012805120b0a0375696418" .
    "082001280d120c0a0474696c69180920012805120d0a05697377696e180a" .
    "2001280d22690a08427573696e657373120f0a077465615f6e756d180120" .
    "01280d12120a0a68616c6c5f6e756d5f3118022001280d12120a0a68616c" .
    "6c5f6e756d5f3218032001280d12120a0a68616c6c5f6e756d5f33180420" .
    "01280d12100a08646174655f64617918052001280922320a0c427573696e" .
    "6573734c69737412220a086275735f6c69737418012003280b32102e7878" .
    "67616d652e427573696e65737322340a0b506c617957696e4c6973741225" .
    "0a0c706c617977696e5f6c69737418012003280b320f2e787867616d652e" .
    "506c617957696e228d010a07506c617957696e12100a086e69636b6e616d" .
    "65180120012809120c0a0468656164180220012809120b0a037569641803" .
    "2001280d12140a0c68616c6c5f77696e5f6f6e6518042001280d12140a0c" .
    "68616c6c5f77696e5f74776f18052001280d12160a0e68616c6c5f77696e" .
    "5f746872656518062001280d12110a09746f74616c5f77696e1807200128" .
    "0d620670726f746f33"
));

