<?php
/**
 * Created by Sabri Hamda <sabri@hamda.ch>
 */

namespace GoogleTranslator\GoogleTranslatorBundle\Services;


use Symfony\Component\Finder\Finder;

/**
 * Class TemplateParser
 * @package GoogleTranslator\GoogleTranslatorBundle\Services
 */
class TemplateParser
{

    /**
     * @param $generatedKeys
     */
    public function parse($generatedKeys)
    {
        $templates = $this->parseTemplatesDirectory();

        foreach ($templates as $templatePath) {

            $template = file_get_contents('./templates/' . $templatePath);
            if(preg_match_all('/(?<={{ \')(.*)(?=\'\|trans)/', $template, $matches)){
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
        $myfile = fopen("./templates/".$templatePath, "w") or die("Unable to open file!");
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
            $templates[] = $file->getRelativePathname();
        }

        return $templates;
    }


}
