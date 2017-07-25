<?php
/**
 * Created by PhpStorm.
 * User: plaza
 * Date: 05/01/2017
 * Time: 16:18
 */

namespace DUT\Models;

/*
 * Fonction etant en liant avec le .yml concernants les comment
 */

//Model commentaire correspond à la table Comment dans la bd, avec constructeur, getter et setter
class Comment
{
    protected $arId;
    protected $coId;
    protected $coPseudo;
    protected $coDescription;
    protected $coDate;

    /**
     * Comment constructor.
     * @param $arId
     * @param $coPseudo
     * @param $coDescription
     */
    public function __construct($arId, $coPseudo, $coDescription)
    {
        $this->arId = $arId;
        $this->coPseudo = $coPseudo;
        $this->coDescription = $coDescription;
        $this->coDate= new \DateTime();
    }


    /**
     * @param mixed $arId
     */
    public function setArId($arId)
    {
        $this->arId = $arId;
    }

    /**
     * @return mixed
     */
    public function getArId()
    {
        return $this->arId;
    }

    /**
     * @return mixed
     */
    public function getCoDescription()
    {
        return $this->coDescription;
    }

    /**
     * @return mixed
     */
    public function getCoId()
    {
        return $this->coId;
    }

    /**
     * @return mixed
     */
    public function getCoPseudo()
    {
        return $this->coPseudo;
    }

    /**
     * @param mixed $coDescription
     */
    public function setCoDescription($coDescription)
    {
        $this->coDescription = $coDescription;
    }

    /**
     * @param mixed $coId
     */
    public function setCoId($coId)
    {
        $this->coId = $coId;
    }

    /**
     * @param mixed $coPseudo
     */
    public function setCoPseudo($coPseudo)
    {
        $this->coPseudo = $coPseudo;
    }

    /**
     * @return mixed
     */
    public function getCoDate()
    {
        return $this->coDate;
    }

    /**
     * @param mixed $coDate
     */
    public function setCoDate($coDate)
    {
        $this->coDate = $coDate;
    }
}