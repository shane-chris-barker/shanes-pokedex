<?php

namespace App\Controller;

use App\Search\Dto\Request\SearchRequestDto;
use App\Search\Exception\PokeApiSearchException;
use App\Search\Exception\SearchTypeNotFoundException;
use App\Search\Resolver\SearchHandlerStrategyResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class SearchController extends AbstractController
{
    private SearchHandlerStrategyResolver $strategyResolver;

    public function __construct(SearchHandlerStrategyResolver $strategyResolver)
    {
        $this->strategyResolver = $strategyResolver;
    }

    #[Route('/search', name: 'app_search', methods: ['POST'])]
    public function index(#[MapRequestPayload] SearchRequestDto $requestDto): Response
    {
        $searchType = $requestDto->getSearchType();

        try {
            $strategy = $this->strategyResolver->resolve($searchType);
        } catch (SearchTypeNotFoundException $exception) {
            return new JsonResponse(['html' => "<div>Error - $exception</div>"]);
        }

        try {
            $result = $strategy->search($requestDto);
        } catch (PokeApiSearchException) {
            return new JsonResponse(['html' => "<div>We couldn't catch that one....</div>"]);
        }
        $responseHtml = $this->renderView("result/{$searchType}/index.html.twig", [
            'data' => $result,
        ]);

       return new JsonResponse(['html' => $responseHtml]);
    }
}
