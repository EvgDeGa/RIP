<?php


namespace App\Controller;

use App\Repository\TodoRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TodoSiteController
 * @package App\Controller
 *
 * @Route(path="/todo")
 */
class TodoController
{
    private $todoRepository;

    public function __construct(TodoRepository $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }

    /**
     * @Route("/add", name="add_todo", methods={"POST"})
     */
    public function addTodo(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $text = $data['text'];
        $checked = $data['checked'];

        if (empty($text) || empty($checked) ) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->todoRepository->saveTodo($text, $checked);

        return new JsonResponse(['status' => 'Todo added!'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/get/{id}", name="get_one_todo", methods={"GET"})
     */
    public function getOneTodo($id): JsonResponse
    {
        $todo = $this->todoRepository->findOneBy(['id' => $id]);

        $data = [
            'id' => $todo->getId(),
            'text' => $todo->getText(),
            'checked' => $todo->getChecked()

        ];

        return new JsonResponse(['todo' => $data], Response::HTTP_OK);
    }

    /**
     * @Route("/get-all", name="get_all_todo", methods={"GET"})
     */
    public function getAllTodo(): JsonResponse
    {
        $todos = $this->todoRepository->findAll();
        $data = [];

        foreach ($todos as $todo) {
            $data[] = [
                'id' => $todo->getId(),
                'text' => $todo->getText(),
                'checked' => $todo->getChecked()

            ];
        }

        return new JsonResponse(['todos' => $data], Response::HTTP_OK);
    }

    /**
     * @Route("/update/{id}", name="update_todo", methods={"PUT"})
     */
    public function updateCustomer($id, Request $request): JsonResponse
    {
        $todo = $this->todoRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);

        $this->todoRepository->updateTodo($todo, $data);

        return new JsonResponse(['status' => 'todo updated!']);
    }

    /**
     * @Route("/delete/{id}", name="delete_todo", methods={"DELETE"})
     */
    public function deleteCustomer($id): JsonResponse
    {
        $todo = $this->todoRepository->findOneBy(['id' => $id]);

        $this->todoRepository->removeTodo($todo);

        return new JsonResponse(['status' => 'todo deleted']);
    }
}