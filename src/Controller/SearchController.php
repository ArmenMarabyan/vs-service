<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SearchController extends AbstractController
{

    public function __construct(private HttpClientInterface $client)
    {
    }

    #[Route('/search', name: 'app_search')]
    public function index(Request $request): Response
    {

        $term = $request->get('q');

        $hhApi = $this->getParameter('hh_api');
        $hhVacanciesEndpoint = $hhApi . '/vacancies';
        $params = [
            'text' => $term,
            //
        ];
        $hhVacanciesEndpoint = $hhVacanciesEndpoint . '?' . http_build_query($params);

        $response = $this->client->request(
            'GET',
            $hhVacanciesEndpoint
        );

        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        $contentType = $response->getHeaders()['content-type'][0];
        // $contentType = 'application/json'
        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]
//dd($content);


        return $this->render('search/index.html.twig', [
            'items' => $content,
            'term' => $term
        ]);
    }
}
