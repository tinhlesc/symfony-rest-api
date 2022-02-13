<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\Type\ProductType;
use App\Form\Type\ProductUpdateType;
use App\Repository\ProductRepository;
use App\Service\ProductImageService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Product controller.
 *
 * @Route("/api", name="api_")
 */
class ProductController extends AbstractFOSRestController
{
    /**
     * Lists all product.
     *
     * @Rest\Get("/products")
     */
    public function getProductAction(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        return $this->handleView($this->view($products));
    }

    /**
     * View product.
     *
     * @Rest\Get("/products/{id}")
     */
    public function viewProductAction($id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);
        if ($product) {
            return $this->handleView($this->view($product));
        }

        return $this->handleView($this->view(['error' => 'The product was not found.'], Response::HTTP_BAD_REQUEST));
    }

    /**
     * Create product.
     *
     * @Rest\Post("/products")
     */
    public function postProductAction(Request $request, ProductRepository $productRepository, ProductImageService $productImageService): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->submit($request->request->all());
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $request->files->get('image');
            $productImage = $productImageService->upload($uploadedFile);
            if ($productImage) {
                $product->setImage($productImage);
            }

            $productRepository->save($product);

            return $this->handleView($this->view(null, Response::HTTP_CREATED));
        }

        return $this->handleView($this->view($form->getErrors()));
    }

    /**
     * Put|Patch product.
     *
     * @Rest\Put("/products/{id}")
     * @Rest\Patch("/products/{id}")
     */
    public function putProductAction(Request $request, $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);
        if (!$product) {
            return $this->handleView($this->view(['error' => 'The product was not found.'], Response::HTTP_BAD_REQUEST));
        }

        $form = $this->createForm(ProductUpdateType::class, $product);
        $form->submit($request->request->all(), Request::METHOD_PATCH !== $request->getMethod());
        if ($form->isSubmitted() && $form->isValid()) {
            $productRepository->save($product);

            return $this->handleView($this->view(null, Response::HTTP_NO_CONTENT));
        }

        return $this->handleView($this->view($form->getErrors()));
    }

    /**
     * Delete product.
     *
     * @Rest\Delete("/products/{id}")
     */
    public function deleteProductAction($id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);
        if (!$product) {
            return $this->handleView($this->view(['error' => 'The product was not found.'], Response::HTTP_BAD_REQUEST));
        }

        $productRepository->remove($product);

        return $this->handleView($this->view(null, Response::HTTP_NO_CONTENT));
    }
}
