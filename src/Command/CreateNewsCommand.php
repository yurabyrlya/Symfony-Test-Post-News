<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Services\NewsManagerService as NewsManager;

class CreateNewsCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'app:create-news';
    /**
     * @var NewsManager
     */
    private  $newsManager;

    /**
     * CreateNewsCommand constructor.
     * @param NewsManager $newsManager
     */
    public function __construct(NewsManager $newsManager)
    {
        $this->newsManager = $newsManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Generate a custom news')
            ->addArgument('title', InputArgument::OPTIONAL, 'Title for News Post');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $title = $input->getArgument('title');

        if ($title) {
            $io->note(sprintf('creating post-news width title: %s ', $title));
            $news = $this->newsManager->getNews()->setTitle($title);
            $this->newsManager->setNews($news);
        }
        $this->newsManager->createCustomPostNews();
        $io->success('you had created News post with success');

        return 0;
    }
}
