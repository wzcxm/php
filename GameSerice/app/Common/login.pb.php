<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: login.proto

namespace CsppLogin;

use Google\Protobuf\Internal\DescriptorPool;
use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

class ClientLoginInfo extends \Google\Protobuf\Internal\Message
{
    private $uid = 0;
    private $type = 0;
    private $passwd = '';

    public function getUid()
    {
        return $this->uid;
    }

    public function setUid($var)
    {
        GPBUtil::checkUint32($var);
        $this->uid = $var;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($var)
    {
        GPBUtil::checkUint32($var);
        $this->type = $var;
    }

    public function getPasswd()
    {
        return $this->passwd;
    }

    public function setPasswd($var)
    {
        GPBUtil::checkString($var, True);
        $this->passwd = $var;
    }

}

class ServerLoginInfo extends \Google\Protobuf\Internal\Message
{
    private $code = 0;
    private $uid = 0;
    private $nickname = '';
    private $head_img_url = '';
    private $sex = 0;
    private $roomcard = 0;
    private $bubble = 0;
    private $room_id = 0;
    private $rid = 0;
    private $token = '';
    private $passwd = '';
    private $domain_info;
    private $hall_id = 0;
    private $tea_id = 0;
    private $sign = '';

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($var)
    {
        GPBUtil::checkUint32($var);
        $this->code = $var;
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

    public function getNickname()
    {
        return $this->nickname;
    }

    public function setNickname($var)
    {
        GPBUtil::checkString($var, True);
        $this->nickname = $var;
    }

    public function getHeadImgUrl()
    {
        return $this->head_img_url;
    }

    public function setHeadImgUrl($var)
    {
        GPBUtil::checkString($var, True);
        $this->head_img_url = $var;
    }

    public function getSex()
    {
        return $this->sex;
    }

    public function setSex($var)
    {
        GPBUtil::checkUint32($var);
        $this->sex = $var;
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

    public function getBubble()
    {
        return $this->bubble;
    }

    public function setBubble($var)
    {
        GPBUtil::checkInt32($var);
        $this->bubble = $var;
    }

    public function getRoomId()
    {
        return $this->room_id;
    }

    public function setRoomId($var)
    {
        GPBUtil::checkUint32($var);
        $this->room_id = $var;
    }

    public function getRid()
    {
        return $this->rid;
    }

    public function setRid($var)
    {
        GPBUtil::checkUint32($var);
        $this->rid = $var;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($var)
    {
        GPBUtil::checkString($var, True);
        $this->token = $var;
    }

    public function getPasswd()
    {
        return $this->passwd;
    }

    public function setPasswd($var)
    {
        GPBUtil::checkString($var, True);
        $this->passwd = $var;
    }

    public function getDomainInfo()
    {
        return $this->domain_info;
    }

    public function setDomainInfo(&$var)
    {
        GPBUtil::checkRepeatedField($var, GPBType::MESSAGE, \CsppLogin\ServerDomainInfo::class);
        $this->domain_info = $var;
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

    public function getTeaId()
    {
        return $this->tea_id;
    }

    public function setTeaId($var)
    {
        GPBUtil::checkUint32($var);
        $this->tea_id = $var;
    }

    public function getSign()
    {
        return $this->sign;
    }

    public function setSign($var)
    {
        GPBUtil::checkString($var, True);
        $this->sign = $var;
    }

}

class ServerDomainInfo extends \Google\Protobuf\Internal\Message
{
    private $index = 0;
    private $domain = '';
    private $port = 0;
    private $status = 0;

    public function getIndex()
    {
        return $this->index;
    }

    public function setIndex($var)
    {
        GPBUtil::checkUint32($var);
        $this->index = $var;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function setDomain($var)
    {
        GPBUtil::checkString($var, True);
        $this->domain = $var;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function setPort($var)
    {
        GPBUtil::checkUint32($var);
        $this->port = $var;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($var)
    {
        GPBUtil::checkUint32($var);
        $this->status = $var;
    }

}

class ErrorCode
{
    const NO_ERROR = 0;
    const Error_System = 2;
    const Error_Not_Found_User = 3;
    const Error_WeiXin_Login = 4;
    const Error_User_Info = 5;
    const Error_WeiXin_ReLogin = 6;
}

$pool = DescriptorPool::getGeneratedPool();

$pool->internalAddGeneratedFile(hex2bin(
    "0ae2040a0b6c6f67696e2e70726f746f1209437370704c6f67696e223c0a" .
    "0f436c69656e744c6f67696e496e666f120b0a0375696418012001280d12" .
    "0c0a047479706518022001280d120e0a0670617373776418032001280922" .
    "a1020a0f5365727665724c6f67696e496e666f120c0a04636f6465180120" .
    "01280d120b0a0375696418022001280d12100a086e69636b6e616d651803" .
    "2001280912140a0c686561645f696d675f75726c180420012809120b0a03" .
    "73657818052001280d12100a08726f6f6d6361726418062001280d120e0a" .
    "06627562626c65180720012805120f0a07726f6f6d5f696418082001280d" .
    "120b0a0372696418092001280d120d0a05746f6b656e180a20012809120e" .
    "0a06706173737764180b2001280912300a0b646f6d61696e5f696e666f18" .
    "0c2003280b321b2e437370704c6f67696e2e536572766572446f6d61696e" .
    "496e666f120f0a0768616c6c5f6964180d2001280d120e0a067465615f69" .
    "64180e2001280d120c0a047369676e180f20012809224f0a105365727665" .
    "72446f6d61696e496e666f120d0a05696e64657818012001280d120e0a06" .
    "646f6d61696e180220012809120c0a04706f727418032001280d120e0a06" .
    "73746174757318042001280d2a8c010a094572726f72436f6465120c0a08" .
    "4e4f5f4552524f52100012100a0c4572726f725f53797374656d10021218" .
    "0a144572726f725f4e6f745f466f756e645f55736572100312160a124572" .
    "726f725f57656958696e5f4c6f67696e100412130a0f4572726f725f5573" .
    "65725f496e666f100512180a144572726f725f57656958696e5f52654c6f" .
    "67696e1006620670726f746f33"
));

