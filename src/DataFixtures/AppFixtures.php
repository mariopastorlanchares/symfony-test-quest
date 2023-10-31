<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Category;
use App\Entity\Question;
use Certificationy\Certification\Loader;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $path = 'src/DataFixtures/config.yml';
        $categories = Loader::getCategories($path);
        echo "Starting progress: 0/" . count($categories) . "\n";
        foreach ($categories as $categoryName) {
            $categoryEntity = new Category();
            $categoryEntity->setName($categoryName);
            $manager->persist($categoryEntity);
            $set = Loader::init(9999, [$categoryName], $path);
            $questions = $set->getQuestions();
            $batchSize = 10;
            foreach ($questions as $i => $questionData) {
                $questionEntity = new Question();
                $questionEntity->setCategory($categoryEntity);
                $questionEntity->setText($questionData->getQuestion());

                $manager->persist($questionEntity);

                foreach ($questionData->getAnswers() as $answerData) {
                    $answerEntity = new Answer();
                    $answerEntity->setText($answerData->getValue());
                    $answerEntity->setIsCorrect($answerData->isCorrect());
                    $answerEntity->setQuestion($questionEntity);
                    $manager->persist($answerEntity);
                }
                if (($i % $batchSize) === 0) {
                    $manager->flush();
                    $manager->clear();
                }
            }
            echo ".";
        }
        echo "\n";
        $manager->flush();
    }
}
