<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: message.proto

namespace Xxgame;

use Google\Protobuf\Internal\DescriptorPool;
use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

class ServerUserBase extends \Google\Protobuf\Internal\Message
{
    private $card_num = 0;
    private $coin_num = 0;
    private $role_id = 0;
    private $message = '';

    public function getCardNum()
    {
        return $this->card_num;
    }

    public function setCardNum($var)
    {
        GPBUtil::checkUint32($var);
        $this->card_num = $var;
    }

    public function getCoinNum()
    {
        return $this->coin_num;
    }

    public function setCoinNum($var)
    {
        GPBUtil::checkUint32($var);
        $this->coin_num = $var;
    }

    public function getRoleId()
    {
        return $this->role_id;
    }

    public function setRoleId($var)
    {
        GPBUtil::checkUint32($var);
        $this->role_id = $var;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($var)
    {
        GPBUtil::checkString($var, True);
        $this->message = $var;
    }

}

$pool = DescriptorPool::getGeneratedPool();

$pool->internalAddGeneratedFile(hex2bin(
    "0a770a0d6d6573736167652e70726f746f1206787867616d6522560a0e53" .
    "6572766572557365724261736512100a08636172645f6e756d1801200128" .
    "0d12100a08636f696e5f6e756d18022001280d120f0a07726f6c655f6964" .
    "18032001280d120f0a076d657373616765180420012809620670726f746f" .
    "33"
));

