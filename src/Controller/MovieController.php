<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\Type\MovieType;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Movie controller.
 *
 * @Route("/api", name="api_")
 */
class MovieController extends AbstractFOSRestController
{
    /**
     * Lists all Movies.
     *
     * @Rest\Get("/movies")
     */
    public function getMovieAction(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Movie::class);
        $movies = $repository->findAll();

        return $this->handleView($this->view($movies));
    }

    /**
     * View movie.
     *
     * @Rest\Get("/movies/{id}")
     */
    public function viewMovieAction($id): Response
    {
        $repository = $this->getDoctrine()->getRepository(Movie::class);
        $movie = $repository->find($id);
        if ($movie) {
            return $this->handleView($this->view($movie));
        }

        return $this->handleView($this->view(['error' => 'The movie was not found.'], Response::HTTP_BAD_REQUEST));
    }

    /**
     * Create Movie.
     *
     * @Rest\Post("/movies")
     */
    public function postMovieAction(Request $request): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($movie);
            $em->flush();

            return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
        }

        return $this->handleView($this->view($form->getErrors()));
    }

    /**
     * Put|Patch Movie.
     *
     * @Rest\Put("/movies/{id}")
     */
    public function putMovieAction(Request $request, $id): Response
    {
        $repository = $this->getDoctrine()->getRepository(Movie::class);
        $movie = $repository->find($id);
        if (!$movie) {
            return $this->handleView($this->view(['error' => 'The movie was not found.'], Response::HTTP_BAD_REQUEST));
        }

        $form = $this->createForm(MovieType::class, $movie);
        $form->submit($request->request->all(), Request::METHOD_PATCH !== $request->getMethod());
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($movie);
            $em->flush();

            return $this->handleView($this->view([], Response::HTTP_NO_CONTENT));
        }

        return $this->handleView($this->view($form->getErrors()));
    }

    /**
     * Delete Movie.
     *
     * @Rest\Delete("/movies/{id}")
     */
    public function deleteMovieAction($id): Response
    {
        $repository = $this->getDoctrine()->getRepository(Movie::class);
        $movie = $repository->find($id);
        if (!$movie) {
            return $this->handleView($this->view(['error' => 'The movie was not found.'], Response::HTTP_BAD_REQUEST));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($movie);
        $em->flush();

        return $this->handleView($this->view([], Response::HTTP_NO_CONTENT));
    }
}
