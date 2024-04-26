<?php

namespace App\Controller\Api;

use App\Entity\Type;
use OpenApi\Attributes as OA;
use App\Repository\TypeRepository;
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

#[OA\Tag(name: "Type")]
class TypeController extends AbstractController
{
    public function __construct(
        private TypeRepository $typeRepository,
        private EntityManagerInterface $em,
        private SerializerInterface $serializer
    ) {
        // ...
    }

    #[Route('/api/types', name: 'app_api_type', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Type::class, groups: ['read']))
        )
    )]
    public function index(
        PaginatorInterface $paginator,
        Request $request
    ): JsonResponse {

        $type = $this->typeRepository->getAllTypesQuery();

        $data = $paginator->paginate(
            $type,
            $request->query->get('page', 1),
            2
        );

        return $this->json([
            'data' => $data,
        ], 200, [], [
            'groups' => ['read']
        ]);
    }

    #[Route('/api/type/{id}', name: 'app_api_type_get',  methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new Model(type: Type::class, groups: ['read'])
    )]
    public function get(?Type $type = null): JsonResponse
    {
        if (!$type) {
            return $this->json([
                'error' => 'Ressource does not exist',
            ], 404);
        }

        return $this->json($type, 200, [], [
            'groups' => ['read']
        ]);
    }

    #[Route('/api/types', name: 'app_api_type_add',  methods: ['POST'])]
    public function add(
        #[MapRequestPayload('json', ['groups' => ['create']])] Type $type
    ): JsonResponse {
        $this->em->persist($type);
        $this->em->flush();

        return $this->json($type, 200, [], [
            'groups' => ['read']
        ]);
    }


    #[Route('/api/type/{id}', name: 'app_api_type_update',  methods: ['PUT'])]
    #[OA\Put(
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: new Model(
                    type: Type::class,
                    groups: ['update']
                )
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new Model(type: Type::class, groups: ['read'])
    )]
    public function update(Type $type, Request $request): JsonResponse
    {

        $data = $request->getContent();
        $this->serializer->deserialize($data, Type::class, 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => $type,
            'groups' => ['update']
        ]);

        $this->em->flush();

        return $this->json($type, 200, [], [
            'groups' => ['read'],
        ]);
    }

    #[Route('/api/type/{id}', name: 'app_api_type_delete',  methods: ['DELETE'])]
    public function delete(Type $type): JsonResponse
    {
        $this->em->remove($type);
        $this->em->flush();

        return $this->json([
            'message' => 'Type deleted successfully'
        ], 200);
    }
}
