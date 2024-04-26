<?php

namespace App\Controller\Api;

use App\Entity\Ability;
use OpenApi\Attributes as OA;
use App\Repository\AbilityRepository;
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

#[OA\Tag(name: "Ability")]
class AbilityController extends AbstractController
{
    public function __construct(
        private AbilityRepository $abilityRepository,
        private EntityManagerInterface $em,
        private SerializerInterface $serializer
    ) {
        // ...
    }

    #[Route('/api/abilities', name: 'app_api_ability', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Ability::class, groups: ['read']))
        )
    )]
    public function index(
        PaginatorInterface $paginator,
        Request $request
    ): JsonResponse {

        $ability = $this->abilityRepository->getAllAbilitiesQuery();

        $data = $paginator->paginate(
            $ability,
            $request->query->get('page', 1),
            2
        );

        return $this->json([
            'data' => $data,
        ], 200, [], [
            'groups' => ['read']
        ]);
    }

    #[Route('/api/ability/{id}', name: 'app_api_ability_get',  methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new Model(type: Ability::class, groups: ['read'])
    )]
    public function get(?Ability $ability = null): JsonResponse
    {
        if (!$ability) {
            return $this->json([
                'error' => 'Ressource does not exist',
            ], 404);
        }

        return $this->json($ability, 200, [], [
            'groups' => ['read']
        ]);
    }

    #[Route('/api/abilities', name: 'app_api_ability_add',  methods: ['POST'])]
    public function add(
        #[MapRequestPayload('json', ['groups' => ['create']])] Ability $ability
    ): JsonResponse {
        $this->em->persist($ability);
        $this->em->flush();

        return $this->json($ability, 200, [], [
            'groups' => ['read']
        ]);
    }


    #[Route('/api/ability/{id}', name: 'app_api_ability_update',  methods: ['PUT'])]
    #[OA\Put(
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: new Model(
                    type: Ability::class,
                    groups: ['update']
                )
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new Model(type: Ability::class, groups: ['read'])
    )]
    public function update(Ability $ability, Request $request): JsonResponse
    {

        $data = $request->getContent();
        $this->serializer->deserialize($data, Ability::class, 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => $ability,
            'groups' => ['update']
        ]);

        $this->em->flush();

        return $this->json($ability, 200, [], [
            'groups' => ['read'],
        ]);
    }

    #[Route('/api/ability/{id}', name: 'app_api_ability_delete',  methods: ['DELETE'])]
    public function delete(Ability $ability): JsonResponse
    {
        $this->em->remove($ability);
        $this->em->flush();

        return $this->json([
            'message' => 'Ability deleted successfully'
        ], 200);
    }
}
