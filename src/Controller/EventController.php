<?php

namespace App\Controller;

use App\Entity\Event;
use Doctrine\Persistence\ManagerRegistry;
use HttpResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EventController extends AbstractController
{
    private $event;
    function __construct(
        ManagerRegistry $doctrine,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        $this->event = $doctrine->getRepository(Event::class);
        $this->database = $doctrine->getManager();
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    public function index()
    {
        $content = $this->serializer->serialize($this->event->findAll(), 'json', ['groups' => ['event', 'lecture']]);
        return HttpResponse::json($content);
    }


    public function getOne($id)
    {

        try {
            $eventExist = $this->event->find($id);
            if (!isset($eventExist)) {
                throw new \Exception('Registro não encontrado.');
            }
        } catch (\Throwable $th) {
            return HttpResponse::json([
                'message' => $th->getMessage(),
            ], 401);
        }

        $content = $this->serializer->serialize($eventExist, 'json', ['groups' => ['lecture', 'event']]);

        return HttpResponse::json($content);
    }

    public function create(Request $request)
    {

        try {
            $event = $this->serializer->deserialize(
                $request->getContent(),
                Event::class,
                'json'
            );
            $error = $this->validator->validate($event);
            if (count($error) > 0) {
                throw new \Exception('campo status opção incorreta!');
            };
        } catch (\Throwable $th) {
            return HttpResponse::json([
                'message' => $th->getMessage(),
            ], 400);
        }

        try {

            $this->database->persist($event);
            $this->database->flush();
            return HttpResponse::json([
                'message' => "evento criado com sucesso!",
            ]);
        } catch (\Throwable $th) {
            return HttpResponse::json([
                'message' => "ocorreu um erro ao criar o evento!",
            ], 400);
        }
    }

    public function update(Request $request, $id)
    {

        try {
            $eventExist = $this->event->find($id);
            if (!isset($eventExist)) {
                throw new \Exception('Registro não encontrado.');
            }
        } catch (\Throwable $th) {
            return HttpResponse::json([
                'message' => $th->getMessage(),
            ], 401);
        }


        try {
            $event = $this->serializer->deserialize(
                $request->getContent(),
                Event::class,
                'json'
            );
            $eventExist->setTitle($event->getTitle());
            $eventExist->setDescription($event->getDescription());
            $eventExist->setStartDatetime($event->getStartDatetime());
            $eventExist->setFinalDatetime($event->getFinalDatetime());
            $eventExist->setStatus($event->getStatus());
        } catch (\Throwable $th) {
            return HttpResponse::json([
                'message' => "Parametros estão faltando!",
                'error' => $th->getMessage()
            ], 400);
        }

        try {
            $this->database->persist($eventExist);
            $this->database->flush();
            return HttpResponse::json([
                'message' => "evento atualizado com sucesso!"
            ]);
        } catch (\Throwable $th) {
            return HttpResponse::json([
                'message' => "ocorreu um erro ao atualizar o evento!",
                'error' => $th->getMessage()
            ], 400);
        }
    }

    public function delete($id)
    {
        try {
            $eventExist = $this->event->find($id);
            if (!isset($eventExist)) {
                throw new \Exception('Registro não encontrado.');
            }
        } catch (\Throwable $th) {
            return HttpResponse::json([
                'message' => $th->getMessage(),
            ], 401);
        }

        try {
            $this->database->remove($eventExist);
            $this->database->flush();
            return HttpResponse::json([
                'message' => "evento apagado com sucesso!"
            ]);
        } catch (\Throwable $th) {
            return HttpResponse::json([
                'message' => "ocorreu um erro ao apagar o evento!",
                'error' => $th->getMessage()
            ], 400);
        }
    }
}
