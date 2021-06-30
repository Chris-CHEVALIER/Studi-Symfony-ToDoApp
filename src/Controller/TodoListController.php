<?php

namespace App\Controller;

use App\Entity\TodoList;
use App\Form\TodoListType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoListController extends AbstractController {
    /**
     * @Route("/create-list", name="create_list")
     */
    public function create(Request $request): Response {
        $todoList = new TodoList();

        $form = $this->createForm(TodoListType::class, $todoList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($todoList);
            $em->flush();
        }

        return $this->render("todo_list/create.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/", name="read_all")
     */
    public function readAll(): Response {
        $repo = $this->getDoctrine()->getRepository(TodoList::class);
        $lists = $repo->findAll();

        return $this->render("todo_list/index.html.twig", [
            "lists" => $lists
        ]);
    }

    /**
     * @Route("/update-list/{id}", name="update_list")
     */
    public function update(TodoList $list, Request $request): Response {
        $form = $this->createForm(TodoListType::class, $list);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->render("todo_list/create.html.twig", [
            "form" => $form->createView(),
            "list" => $list
        ]);
    }
}