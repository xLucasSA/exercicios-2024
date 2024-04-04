<?php

namespace Chuva\Php\WebScrapping;

use Chuva\Php\WebScrapping\Entity\Paper;
use Chuva\Php\WebScrapping\Entity\Person;

/**
 * Does the scrapping of a webpage.
 */
class Scrapper {

  /**
   * Loads paper information from the HTML and returns the array with the data.
   */
  public function scrap(\DOMDocument $dom): array {
    $element = $dom->getElementById('hits-container')->parentNode;
    $result = [];
    
    foreach ($element->childNodes as $child) {
      $id; $title; $type; $authors = [];

      if ($child->nodeName === 'a') {
        $link = $child->getAttribute('href');
        $linkParts = explode('/', $link);
        $id = (int) end($linkParts);
        
        foreach ($child->childNodes as $elementHTML) {
          $htmlClass = $elementHTML->getAttribute('class');
          
          if (str_contains($htmlClass, 'title')){
            $title = $elementHTML->nodeValue;
          }
          
          elseif (str_contains($htmlClass, 'authors')) {
            
            foreach ($elementHTML->childNodes as $author) {  
              if ($author instanceof \DOMElement && $author->getAttribute('title')) {
                $name = trim($author->nodeValue);
                $name = str_replace(';', '', $name);
                $institution = $author->getAttribute('title');
                
                $authors[] = new Person($name, $institution);
              }
            }
          }

          else {
            $type = $elementHTML->firstChild->nodeValue;
          }
        }

        $result[] = new Paper($id, $title, $type, $authors);
      }
    }
    return $result;
  }
}