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
 * Class MessagesCreator.
 */
class MessagesCreator
{
    /**
     * @param array  $translated
     * @param string $lang
     * @param $locale
     * @param $generatedKeys
     */
    public function makeXLF($translated = [], string $lang, $locale, $generatedKeys): void
    {
        $myfile = fopen('./translations/messages.'.$lang.'.xlf', 'w') or die('Unable to open file!');
        $txt = "<?xml version=\"1.0\"?>\n";
        $txt .= "<xliff version=\"1.2\" xmlns=\"urn:oasis:names:tc:xliff:document:1.2\">\n";
        $txt .= '    <file source-language="'.$locale."\" datatype=\"plaintext\" original=\"file.ext\">\n";
        $txt .= "        <body>\n";
        foreach ($translated as $key => $value) {
            $txt .= "            <trans-unit id=\"$key\">\n";
            $txt .= "                <source>$key</source>\n";
            $txt .= '                <target>'.$value."</target>\n";
            $txt .= "            </trans-unit>\n";
        }
        $txt .= "        </body>\n";
        $txt .= "    </file>\n";
        $txt .= "</xliff>\n";
        fwrite($myfile, $txt);
        fclose($myfile);

        $templateParser = new TemplateParser();
        $templateParser->parse($generatedKeys);
    }
}
