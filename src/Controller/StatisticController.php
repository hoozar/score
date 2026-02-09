<?php

namespace App\Controller;

use App\Entity\Statistic;
use App\Repository\StatisticRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;
use Throwable;

final class StatisticController extends AbstractController
{
    #[Route('/statistics', name: 'app_stats', methods: ['GET'])]
    public function index(Request $request, StatisticRepository $repository): JsonResponse
    {
        try {
            $matchId = trim($request->query->get('match_id') ?? '');
            $teamId = trim($request->query->get('team_id') ?? '');

            $validator = Validation::createValidator();
            $violations = $validator->validate($matchId, new NotBlank());
            if (count($violations) > 0) {
                throw new \InvalidArgumentException('match_id is required');
            }

            $records = $repository->findByCriteria(['match_id' => $matchId, 'team_id' => $teamId]);
            $responseData = [
                'match_id' => $matchId,
                'statistics' => $this->buildStatisticData($records),
            ];
            if (!empty($teamId)) {
                $responseData['team_id'] = $teamId;
            }

            return $this->json($responseData);
        } catch (Throwable $e) {
            return $this->json(
                ['error' => $e->getMessage()],
                500
            );
        }
    }

    private function buildStatisticData(array $records): array
    {
        $teamData = [];
        /** @var Statistic $record */
        foreach ($records as $record) {
            if (false === isset($teamData[$record->getTeamId()])) {
                $teamData[$record->getTeamId()] = [];
            }

            $teamData[$record->getTeamId()][$record->getType()] = $record->getCount();
        }

        return $teamData;
    }
}
