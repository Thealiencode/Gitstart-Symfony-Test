<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ProductController extends AbstractController
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly ValidatorInterface $validator,
        private readonly NormalizerInterface $normalizer,
    ) {
    }

    #[Route('/api/products', name: 'get_products', methods: ['GET'])]
    public function index(): JsonResponse
    {


        $products = $this->productRepository->findAll();
        $productsArray = $this->normalizer->normalize($products, null, ['groups' => 'product:read']);

        return $this->json([
            'status' => 'success',
            'data' => $productsArray,
        ]);
    }

    #[Route('/api/products', name: 'create_product', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {

        /** @var array{name: string, description: string, price: string} $data */
        $data = json_decode($request->getContent(), true);
        $product = new Product();

        $product->setName($data['name']);
        $product->setDescription($data['description']);
        $product->setPrice($data['price']);
        $product->setCreatedAt(new \DateTimeImmutable());

        $errors = $this->validator->validate($product);

        if (count($errors) > 0) {
            $errorsString = $errors;


            return $this->json([
                'status'    => 'error',
                'message'   => "validation Errors",
                'data'      => $errorsString

            ], 522);
        }


        $this->productRepository->save($product);
        // Save the product to the database...
        return $this->json(
            [
                'status' => 'success',
                'message'   => "Product Created Successfully"
            ],
            201
        );
    }


    #[Route('/api/products/{id}', name: 'get_product', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            return $this->json(['status' => 'errpr',  'message' => 'Product not found'], 404);
        }

        return $this->json(['status' => 'success', 'data' => $product]);
    }

    #[Route('/api/products/{id}', name: 'update_product', methods: ['PUT'])]
    public function update(Request $request, int $id): JsonResponse
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            return $this->json(['status' => 'error',  'message' => 'Product not found'], 404);
        }

        /** @var array{name: string, description: string, price: string} $data */
        $data = json_decode($request->getContent(), true);

        $product->setName($data['name']);
        $product->setDescription($data['description']);
        $product->setPrice($data['price']);

        $errors = $this->validator->validate($product);
        if (count($errors) > 0) {
            return $this->json(['status' => 'error', 'data' => $errors], 522);
        }

        $this->productRepository->save($product);

        return $this->json(
            [
                'status' => 'success',
                'message'   => "Product Update Successfully",
                'data'      => $product
            ]
        );
    }

    #[Route('/api/products/{id}', name: 'delete_product', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            return $this->json(['status' => 'error',  'message' => 'Product not found'], 404);
        }

        $this->productRepository->delete($product);


        return $this->json(['status' => 'success', 'message' => 'Product deleted']);
    }
}
