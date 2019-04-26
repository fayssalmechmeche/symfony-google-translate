<?php
/**
 * This file is part of the Symfony translation project.
 *
 * (c) Sabri Hamda <sabri@hamda.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ShortHint\GoogleTranslatorBundle\Utils;

use Google\Cloud\Translate\TranslateClient;

class GoogleApiTranslate
{
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
