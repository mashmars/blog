<?php
namespace AdminBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Category
 * @package AdminBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="article")
 */

class Article
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private  $id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="category_id",referencedColumnName="id")
     */
    private $categoryId;
    /**
     * @ORM\ManyToOne(targetEntity="Tag")
     * @ORM\JoinColumn(name="tag_id",referencedColumnName="id")
     */
    private $tagId;
    /**
     * @ORM\Column(type="string",length=100)
     */
    private $title;
    /**
     * @ORM\Column(type="string")
     */
    private $descript;
    /**
     * @ORM\Column(type="text")
     */
    private $content;
    /**
     * @ORM\Column(type="integer",length=6)
     */
    private $views;
    /**
     * @ORM\Column(type="smallint",length=4)
     */
    private $status;
    /**
     * @ORM\Column(type="smallint",length=4,name="status_reply")
     */
    private  $statusReply;
    /**
     * @ORM\Column(type="smallint",length=4)
     */
    private $position;
    /**
     * @ORM\Column(type="integer",name="created_at",length=11)
     */
    private $createdAt;
    /**
     * @ORM\Column(type="integer",name="updated_at",length=11)
     */
    private $updatedAt;





    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set descript
     *
     * @param string $descript
     * @return Article
     */
    public function setDescript($descript)
    {
        $this->descript = $descript;

        return $this;
    }

    /**
     * Get descript
     *
     * @return string 
     */
    public function getDescript()
    {
        return $this->descript;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Article
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set views
     *
     * @param integer $views
     * @return Article
     */
    public function setViews($views)
    {
        $this->views = $views;

        return $this;
    }

    /**
     * Get views
     *
     * @return integer 
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Article
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set statusReply
     *
     * @param integer $statusReply
     * @return Article
     */
    public function setStatusReply($statusReply)
    {
        $this->statusReply = $statusReply;

        return $this;
    }

    /**
     * Get statusReply
     *
     * @return integer 
     */
    public function getStatusReply()
    {
        return $this->statusReply;
    }

    /**
     * Set position
     *
     * @param integer $position
     * @return Article
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set createdAt
     *
     * @param integer $createdAt
     * @return Article
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return integer 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param integer $updatedAt
     * @return Article
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return integer 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set categoryId
     *
     * @param \AdminBundle\Entity\Category $categoryId
     * @return Article
     */
    public function setCategoryId(\AdminBundle\Entity\Category $categoryId = null)
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    /**
     * Get categoryId
     *
     * @return \AdminBundle\Entity\Category 
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * Set tagId
     *
     * @param \AdminBundle\Entity\Tag $tagId
     * @return Article
     */
    public function setTagId(\AdminBundle\Entity\Tag $tagId = null)
    {
        $this->tagId = $tagId;

        return $this;
    }

    /**
     * Get tagId
     *
     * @return \AdminBundle\Entity\Tag 
     */
    public function getTagId()
    {
        return $this->tagId;
    }
}
