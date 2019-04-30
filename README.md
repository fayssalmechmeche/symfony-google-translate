# Symfony 4 | Free Google Translator Bundle
> 5000 words maximum


This bundle will allow you to translate your symfony application instantly and with a single command line in all languages ​​supported by google.

## Installing / Getting started

To use this bundle you would need two symfony4 components.<br/>
```Twig & Translations.``` <br/>
Cause the bundle will looking for  ```{{'the string to translate'|trans}}``` in the ```/Templates``` directory by fetching all files with the ```.twig``` extension.
### Installation with composer

Using  [symfony/flex](https://github.com/symfony/flex).

>1 - Install Twig 

```shell
php composer require twig
```

> 2 - Install Translations

```shell
php composer require translations
```

> Finaly - Install Google Translator Bundle

```shell
php composer require sabrihamda/google-translator-bundle
```

### Initial Configuration
> Templates

To safly start with this bundle, be sure that all the words to be translated are passed to the Translations component like this: <br/>
```{{ 'string to translate'|trans }}```<br/>
>Example: <br/>
![](https://res.cloudinary.com/hamda-ch/image/upload/c_scale,f_auto,q_100,w_1000/v1556654511/GITHUB/GOOGLE-TRANSLATOR/twig-example-1.png)<br/>

> Config

Go to ```./config/packages/translation.yaml``` and add your target languages.<br/>
>Example: <br/>
![](https://res.cloudinary.com/hamda-ch/image/upload/c_scale,f_auto,q_100,w_1000/v1556654511/GITHUB/GOOGLE-TRANSLATOR/translation-example-1.png)<br/>

That's it  :)

### Run the command

Go to your command line and run the command:
```shell
php bin/console google:translate
```
![](https://res.cloudinary.com/hamda-ch/image/upload/c_scale,f_auto,q_100,w_1000/v1556654511/GITHUB/GOOGLE-TRANSLATOR/command-line-example-1.png)<br/>


All the messages files will be generated in the ```./translation``` directory.

## Licensing

>The code in this project is licensed under MIT license.

Copyright (c) 2018 Sabri Hamda

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
