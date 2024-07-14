<?php

namespace App\Controller;


use App\Entity\Blog;
use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\RateLimiter\RateLimiterFactory;



/**
* @Route("/api/blogs", name="blog_api")
*/
class BlogController extends AbstractController
{
   /**
    * @Route("/", name="index", methods={"GET"})
    */
   public function index(Request $request,BlogRepository $blogRepository, SerializerInterface $serializer,RateLimiterFactory $authenticatedApiLimiter): Response
   {
            // create a limiter based on a unique identifier of the client
        // (e.g. the client's IP address, a username/email, an API key, etc.)
        $limiter = $authenticatedApiLimiter->create($request->getClientIp());

        // the argument of consume() is the number of tokens to consume
        // and returns an object of type Limit
        if (false === $limiter->consume(1)->isAccepted()) {
            throw new TooManyRequestsHttpException();
        }
       $blogs = $blogRepository->findAll();
       $data = $serializer->serialize($blogs, 'json');

       return new Response($data, 200, ['Content-Type' => 'application/json']);
   }


   /**
    * @Route("/{id}", name="show", methods={"GET"})
    */
   public function show(Blog $blog, SerializerInterface $serializer): Response
   {
       $data = $serializer->serialize($blog, 'json');
       return new Response($data, 200, ['Content-Type' => 'application/json']);
   }


   /**
    * @Route("/", name="create", methods={"POST"})
    */
   public function create(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
   {
       $requestData = $request->getContent();

       $blog = $serializer->deserialize($requestData, Blog::class, 'json');
       
       //Blog Name is required
       if (!$blog->getName()) {
           return new JsonResponse(['error' => 'Missing required fields'], 400);
       }

       //Set Defaults
       $blog->setCreatedAt(new \DateTime());
       $blog->setCreatedBy(1); //Can be extend to set it from session
       $blog->setDeleteFlag(0);

       $entityManager->persist($blog);
       $entityManager->flush();

       $data = $serializer->serialize($blog, 'json');

       return new JsonResponse(['message' => 'Blog post created!', 'blog' => json_decode($data)], 201);
   }


   /**
    * @Route("/{id}", name="update", methods={"PUT"})
    */
   public function update(Blog $blog, Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
   {
       $requestData = $request->getContent();
       $updatedBlog = $serializer->deserialize($requestData, Blog::class, 'json');

       $blog->setName($updatedBlog->getName());
       $blog->setDescription($updatedBlog->getDescription());

       $entityManager->flush();

       return new Response('Blog Post Updated!', 200);
   }


   /**
    * @Route("/{id}", name="delete", methods={"DELETE"})
    */
   public function delete(Blog $blog, EntityManagerInterface $entityManager): Response
   {
       $entityManager->remove($blog);
       $entityManager->flush();

       return new Response('Blog deleted!', 200);
   }


   /**
    * @Route("/search/{id}", name="search_by_id", methods={"GET"})
    */
   public function findById(BlogRepository $blogRepository, int $id, SerializerInterface $serializer): Response
   {
    if (empty($id) || gettype($id) != 'integer') {
        return new JsonResponse(['error' => 'Parameter required'], 400);
    }
      
       $blog = $blogRepository->find($id);

       if (!$blog) {
           return new JsonResponse(['error' => 'Blog post not found'], 404);
       }

       $data = $serializer->serialize($blog, 'json');

       return new Response($data, 200, ['Content-Type' => 'application/json']);
   }


   /**
    * @Route("/search/description/{description}", name="search_by_description", methods={"GET"})
    */
   public function findByDescription(BlogRepository $blogRepository, string $description, SerializerInterface $serializer): Response
   {
       $blogs = $blogRepository->findOneBydescription($description);
 

       if (!$blogs) {
           return new JsonResponse(['error' => 'Blog Posts not found'], 404);
       }

       $data = $serializer->serialize($blogs, 'json');

       return new Response($data, 200, ['Content-Type' => 'application/json']);
   }


}