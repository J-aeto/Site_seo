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
        $textparse = u($textparse)->split('#');
        
        foreach ($textparse as $k => $v) {
            if (strlen($v) > 1){
                array_push($textArray, $v);
            }
        }
        $finalText = $textArray[0];
        foreach ($textArray as $k => $v) {
            if ($k != 0) {
                $finalText = $finalText . '#' . $v;
            }    
        }
        //$textTrans = $callApiService->getRewriterData($finalText);
        //$textTrans = $textTrans['rewrite'];

        //$text_output =  u($textTrans)->split('#');
        $text_output = [];
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
