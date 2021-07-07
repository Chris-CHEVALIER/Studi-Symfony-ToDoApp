<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\TodoList;
use App\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController {
    /**
     * @Route("/create-task/{id}", name="create_task")
     */
    public function create(Request $request, TodoList $list): Response {
        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setCompleted(0);
            $task->setList($list);
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();
            return $this->redirectToRoute("read_all");
        }

        return $this->render('task/create.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/update-task/{id}", name="update_task")
     */
    public function update(Task $task, Request $request): Response {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute("read_all");
        }

        return $this->render("task/create.html.twig", [
            "form" => $form->createView(),
            "task" => $task
        ]);
    }

    /**
     * @Route("/delete-task/{id}", name="delete_task")
     */
    public function delete(Task $task): Response {
        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();
        return $this->redirectToRoute("read_all");
    }

    /**
     * @Route("/update-task-status/{id}", name="update_task_status")
     */
    public function updateTaskStatus(Task $task): Response {
        $task->setCompleted(!$task->getCompleted());
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute("read_all");
    }
}
