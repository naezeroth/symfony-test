<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/book")
 */
class BookController extends AbstractController
{
    /**
     * @Route("/", name="book_index", methods={"GET"})
     */
    public function index(BookRepository $bookRepository): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => $bookRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="book_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('book_index');
        }

        return $this->render('book/new.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="book_show", methods={"GET"})
     */
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="book_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Book $book): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('book_index', [
                'id' => $book->getId(),
            ]);
        }

        return $this->render('book/edit.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="book_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Book $book): Response
    {
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($book);
            $entityManager->flush();
        }

        return $this->redirectToRoute('book_index');
    }
        
}


// <?php
// // src/Controller/LuckyController.php
// namespace App\Controller;

// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\RedirectResponse;

// class BookController extends AbstractController
// {
//     /**
//      * @Route("/books")
//      */
//     public function number()
//     {
//         $number = random_int(0, 100);

//         // return new Response(
//         //     '<html><body>Lucky number: '.$number.'</body></html>'
//         // );
//         return $this->render('books/book.html.twig', [
//             'number' => $number,
//         ]);
//     }

//     /**
//      * @Route("/books/{page}", name="book_list", requirements={"page"="\d+"})
//      */
//     public function list($page)
//     {
//         return new Response(
//                 '<html><body>This is where the list of books can be found</body></html>'
//         );
//     }

//     /**
//      * @Route("/books/{slug}", name="book_show")
//      */
//     public function show($slug)
//     {
//         return new Response(
//             '<html><body>This is where a specific book can be found</body></html>'
//         );
//     }


//     /**
//      * @Route("/books/add/{", name="book_show")
//      */
//     public function show($slug)
//     {
//         return new Response(
//             '<html><body>This is where a specific book can be found</body></html>'
//         );
//     }


//     /**
//      * @Route("/books")
//      */
//     public function redirection()
//     {
//         // redirects to the "homepage" route
//         return $this->redirectToRoute('homepage');

//         // redirectToRoute is a shortcut for:
//         // return new RedirectResponse($this->generateUrl('homepage'));

//         // does a permanent - 301 redirect
//         return $this->redirectToRoute('homepage', [], 301);

//         // redirect to a route with parameters
//         return $this->redirectToRoute('app_lucky_number', ['max' => 10]);

//         // redirects to a route and maintains the original query string parameters
//         return $this->redirectToRoute('blog_show', $request->query->all());

//         // redirects externally
//         return $this->redirect('http://symfony.com/doc');
//     }


// }


