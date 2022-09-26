<?php

declare(strict_types=1);

namespace App\Controller;

use App\Provider\ProviderAdapter;
use App\Provider\GitHubProvider;
use App\Service\ScoreService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScoreController extends AbstractController
{
    #[Route('/score/github/{term}', name: 'app_score', methods: ['GET'])]
    public function getGithubScore(string $term, ScoreService $scoreService): Response
    {
        // sanitize input

        $provider = new ProviderAdapter(new GitHubProvider());

        try {
            $data   = $scoreService->getScore($term, $provider);
            $status = Response::HTTP_OK;
        } catch(\Exception $e) {
            $data = [
                'message' => $e->getMessage(),
                'code'    => $e->getCode(),
            ];
        }

        return $this->json($data, $status ?? Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
