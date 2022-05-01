<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Lecture;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use HttpResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class LectureController extends AbstractController
{
    private $lecture;
    function __construct(
        ManagerRegistry $doctrine,
        SerializerInterface $serializer
    ) {
        $this->lecture = $doctrine->getRepository(Lecture::class);
        $this->event = $doctrine->getRepository(Event::class);
        $this->database = $doctrine->getManager();
        $this->serializer = $serializer;
    }

    public function index()
    {
        $content = $this->serializer->serialize($this->lecture->findAll(), 'json', ['groups' => ['lecture', 'event']]);

        return HttpResponse::json($content);
    }

    public function getOne($id)
    {

        try {
            $lecture = $this->lecture->find($id);
            if (!isset($lecture)) {
                throw new \Exception('Registro não encontrado.');
            }
        } catch (\Throwable $th) {
            return HttpResponse::json([
                'message' => $th->getMessage(),
            ], 401);
        }

        $content = $this->serializer->serialize($lecture, 'json', ['groups' => ['lecture', 'event']]);

        return HttpResponse::json($content);
    }

    public function create(Request $request)
    {
        $content = json_decode($request->getContent());

        try {
            if (!$event = $this->event->find($content->event_id)) {
                throw new \Exception('Registro não encontrado.');
            }
        } catch (\Throwable $th) {
            return HttpResponse::json([
                'message' => $th->getMessage(),
            ], 401);
        }

        try {
            $lecture = new Lecture();
            $lecture->setTitle($content->title);
            $lecture->setDescription($content->description);
            $lecture->setDate(new \DateTime($content->date));
            $lecture->setStartHour(new \DateTime($content->start_hour));
            $lecture->setFinalHour(new \DateTime($content->final_hour));
            $lecture->setSpeaker($content->speaker);
            $lecture->setEvent($event);

            $this->database->persist($lecture);
            $this->database->flush();
            return HttpResponse::json([
                'message' => "palestra criada com sucesso!",
            ]);
        } catch (\Throwable $th) {
            return HttpResponse::json([
                'message' => "ocorreu um erro ao criar a palestra!",
                "error" => $th->getMessage()
            ], 400);
        }
    }

    public function update(Request $request, $id)
    {

        $content = json_decode($request->getContent());
        try {
            if (!$event = $this->event->find($content->event_id)) {
                throw new \Exception('Evento não encontrado.');
            }
        } catch (\Throwable $th) {
            return HttpResponse::json([
                'message' => $th->getMessage(),
            ], 401);
        }

        try {
            if (!$lectureExists = $this->lecture->find($id)) {
                throw new \Exception('Palestra não encontrada.');
            }
        } catch (\Throwable $th) {
            return HttpResponse::json([
                'message' => $th->getMessage(),
            ], 401);
        }


        try {

            $lectureExists->setTitle($content->title);
            $lectureExists->setDescription($content->description);
            $lectureExists->setDate(new DateTime($content->date));
            $lectureExists->setStartHour(new DateTime($content->start_hour));
            $lectureExists->setFinalHour(new DateTime($content->final_hour));
            $lectureExists->setSpeaker($content->speaker);
            $lectureExists->setEvent($event);

            $this->database->persist($lectureExists);
            $this->database->flush();
            return HttpResponse::json([
                'message' => "palestra atualizada com sucesso!",
            ]);
        } catch (\Throwable $th) {
            return HttpResponse::json([
                'message' => "ocorreu um erro ao atualizar a palestra!",
                "error" => $th->getMessage()
            ], 400);
        }
    }

    public function delete($id)
    {
        try {
            $lectureExist = $this->lecture->find($id);
            if (!isset($lectureExist)) {
                throw new \Exception('Palestra não encontrada.');
            }
        } catch (\Throwable $th) {
            return HttpResponse::json([
                'message' => $th->getMessage(),
            ], 401);
        }

        try {
            $this->database->remove($lectureExist);
            $this->database->flush();
            return HttpResponse::json([
                'message' => "palestra apagada com sucesso!"
            ]);
        } catch (\Throwable $th) {
            return HttpResponse::json([
                'message' => "ocorreu um erro ao apagar o evento!",
                'error' => $th->getMessage()
            ], 400);
        }
    }
}
