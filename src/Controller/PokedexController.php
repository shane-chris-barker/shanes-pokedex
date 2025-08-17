<?php

namespace App\Controller;

use App\Form\SearchFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PokedexController extends AbstractController
{
    #[Route('/', name: 'app_pokedex_index', methods: ['GET'])]
    public function index(): Response
    {
        $form = $this->createForm(SearchFormType::class);
        return $this->render('pokedex/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
