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
    private $numbers = 0;
    private $hall_id = 0;
    private $uid = 0;
    private $winnum = 0;
    private $remarks = '';
    private $date = '';
    private $online = 0;

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

    public function getNumbers()
    {
        return $this->numbers;
    }

    public function setNumbers($var)
    {
        GPBUtil::checkUint32($var);
        $this->numbers = $var;
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

    public function getWinnum()
    {
        return $this->winnum;
    }

    public function setWinnum($var)
    {
        GPBUtil::checkUint32($var);
        $this->winnum = $var;
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
        GPBUtil::checkUint32($var);
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

$pool = DescriptorPool::getGeneratedPool();

$pool->internalAddGeneratedFile(hex2bin(
    "0ad3090a1067616d657365726963652e70726f746f1206787867616d6522" .
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
    "79657222bb010a09546561506c6179657212100a086e69636b6e616d6518" .
    "0120012809120e0a067465615f696418022001280d120d0a057374617465" .
    "18032001280d120f0a076d616e6167657218042001280d120f0a076e756d" .
    "6265727318052001280d120f0a0768616c6c5f696418062001280d120b0a" .
    "0375696418072001280d120e0a0677696e6e756d18082001280d120f0a07" .
    "72656d61726b73180920012809120c0a0464617465180a20012809120e0a" .
    "066f6e6c696e65180b2001280d22400a0a5265636f72644c69737412230a" .
    "0b5265636f72645f6c69737418012003280b320e2e787867616d652e5265" .
    "636f7264120d0a05746f74616c18022001280d22720a065265636f726412" .
    "0e0a06726f6f6d696418012001280d120e0a066e756d6265721802200128" .
    "0d12120a0a63726561746574696d6518032001280912220a06706c617965" .
    "7218042003280b32122e787867616d652e506c61796572696e666f12100a" .
    "0867616d657479706518052001280d22400a0a53696e676c654c69737412" .
    "230a0b53696e676c655f6c69737418012003280b320e2e787867616d652e" .
    "53696e676c65120d0a05746f74616c18022001280d22620a0653696e676c" .
    "6512110a097265636f72645f696418012001280d12120a0a637265617465" .
    "74696d6518022001280912220a06706c6179657218032003280b32122e78" .
    "7867616d652e506c61796572696e666f120d0a05696e6465781804200128" .
    "0d2289010a0a506c61796572696e666f12100a086e69636b6e616d651801" .
    "20012809120c0a046865616418022001280912100a08726f6f6d63617264" .
    "18032001280d120c0a04726f6c6518042001280d120f0a076d6172717565" .
    "65180520012809120e0a06757267656e74180620012809120d0a0573636f" .
    "726518072001280d120b0a0375696418082001280d22690a08427573696e" .
    "657373120f0a077465615f6e756d18012001280d12120a0a68616c6c5f6e" .
    "756d5f3118022001280d12120a0a68616c6c5f6e756d5f3218032001280d" .
    "12120a0a68616c6c5f6e756d5f3318042001280d12100a08646174655f64" .
    "617918052001280922320a0c427573696e6573734c69737412220a086275" .
    "735f6c69737418012003280b32102e787867616d652e427573696e657373" .
    "620670726f746f33"
));

