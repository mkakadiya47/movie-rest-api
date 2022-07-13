<?php

namespace App\Controller;

use App\Entity\Movies;
use App\Repository\MoviesRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class MovieController extends AbstractFOSRestController
{
    /**
     * @Route("/api/v1/movies", name="movie_list", methods={"GET"})
     * @param MoviesRepository $moviesRepository
     * @return Response
     */
    public function movies(MoviesRepository $moviesRepository): Response
    {
        $movies = $moviesRepository->findByOwner($this->getUser());
        $view = $this->view($movies, Response::HTTP_OK);
        return $this->handleView($view);
    }

    /**
     * @Route("/api/v1/movie", name="create_movie", methods={"POST"})
     * @ParamConverter("movie", converter="fos_rest.request_body")
     * @param Movies $movie
     * @param ConstraintViolationListInterface $validationErrors
     * @return Response
     */
    public function createMovie(Movies $movie, ConstraintViolationListInterface $validationErrors)
    {
        $dm = $this->getDoctrine()->getManager();

        if(count($validationErrors) > 0){
            $view = $this->view($validationErrors, Response::HTTP_BAD_REQUEST);
            return $this->handleView($view);
        }

        $movie->setOwner($this->getUser());
        $dm->persist($movie);
        $dm->flush();

        $view = $this->view($movie, Response::HTTP_OK);
        return $this->handleView($view);
    }

    /**
     * @Route("/api/v1/movie/{movie}", name="get_movie_by_id", methods={"GET"})
     * @param Movies $movie
     * @return Response
     */
    public function getMovieById(Movies $movie)
    {
        if($movie->getOwner()->getId() != $this->getUser()->getId()){
            $view = $this->view(['error' => 'Try to access invalid data'], Response::HTTP_BAD_REQUEST);
            return $this->handleView($view);
        }
        $view = $this->view($movie, Response::HTTP_OK);
        return $this->handleView($view);
    }
}