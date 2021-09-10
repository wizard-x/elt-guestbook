<?php namespace App\Controller\API;

use App\Services\GuestbookService;
use App\DTO\MessageDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class GuestbookAPIController extends AbstractController {
    private const HANDLERS = [
        'get' => '_get',
        'post' => '_post',
        'patch' => '_patch',
        'delete' => '_delete',
    ];

    /**
     * @Route("/guestbook", name="guestbook", methods={"GET", "POST", "PATCH", "DELETE"})
     */
    public function guestbook_api_endpoint(Request $request, GuestbookService $service): Response {
        $method = mb_strtolower($request->getMethod());
        if (array_key_exists($method, static::HANDLERS)) {
            $handlers = static::HANDLERS;
            if (method_exists($this, $handlers[$method])) {
                return call_user_func_array([$this, $handlers[$method]], [$request, $service]);
            }
        }

        return $this->json([
            'message' => 'Method not found',
        ], 500);
    }

    private function _get(Request $request, GuestbookService $service): Response {
        $messages = $service->getAllMessages();
        return $this->json(json_encode($messages), Response::HTTP_OK);
    }

    private function _post(Request $request, GuestbookService $service): Response {
        $data = json_decode($request->getContent(), true);
        $messageDTO = new MessageDTO();
        $messageDTO
            ->setRepliedTo((int)$data['repliedTo'])
            ->setAuthor(htmlspecialchars($data['author']))
            ->setText(htmlspecialchars($data['text']))
            ->setPublished(date('d.m.Y'))
        ;
        $id = $service->appendReply($messageDTO);
        return $this->json(['id' => $id], Response::HTTP_OK);
    }

    private function _patch(Request $request, GuestbookService $service): Response {
        return new Response('This is PATCH§');
    }

    private function _delete(Request $request, GuestbookService $service): Response {
        $data = json_decode($request->getContent(), true);
        $service->deleteMessage((int)$data['id']);
        return $this->json('{success: true}', Response::HTTP_OK);
    }
}
