<?php

namespace App\Controller\Api;

use App\Entity\Species;
use OpenApi\Attributes as OA;
use App\Repository\SpeciesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[OA\Tag(name: "Species")]
class SpeciesController extends AbstractController
{
    public function __construct(
        private SpeciesRepository $speciesRepository,
        private EntityManagerInterface $em,
        private SerializerInterface $serializer
    ) {
        // ...
    }

    #[Route('/api/species', name: 'app_api_species', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Species::class, groups: ['read']))
        )
    )]
    public function index(
        PaginatorInterface $paginator,
        Request $request
    ): JsonResponse {

        $species = $this->speciesRepository->getAllSpeciesQuery();

        $data = $paginator->paginate(
            $species,
            $request->query->get('page', 1),
            2
        );

        return $this->json([
            'data' => $data,
        ], 200, [], [
            'groups' => ['read']
        ]);
    }

    #[Route('/api/species/{id}', name: 'app_api_species_get',  methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new Model(type: Species::class, groups: ['read'])
    )]
    public function get(?Species $species = null): JsonResponse
    {
        if (!$species) {
            return $this->json([
                'error' => 'Ressource does not exist',
            ], 404);
        }

        return $this->json($species, 200, [], [
            'groups' => ['read']
        ]);
    }

    #[Route('/api/species', name: 'app_api_species_add',  methods: ['POST'])]
    public function add(
        #[MapRequestPayload('json', ['groups' => ['create']])] Species $species
    ): JsonResponse {
        $this->em->persist($species);
        $this->em->flush();

        return $this->json($species, 200, [], [
            'groups' => ['read']
        ]);
    }


    #[Route('/api/species/{id}', name: 'app_api_species_update',  methods: ['PUT'])]
    #[OA\Put(
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: new Model(
                    type: Species::class,
                    groups: ['update']
                )
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new Model(type: Species::class, groups: ['read'])
    )]
    public function update(Species $species, Request $request): JsonResponse
    {

        $data = $request->getContent();
        $this->serializer->deserialize($data, Species::class, 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => $species,
            'groups' => ['update']
        ]);

        $this->em->flush();

        return $this->json($species, 200, [], [
            'groups' => ['read'],
        ]);
    }

    #[Route('/api/species/{id}', name: 'app_api_species_delete',  methods: ['DELETE'])]
    public function delete(Species $species): JsonResponse
    {
        $this->em->remove($species);
        $this->em->flush();

        return $this->json([
            'message' => 'Species deleted successfully'
        ], 200);
    }
}
