<?php

namespace d3yii2\d3btl\models;

use yii\base\Model;

class ProcessLap4_030 extends Model
{
    /** @var string */
    public $key;

    /** @var int */
    public $ident;

    /** @var string */
    public $quality;

    /** @var string */
    public $recess;

    /** @var string */
    public $comment;

    /** @var string */
    public $parameters;

    /** @var int Distance from beam start to the reference point */
    public $P01;

    /** @var int Distance from the reference edge to the reference point */
    public $P02;

    /** @var int Displacement to the reference side */
    public $P03;

    /** @var int Limit of the 6 faces of the lap, binary code */
    public $P04;

    /** @var int Angle to the reference edge in the reference side*/
    public $P06;

    /** @var int Inclination to the reference side */
    public $P07;

    /** @var int Angle between edge and reference side in face */
    public $P08;

    /** @var int Angle in the floor face */
    public $P09;

    /** @var int Angle between base face and one face of lap */
    public $P10;

    /** @var int Distance (orthogonal) from reference side to point below reference point */
    public $P11;

    /** @var int Length */
    public $P12;

    /** @var int Chamfer angle */
    public $P13;

    /** @var int Grooving depth (length of the lapped scarf in transverse direction)*/
    public $P14;

    /**
     * @param string $parameters
     */
    public function setParameters(string $parameters): void
    {
        $this->parameters = $parameters;
        foreach(explode(' ', $parameters) as $parameter){
            [$parameterName, $parameterValue] = explode(':',$parameter);
            $this->$parameterName = $parameterValue;
        }
    }

    public function isP04bit1(): bool
    {
        return (bool)($this->P04 & 1);
    }

    public function isP04bit2(): bool
    {
        return (bool)($this->P04 & 2);
    }
    public function isP04bit3(): bool
    {
        return (bool)($this->P04 & 4);
    }
    public function isP04bit4(): bool
    {
        return (bool)($this->P04 & 8);
    }
    public function isP04bit5(): bool
    {
        return (bool)($this->P04 & 16);
    }
    public function isP04bit6(): bool
    {
        return (bool)($this->P04 & 32);
    }
}