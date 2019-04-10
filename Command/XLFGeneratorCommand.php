<?php
/**
 * Created by Sabri Hamda <sabri@hamda.ch>
 */

namespace App\GoogleTranslator\GoogleTranslatorBundle\Command;

use App\GoogleTranslator\GoogleTranslatorBundle\GoogleTranslatorBundle;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class XLFGeneratorCommand
 * @package App\GoogleTranslator\GoogleTranslatorBundle\Command
 */
class XLFGeneratorCommand extends Command
{

    /**
     * @var GoogleTranslatorBundle
     */
    private $googleTranslator;

    private $translator;

    private $locale;

    private $fallbackLocales = [];

    /**
     * XLFGeneratorCommand constructor.
     * @param GoogleTranslatorBundle $googleTranslator
     * @param TranslatorInterface $translator
     */
    public function __construct(GoogleTranslatorBundle $googleTranslator, TranslatorInterface $translator)
    {
        $this->googleTranslator = $googleTranslator;
        $this->translator = $translator;

        parent::__construct();
    }


    /**
     *
     */
    protected function configure()
    {
        $this->setName('google:free-translate')
            ->setDescription('Translate and generate translation messages');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $errorIo = $io->getErrorStyle();

        $output->writeln([
            '',
            ' <fg=blue>SH</> <fg=blue>G</><fg=red>o</><fg=yellow>o</><fg=blue>g</><fg=green>l</><fg=red>e</> <fg=blue>Translator</>',
            ' <fg=blue>====================</>',
            ' // Checking your configuration...',
            ''
        ]);

        //Check application locales & fallbackLocales
        $progressBar = new ProgressBar($output, 100);
        $progressBar->start();
        $i = 0;
        while ($i++ < 2) {

            sleep(1);
            if (!empty($this->translator->getLocale())) {
                $this->locale = $this->translator->getLocale();
            } else {

                $errorIo->error('You must config your locale application languege in [config/services.yaml]');

                exit();
            }

            if (!empty($this->translator->getfallbackLocales())) {
                $this->fallbackLocales[] = $this->translator->getfallbackLocales();
                $shiftFallbackLocales = array_shift($this->fallbackLocales);
                if (count($shiftFallbackLocales) === 1) {
                    $errorIo->error('Sorry, no target languages detected in your application');
                    exit();
                }
                $targetLanguages = implode(",", $shiftFallbackLocales);

            } else {
                $output->writeLn([
                    '',
                    '<fg=red> Error: You must config your fallbackLocales langueges in </><fg=green>[config/packages/translation.yaml]</>',
                    ''
                ]);
                exit();
            }


            $progressBar->advance(50);

        }
        $progressBar->finish();

        $io->title('<comment> Locale configuration :</comment>');
        $io->text('*<info> Default application language [' . $this->locale . ']</info>');
        $io->text('*<info> Target languages [' . $targetLanguages . ']</info>');


        $helper = $this->getHelper('question');
        $io->text([
            '<fg=green>[y]</> If you want to generate your files \'messages\' in the default directory [translations/], choose <fg=green>[y]</>.',
            '<fg=green>[n]</> If you want to generate your files \'messages\' in another directory choose <fg=green>[n]</>.',
            ''
        ]);
        $useDefaultConfig = new ConfirmationQuestion(' <question>Use the default settings for generating files ? [y/n]</question>', false);
        $output->write('');

        if ($helper->ask($input, $output, $useDefaultConfig)) {
            $output->writeLn([
                '',
                '<info> We generate your files ... </info>'
            ]);

            // Generate the default messages.%locale.php
            $command = $this->getApplication()->find('translation:update');
            $arguments = array(
                'command' => 'translation:update',
                '--dump-messages' => true,
                '--force' => true,
                '--output-format' => 'php',
                'locale' => $this->locale
            );
            $greetInput = new ArrayInput($arguments);
            $command->run($greetInput, $output);

            //Creating new 'messages' in the default [translations/] directory
            $progressBar = new ProgressBar($output, 100);

            //Start translation
            $progressBar->start();
            $i = 0;
            while ($i++ < 2) {
                $this->googleTranslator->generate($this->translator);

                $progressBar->advance(10);
            }
            $progressBar->finish();
            $output->writeLn('');
            $errorIo->success('your files have been successfully generated in [translations/] directory');
        } else {
            $output->writeLn('');
            $errorIo->error('we stop the execution of process');
        }

    }

}