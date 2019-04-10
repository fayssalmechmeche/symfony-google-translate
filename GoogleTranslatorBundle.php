<?php
/**
 * Created by Sabri Hamda <sabri@hamda.ch>
 */

namespace GoogleTranslator\GoogleTranslatorBundle;

use GoogleTranslator\GoogleTranslatorBundle\Services\GoogleFreeTranslate;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use GoogleTranslator\GoogleTranslatorBundle\Services\MessagesCreator;
use Symfony\Component\Translation\Translator;

/**
 * Class GoogleTranslatorBundle
 * @package GoogleTranslator\GoogleTranslatorBundle
 */
class GoogleTranslatorBundle extends Bundle
{

    /**
     * Array of all application suported languages
     * @var array
     */
    private $fallbackLocales = [];

    /**
     * Array of values to translate extracted from the messages.%locale%.php
     * @var array
     */
    private $valuesToTranslate = [];

    /**
     * Locale language of the application
     * @var string
     */
    private $localeLanguage;

    private $translatedValues;

    private $finalTranslated;

    private $generatedKeys = [];

    /**
     * @param Translator $translator
     */
    public function generate(Translator $translator)
    {

        $this->fallbackLocales = $translator->getfallbackLocales();
        $this->localeLanguage = $translator->getLocale();
        $this->valuesToTranslate = require('./translations/messages.'.$this->localeLanguage.'.php');
        $this->generateKeys();

        //Loop the fallbackLocales table
        foreach ($this->fallbackLocales as $fallbackLocale) {
            //Loop the valueToTranslate table
            foreach ($this->valuesToTranslate as $valueToTranslate) {
                $cleanedUpValue = str_replace('__', '', $valueToTranslate);
                $googleFreeTranslator = new GoogleFreeTranslate();
                $this->translatedValues [] = $googleFreeTranslator->translate($this->localeLanguage, $fallbackLocale, $cleanedUpValue);

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

    private function generateKeys()
    {
        foreach ($this->valuesToTranslate as $valueToTranslate) {
            $this->generatedKeys[] = $this->generateRandomId(10);
        }
        return $this->generatedKeys;
    }

    /**
     * @param int $length
     * @return string
     */
    private function generateRandomId($length)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomId = '';
        for ($i = 0; $i < $length; $i++) {
            $randomId .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomId;
    }
}
