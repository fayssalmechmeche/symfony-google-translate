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

use Symfony\Component\Finder\Finder;

/**
 * Class TemplateParser.
 */
class TemplateParser
{
    /**
     * @var array Path of all files in Templates directory
     */
    private array $templates = [];

    /**
     * @param $generatedKeys
     */
    public function parse($generatedKeys): void
    {
        $templates = $this->parseTemplatesDirectory();

        foreach ($templates as $templatePath) {
            $template = file_get_contents('./templates/'.$templatePath);
            if (preg_match_all('/(?<={{ \')(.*)(?=\'\|trans)/', $template, $matches)) {
                $values = $matches[0];

                $updatedTemplate = str_replace($values, $generatedKeys, $template);

                $this->create($updatedTemplate, $templatePath);
            }
        }
    }

    /**
     * @param $template
     * @param $templatePath
     */
    private function create($template, $templatePath): void
    {
        $myfile = fopen('./templates/'.$templatePath, 'w') or die('Unable to open file!');
        $txt = $template;
        fwrite($myfile, $txt);
        fclose($myfile);
    }

    /**
     * @return array
     */
    public function parseTemplatesDirectory(): array
    {
        $finder = new Finder();
        $finder->files()->in('./templates/');

        foreach ($finder as $file) {
            // dumps the absolute path
            $this->templates[] = $file->getRelativePathname();
        }

        return $this->templates;
    }
}
