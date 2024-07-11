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

use Google\Cloud\Core\Exception\GoogleException;
use Google\Cloud\Core\Exception\ServiceException;
use Google\Cloud\Translate\V2\TranslateClient;

class GoogleApiTranslate
{
    /**
     * @throws GoogleException
     * @throws ServiceException
     */
    public function translate($target, $text, $apiKey)
    {
        // Your Google Cloud Platform project ID
        $projectId = $apiKey;

        // Instantiates a client
        $translate = new TranslateClient([
            'projectId' => $projectId,
        ]);

        // The text to translate
        $text = 'Hello, world!';
        // The target language
        $target = 'ru';

        // Translates some text into Russian
        $translation = $translate->translate($text, [
            'target' => $target,
        ]);

        return $translation['text'];
    }
}
