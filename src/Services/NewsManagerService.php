<?php


namespace App\Services;

use Doctrine\ORM\ORMException;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bridge\Doctrine\RegistryInterface as Doctrine;
use App\Entity\News;

/**
 * News manager Service managing News-posts
 */
class NewsManagerService
{
    /**
     * @var Doctrine
     */
    private  $doctrine;
    /**
     * @var News
     */
    private $news;

    /**
     * NewsManagerService constructor.
     * @param Doctrine $doctrine
     * @param News|null $news
     */
    public function __construct(Doctrine $doctrine, News $news = null)
    {
        $this->doctrine = $doctrine;
        $this->news  = $news;
    }

    /**
     * The method for generate a custom News-post with custom data
     * @return int
     * @throws \Exception
     *
     */
    public function createCustomPostNews()
    {
        if ($this->news){
            $this->save();
            return 0;
        }

        $news = new News();
        $news
            ->setTitle('What is Lorem Ipsum?')
            ->setCreatedAt(new \DateTime('now'))
            ->setPost('Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.

');
        $this->news = $news;
        $this->save();
        return 0;
    }

    /**
     * method for save News-Post in DB
     * @throws \Exception
     */
    public function save()
    {
        $em = $this->doctrine->getEntityManager();
        try {
            $em->persist($this->news);
            $em->flush();

        } catch (ORMException $e) {
            throw new \Exception($e->getMessage());
        }

    }

    /**
     * @return News
     */
    public function getNews(): News
    {
         if ($this->news) return $this->news;
         $this->news = new News();
         return $this->news;

    }

    /**
     * @param News $news
     */
    public function setNews(News $news): void
    {
        $this->news = $news;
    }
}