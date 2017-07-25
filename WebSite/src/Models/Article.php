<?php
/**
 * Created by PhpStorm.
 * User: plaza
 * Date: 05/01/2017
 * Time: 14:29
 */

namespace DUT\Models;

/*
 * Fonction etant en liant avec le .yml concernants les articles
 */

//Model article correspond à la table Article dans la bd, avec constructeur, getter et setter
class Article
{
    protected $arId;
    protected $arTitle;
    protected $arDescription;
    protected $arDate;
    protected $arLink;
    protected $arDescPhoto;

    /**
     * Article constructor.
     * @param $arTitle
     * @param $arDescription
     */
    public function __construct($arTitle, $arDescription, $arLink, $arDescPhoto)
    {
        $this->arTitle = $arTitle;
        $this->arDescription = $arDescription;
        $this->arLink = $arLink;
        $this->arDescPhoto = $arDescPhoto;
        $this->arDate= new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getArDate()
    {
        return $this->arDate;
    }

    /**
     * @return mixed
     */
    public function getArDescription()
    {
        return $this->arDescription;
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
    public function getArTitle()
    {
        return $this->arTitle;
    }

    /**
     * @param mixed $arDate
     */
    public function setArDate($arDate)
    {
        $this->arDate = $arDate;
    }

    /**
     * @param mixed $arId
     */
    public function setArId($arId)
    {
        $this->arId = $arId;
    }

    /**
     * @param mixed $arTitle
     */
    public function setArTitle($arTitle)
    {
        $this->arTitle = $arTitle;
    }

    /**
     * @return mixed
     */
    public function getArLink()
    {
        return $this->arLink;
    }

    /**
     * @param mixed $arDescription
     */
    public function setArDescription($arDescription)
    {
        $this->arDescription = $arDescription;
    }

    /**
     * @param mixed $arLink
     */
    public function setArLink($arLink)
    {
        $this->arLink = $arLink;
    }

    /**
     * @return mixed
     */
    public function getArDescPhoto()
    {
        return $this->arDescPhoto;
    }

    /**
     * @param mixed $arDescPhoto
     */
    public function setArDescPhoto($arDescPhoto)
    {
        $this->arDescPhoto = $arDescPhoto;
    }

}