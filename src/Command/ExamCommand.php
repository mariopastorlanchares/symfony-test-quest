<?php

namespace App\Command;

use App\Repository\CategoryRepository;
use App\Repository\QuestionRepository;
use Certificationy\Certification\Loader;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:exam',
    description: 'Starts a new exam session.',
)]
class ExamCommand extends Command
{
    private QuestionRepository $questionRepository;
    private CategoryRepository $categoryRepository;

    protected function configure(): void
    {

    }

    public function __construct(
        QuestionRepository $questionRepository,
        CategoryRepository $categoryRepository,
        string             $name = null
    )
    {
        parent::__construct($name);
        $this->questionRepository = $questionRepository;
        $this->categoryRepository = $categoryRepository;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $questionHelper = $this->getHelper('question');
        $score = 0;

        $numberOfQuestionsChoice = new ChoiceQuestion(
            'How many questions do you want to answer? (default is 20)',
            ['90', '45', '20'], // Opciones
            2 // Opción predeterminada
        );
        $numberOfQuestionsChoice->setErrorMessage('Choice %s is invalid.');

        $totalNumberOfQuestions = (int) $questionHelper->ask($input, $output, $numberOfQuestionsChoice);

        // Obtener todas las categorías
        $categories = $this->categoryRepository->findAll();
        $totalCategories = count($categories);
        $questionsDistribution = $this->calculateQuestionsPerCategory(
            $totalNumberOfQuestions,
            $totalCategories
        );
        $questions = [];

        foreach ($categories as $index => $category) {
            $numberOfQuestionsForThisCategory = $questionsDistribution[$index];
            $randomQuestions = $this->questionRepository->findRandomByCategory(
                $category,
                $numberOfQuestionsForThisCategory
            );
            foreach ($randomQuestions as $question) {
                $questions[] = [
                    'question' => $question,
                    'category' => $category->getName(),
                ];
            }
        }

        shuffle($questions);
        foreach ($questions as $index => $data) {
            $question = $data['question'];
            $categoryName = $data['category'];
            $questionNumber = $index + 1;
            $io->section("Question $questionNumber / $totalNumberOfQuestions ($categoryName)");

            // Collect answers and turn them into a simple array
            $choices = [];
            foreach ($question->getAnswers() as $key => $answer) {
                $choices[$key] = $answer->getText();
            }

            // Pose the question as a multiple-choice question
            $questionText = "\033[1m" . $question->getText() . "\033[0m";
            $choiceQuestion = new ChoiceQuestion(
                $questionText,
                $choices,
                null
            );
            $choiceQuestion->setMultiselect(true);
            $selectedAnswers = $questionHelper->ask($input, $output, $choiceQuestion);

            // Las respuestas correctas como un array de texto
            $correctAnswersTexts = array_map(
                fn($a) => $a->getText(),
                $question->getAnswers()->filter(fn($a) => $a->isIsCorrect())->toArray()
            );

            // Comprobar si las respuestas seleccionadas son correctas
            $isCorrect = !array_diff($correctAnswersTexts, $selectedAnswers) && !array_diff($selectedAnswers, $correctAnswersTexts);

            if ($isCorrect) {
                $io->success('Correct answer!');
                $score++;
            } else {
                $io->error('Incorrect answer!');
            }
        }

        // Show final results
        $io->success("You have completed the exam with a score of $score / " . count($questions));

        return Command::SUCCESS;
    }

    private function calculateQuestionsPerCategory(int $totalNumberOfQuestions, int $totalCategories): array
    {
        $baseNumber = intdiv($totalNumberOfQuestions, $totalCategories);
        $remainder = $totalNumberOfQuestions % $totalCategories;

        $questionsPerCategory = array_fill(0, $totalCategories, $baseNumber);

        for ($i = 0; $i < $remainder; $i++) {
            $questionsPerCategory[$i]++;
        }

        return $questionsPerCategory;
    }

}
