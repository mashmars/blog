<?php
namespace AdminBundle\Twig;
 
class Extension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('sfa', array($this, 'myunserialize')),
        );
    }
 
    public function myunserialize($serialize)
    {
        $arr = unserialize($serialize);
		return $arr;
    }
	
	  public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('getBulletin', [$this, 'getBulletinFunction']),
        ];
    }
	public function getBulletinFunction(){
		return array('a','b');
	}
 
    public function getName()
    {
        return 'extension';
    }
}