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

use Symfony\Component\Finder\Finder;

/**
 * Class TemplateParser.
 */
class TemplateParser
{
    /**
     * @var array Path of all files in Templates directory
     */
    private $templates = [];

    /**
     * @param $generatedKeys
     */
    public function parse($generatedKeys)
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
    private function create($template, $templatePath)
    {
        $myfile = fopen('./templates/'.$templatePath, 'w') or die('Unable to open file!');
        $txt = $template;
        fwrite($myfile, $txt);
        fclose($myfile);
    }

    /**
     * @return array
     */
    public function parseTemplatesDirectory()
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
