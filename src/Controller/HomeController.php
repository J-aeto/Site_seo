<?php

namespace App\Controller;

use PHPUnit\Framework\Test;
use App\Service\CallApiService;
use Symfony\Component\String\UnicodeString;
use function Symfony\Component\String\u;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\Length;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(CallApiService $callApiService, Request $request): Response
   {
       $text = $request->get('modify_text');
       $text_output = '';
       $textArray = [];
       if ($text) {
        $textparse = preg_replace( "/\r|\n/", "#", $text );
        $mask = ["fastcompare","FastCompare","Fastcompare", "lelynx", "LeLynx", "Lelynx", "LeLynx.fr", "jechange", "JeChange", "jeChange"];
        
        $textparse = u($textparse)->split('#');
        
        foreach ($textparse as $k => $v) {
            if (strlen($v) > 1){
                $vParse = preg_replace( '/<a\shref=\".*\">(.+<\/a>)/', '<a href="">$1', $v );
                $vParse = preg_replace( '/<h2\sid=\".*\">/', '<h2>$1', $vParse );
                $vParse = str_replace($mask, "RapideCompare", $vParse);
                array_push($textArray, $vParse);
            }
        }
        foreach ($textArray as $k => $v) {
            $tag = substr($v, 0, 3);
            if ($tag[0] !== "<h2") {
                $finalText = $v;
                $firstKey = $k;
                break 1;
            }    
        }
        foreach ($textArray as $k => $v) {
            $tag = substr($v, 0, 3);
            if ($k != 0 && $k != $firstKey && $tag[0] !== "<h2") {
                $finalText = $finalText . '#' . $v;
            }    
        }
        $textTrans = $callApiService->getRewriterData($finalText);
        $textTrans = $textTrans['rewrite'];

        $preTextOutput =  u($textTrans)->split('#');
        //$preTextOutput =  u($finalText)->split('#');
        $text_output = $textArray;
        $count = 0;
        $length = count($text_output);
        foreach($preTextOutput as $k => $v){
            $i = 0;
            for ($i=$count; $i < $length; $i++) {
                $tag = substr($text_output[$i], 0, 3);
                if ($tag[0] !== "<h2") {
                    $text_output[$i] = $v;
                    $count = $i + 1;
                    break 1;
                }
            }
        }
       }
       // ADD sécurité sur la sortie formulaire
       
       return $this->render('home/index.html.twig', [
           'controller_name' => 'HomeController',
           'text_output' => $text_output,
           'original_text' => $textArray
       ]);
   }
   // A faire une fonction a part pour l'affichage et la requete api
}

