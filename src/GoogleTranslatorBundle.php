<?php
/**
 * This file is part of the Short Hint | Google Translator project.
 *
 * (c) Sabri Hamda <sabri@hamda.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ShortHint\GoogleTranslatorBundle;

use ShortHint\GoogleTranslatorBundle\DependencyInjection\GoogleTranslatorExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

class GoogleTranslatorBundle extends Bundle
{
    public function getContainerExtension() : ?ExtensionInterface
    {
        if (null === $this->extension) {
            $this->extension = new GoogleTranslatorExtension();
        }

        return $this->extension;
    }
}
