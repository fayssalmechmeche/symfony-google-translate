<?php
/**
 * This file is part of the Short Hint | Google Translator project.
 *
 * (c) Sabri Hamda <sabri@hamda.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ShortHint\GoogleTranslatorBundle\Command;

use ShortHint\GoogleTranslatorBundle\Utils\Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class GeneratorCommand.
 */
class GeneratorCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'google:translate';

    /**
     * @var null
     */
    private $apiKey;

    /**
     * @var Generator
     */
    private $generator;

    /**
     * Default application language.
     *
     * @var string
     */
    private $locale;

    /**
     * The languages ​​in which the application must be translated.
     *
     * @var array
     */
    private $targetLanguages = [];

    /**
     * XLFGeneratorCommand constructor.
     *
     * @param Generator $generator
     * @param $defaultLocale
     * @param $targetLanguages
     * @param $apiKey
     */
    public function __construct(Generator $generator, $defaultLocale, $targetLanguages, $apiKey = null)
    {
        $this->generator = $generator;
        $this->targetLanguages = $targetLanguages;
        $this->locale = $defaultLocale;
        $this->apiKey = $apiKey;

        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this->setName('google:translate')
            ->setDescription('Translate and generate translation messages');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     *
     * @throws ExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $errorIo = $io->getErrorStyle();

        $output->writeln([
            '',
            ' <fg=blue>Short Hint</> <fg=blue>G</><fg=red>o</><fg=yellow>o</><fg=blue>g</><fg=green>l</><fg=red>e</> <fg=blue>Translator</>',
            ' <fg=blue>====================</>',
            ' // Checking your configuration...',
            '',
        ]);

        //Check application locales & targetLanguages
        $progressBar = new ProgressBar($output, 100);
        $progressBar->start();
        $i = 0;
        $targetLanguages = ['fr'];
        while ($i++ < 2) {
            sleep(1);
            if (!empty($this->locale)) {
//                $this->locale = $this->translator->getLocale();
            } else {
                $errorIo->error('You must config your locale application languege in [config/services.yaml]');
                return Command::INVALID;
                //exit();
            }

            if (!empty($this->targetLanguages)) {
//                $this->targetLanguages[] = $this->translator->gettargetLanguages();
//                $shiftTargetLanguages = array_shift($this->targetLanguages);
                $shiftTargetLanguages = $this->targetLanguages;

                if (1 === count($shiftTargetLanguages)) {
                    $errorIo->error('Sorry, no target languages detected in your application');
                    return Command::FAILURE;
                    //exit();
                }
                $targetLanguages = implode(',', $shiftTargetLanguages);
            } else {
                $output->writeLn([
                    '',
                    '<fg=red> Error: You must config your targetLanguages languages in </><fg=green>[config/packages/translation.yaml]</>',
                    '',
                ]);
                return Command::INVALID;
                //exit();
            }

            $progressBar->advance(50);
        }
        $progressBar->finish();

        $io->title('<comment> Locale configuration :</comment>');
        $io->text('*<info> Default application language ['.$this->locale.']</info>');
        $io->text('*<info> Target languages ['.$targetLanguages.']</info>');

        $helper = $this->getHelper('question');
        $io->text([
            '<fg=green>[y]</> If you want to generate your files \'messages\' in the default directory [translations/], choose <fg=green>[y]</>.',
            '<fg=green>[n]</> If you want to cancel choose <fg=green>[n]</>.',
            '',
        ]);
        $useDefaultConfig = new ConfirmationQuestion(' <question>Use the default settings for generating files ? [y/n]</question>', false);
        $output->write('');

        if ($helper->ask($input, $output, $useDefaultConfig)) {
            $output->writeLn([
                '',
                '<info> We generate your files ... </info>',
            ]);

            // Generate the default messages.%locale.php
            $command = $this->getApplication()->find('translation:update');
            $arguments = array(
                'command' => 'translation:update',
                '--dump-messages' => true,
                '--force' => true,
                '--output-format' => 'php',
                'locale' => $this->locale,
            );
            $greetInput = new ArrayInput($arguments);
            $command->run($greetInput, $output);

            //Creating new 'messages' in the default [translations/] directory
            $progressBar = new ProgressBar($output, 100);

            //Start translation
            $progressBar->start();
            $i = 0;
            while ($i++ < 2) {
                $this->generator->generate($this->targetLanguages, $this->locale, $this->apiKey);

                $progressBar->advance(10);
            }
            $this->generator->removeDefaultMessage($this->locale);
            $progressBar->finish();
            $output->writeLn('');
            $errorIo->success('your files have been successfully generated in [translations/] directory');
        } else {
            $output->writeLn('');
            $errorIo->success('we stop the execution of process');
        }
        return  Command::SUCCESS;
    }
}
