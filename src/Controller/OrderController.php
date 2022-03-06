<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Form\Type\OrderItemType;
use App\Repository\OrderItemRepository;
use App\Repository\OrderRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Product controller.
 *
 * @Route("/api", name="api_")
 */
class OrderController extends AbstractFOSRestController
{
    /**
     * View cart.
     *
     * @Rest\Get("/orders/{id}")
     */
    public function viewCartAction($id, OrderItemRepository $orderItemRepository): Response
    {
        $orderItem = $orderItemRepository->find($id);
        if ($orderItem) {
            return $this->handleView($this->view($orderItem));
        }

        return $this->handleView($this->view(['error' => 'The order was not found.'], Response::HTTP_BAD_REQUEST));
    }

    /**
     * Create cart.
     *
     * @Rest\Post("/orders")
     */
    public function postOrderAction(Request $request, OrderRepository $orderRepository, OrderItemRepository $orderItemRepository): Response
    {
        $orderItem = new OrderItem();
        $form = $this->createForm(OrderItemType::class, $orderItem);
        $form->submit($request->request->all());
        if ($form->isSubmitted() && $form->isValid()) {
            $order = new Order();
            $orderRepository->save($order);

            $orderItem->setOrderRef($order);
            $orderItemRepository->save($orderItem);

            return $this->handleView($this->view(null, Response::HTTP_CREATED));
        }

        return $this->handleView($this->view($form->getErrors()));
    }
}
