<?php
/**
 * This file is part of the Short Hint | Google Translator project.
 *
 * (c) Sabri Hamda <sabri@hamda.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ShortHint\GoogleTranslatorBundle\Utils;

/**
 * Class Generator.
 */
class Generator
{
    /**
     * @var
     */
    private string $apiKey;

    /**
     * Array of all application supported languages.
     *
     * @var array
     */
    private array $fallbackLocales = [];

    /**
     * Array of values to translate extracted from the messages.%locale%.php.
     *
     * @var array
     */
    private $valuesToTranslate = [];

    /**
     * Locale language of the application.
     *
     * @var string
     */
    private string $localeLanguage;

    /**
     * @var array
     */
    private array $translatedValues = [];

    /**
     * @var array
     */
    private array $finalTranslated;

    /**
     * @var array
     */
    private array $generatedKeys = [];

    /**
     * @param $fallbackLocales
     * @param $locale
     */
    public function generate($fallbackLocales, $locale, $apiKey): void
    {
        $this->fallbackLocales = $fallbackLocales;
        $this->localeLanguage = $locale;
        $this->apiKey = $apiKey;
        $this->valuesToTranslate = require './translations/messages.'.$this->localeLanguage.'.php';
        $this->generateKeys();

        //Loop the fallbackLocales table
        foreach ($this->fallbackLocales as $fallbackLocale) {
            //Loop the valueToTranslate table
            foreach ($this->valuesToTranslate as $valueToTranslate) {
                $cleanedUpValue = str_replace('__', '', $valueToTranslate);
                if (\is_null($this->apiKey)) {
                    $googleFreeTranslator = new GoogleFreeTranslate();
                    $this->translatedValues[] = $googleFreeTranslator->translate($this->localeLanguage, $fallbackLocale, $cleanedUpValue);
                } else {
                    $googleApiTranslator = new GoogleApiTranslate();
                    $this->translatedValues[] = $googleApiTranslator->translate($fallbackLocale, $cleanedUpValue, $this->apiKey);
                }
            }

            //Combine the 2 arrays to get one array with 'generatedKeys' => 'translatedValue'
            $this->finalTranslated = array_combine($this->generatedKeys, $this->translatedValues);

            //Create the final file
            $messagesCreator = new MessagesCreator();
            $messagesCreator->makeXLF($this->finalTranslated, $fallbackLocale, $this->localeLanguage, $this->generatedKeys);
            unset($this->translatedValues);
        }

        unset($this->generatedKeys);
    }

    /**
     * Create table with #ID's.
     *
     * @return void
     */
    private function generateKeys(): void
    {
        foreach ($this->valuesToTranslate as $valueToTranslate) {
            $this->generatedKeys[] = 'SHG-'.uniqid();
        }
    }

    /**
     * Delete messages.locale.php file.
     *
     * @param $localeLanguage
     */
    public function removeDefaultMessage($localeLanguage): void
    {
        $defaultMessage = './translations/messages.'.$localeLanguage.'.php';
        $myFileLink = fopen($defaultMessage, 'w') or die("can't open file");
        fclose($myFileLink);
        $defaultMessage = './translations/messages.'.$localeLanguage.'.php';
        unlink($defaultMessage) or die("Couldn't delete file");
    }
}
